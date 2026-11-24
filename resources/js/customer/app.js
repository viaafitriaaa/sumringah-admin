import { createCart, createRequest, number, rupiah } from './cart-client.js';
import { startPayment } from './payment.js';

const root = document.querySelector('[data-customer-page]');
const config = JSON.parse(document.getElementById('customer-data').textContent);
const request = createRequest(document.querySelector('meta[name="csrf-token"]').content);
const cart = createCart(config, request);
const $ = (selector, container = root) => container.querySelector(selector);
const $$ = (selector, container = root) => [...container.querySelectorAll(selector)];
const write = (selector, value, container = root) => $$(selector, container).forEach((element) => { element.textContent = value; });
let toastTimer;

function toast(message) {
    clearTimeout(toastTimer);
    const region = $('[data-toast]');
    region.textContent = message;
    toastTimer = setTimeout(() => { region.textContent = ''; }, 3500);
}

function renderTotals() {
    const { totals } = cart.state;
    write('[data-cart-count]', totals.count);
    write('[data-cart-subtotal]', number(totals.subtotal));
    write('[data-subtotal]', rupiah(totals.subtotal));
    write('[data-discount]', rupiah(totals.discount));
    write('[data-tax]', rupiah(totals.tax));
    write('[data-rounding]', rupiah(totals.rounding));
    for (const row of $$('[data-rounding-row]')) row.hidden = totals.rounding === 0;
    write('[data-total]', rupiah(totals.total));
    for (const link of $$('[data-cart-link]')) link.hidden = totals.count === 0;
}

function setQuantity(control, value, max = Number(control.dataset.max)) {
    const min = Number(control.dataset.min ?? 0);
    const next = Math.max(0, Math.min(max, Math.max(min, value)));
    control.dataset.max = max;
    control.dataset.value = next;
    write('[data-count]', next, control);
    $('[data-step="-1"]', control).disabled = next <= min;
    $('[data-step="1"]', control).disabled = next >= max;
    return next;
}

function startCatalog() {
    const toggle = $('[data-search-toggle]');
    const search = $('#menu-search');
    const input = $('[data-search-input]');
    const rows = $$('[data-menu-row]');
    const active = $('[aria-current="page"]');
    if (active) {
        const navigation = active.parentElement;
        navigation.scrollLeft = Math.max(0, active.getBoundingClientRect().right - navigation.getBoundingClientRect().right + 8);
    }
    const filter = () => {
        const query = input.value.trim().toLocaleLowerCase('id');
        let count = 0;
        for (const row of rows) {
            row.hidden = !row.dataset.menuName.toLocaleLowerCase('id').includes(query);
            if (!row.hidden) count++;
        }
        $('[data-no-results]').hidden = count > 0;
    };
    function showSearch(show) {
        search.hidden = !show;
        toggle.setAttribute('aria-expanded', String(show));
        if (show) input.focus();
        else { input.value = ''; filter(); toggle.focus(); }
    }
    toggle.addEventListener('click', () => showSearch(search.hidden));
    input.addEventListener('input', filter);
    input.addEventListener('keydown', (event) => { if (event.key === 'Escape') showSearch(false); });
}

function startItem() {
    const item = config.product;
    const editId = new URLSearchParams(location.search).get('ubah');
    const currentLine = () => cart.state.lines.find((line) => line.id === editId && line.item === item.id);
    const qty = $('[data-main-quantity]');
    const note = $('[data-item-note]');
    const add = $('[data-add-item]');
    const action = $('[data-item-action]');
    const originalLabel = editId ? 'Simpan Perubahan' : action.textContent;
    const optionControls = $$('[data-option]');
    let busy = false;

    function available() {
        const used = cart.state.lines.filter((line) => line.item === item.id && line.id !== editId)
            .reduce((sum, line) => sum + line.quantity, 0);
        return Math.max(0, item.stok - used);
    }
    function draft() {
        const options = Object.fromEntries(optionControls.map((control) => [control.dataset.option, Number(control.dataset.value)]));
        return { options, quantity: Number(qty.dataset.value), note: note.value };
    }
    function render() {
        const max = available();
        setQuantity(qty, Number(qty.dataset.value || 1), max);
        add.disabled = busy || !cart.state.table || max < 1 || Boolean(editId && !currentLine());
        action.textContent = busy ? 'Menyimpan…' : max < 1 && item.stok > 0 ? 'Stok sudah di keranjang' : originalLabel;
        const extras = Object.entries(draft().options).reduce((sum, [key, value]) => sum + item.options[key].price * value, 0);
        write('[data-item-total]', number((item.price + extras) * Number(qty.dataset.value)));
    }

    const initial = currentLine();
    setQuantity(qty, initial?.quantity ?? 1, available());
    for (const control of optionControls) {
        setQuantity(control, initial?.options.find((option) => option.id === control.dataset.option)?.quantity ?? 0);
    }
    note.value = initial?.note ?? '';
    if (editId && !initial) toast('Item sudah tidak ada di keranjang.');
    root.addEventListener('click', (event) => {
        const step = event.target.closest('[data-step]');
        if (!step || busy) return;
        const control = step.closest('[data-quantity]');
        setQuantity(control, Number(control.dataset.value) + Number(step.dataset.step));
        render();
    });
    add.addEventListener('click', async () => {
        busy = true;
        render();
        try {
            await cart.change({ action: 'item', menu: Number(item.id), line: editId, ...draft() });
            try {
                sessionStorage.setItem('warkop.customer.toast', editId ? 'Pesanan diperbarui.' : 'Menu ditambahkan ke keranjang.');
            } catch { /* Feedback is optional if browser storage is disabled. */ }
            location.assign(editId ? config.urls.cart : `${config.urls.catalog}/${item.category}`);
        } catch (error) {
            toast(error.message);
            busy = false;
            render();
        }
    });
    return render;
}

function startCart() {
    const lines = $('[data-cart-lines]');
    const template = $('[data-cart-line-template]');
    const type = $('[data-order-type]');
    const note = $('[data-cart-note]');
    const next = $('[data-checkout-link]');
    const errorRegion = $('[data-cart-error]');
    let pending = Promise.resolve(true);

    function error(message) {
        errorRegion.hidden = !message;
        errorRegion.textContent = message;
    }
    function render() {
        const state = cart.state;
        const hasItems = state.lines.length > 0;
        $('[data-cart-empty]').hidden = hasItems;
        $('[data-cart-content]').hidden = !hasItems;
        $('[data-cart-footer]').hidden = !hasItems;
        next.setAttribute('aria-disabled', String(!state.valid || !state.table));
        type.value = state.type;
        if (document.activeElement !== note) note.value = state.note;
        renderTotals();
        lines.replaceChildren(...state.lines.map((line) => {
            const row = template.content.firstElementChild.cloneNode(true);
            row.dataset.lineId = line.id;
            write('[data-line-name]', line.name, row);
            write('[data-line-price]', rupiah(line.amount), row);
            for (const [selector, text] of [
                ['[data-line-options]', line.options.map((option) => `${option.quantity}× ${option.name}`).join(' · ')],
                ['[data-line-note]', line.note],
                ['[data-line-issue]', line.issue],
            ]) {
                const element = $(selector, row);
                element.hidden = !text;
                element.textContent = text ?? '';
            }
            const edit = $('[data-line-edit]', row);
            edit.href = `${config.urls.item.replace('__item__', line.item)}?dari=pesanan&ubah=${line.id}`;
            edit.setAttribute('aria-label', `Ubah ${line.name}`);
            if (!line.max) edit.hidden = true;
            $('[data-line-remove]', row).setAttribute('aria-label', `Hapus ${line.name}`);
            const qty = $('[data-quantity]', row);
            setQuantity(qty, line.quantity, line.max);
            if (line.issue) {
                qty.dataset.value = line.quantity;
                write('[data-count]', line.quantity, qty);
            }
            return row;
        }));
    }

    function mutate(payload, keepFocus) {
        pending = pending.then(async () => {
            try {
                await cart.change(payload);
                render();
                error('');
                keepFocus?.();
                return true;
            } catch (failure) {
                error(failure.message);
                return false;
            }
        });
        return pending;
    }

    lines.addEventListener('click', (event) => {
        const lineId = event.target.closest('[data-line-id]')?.dataset.lineId;
        if (!lineId) return;
        const step = event.target.closest('[data-step]');
        if (step) {
            const control = step.closest('[data-quantity]');
            const value = Math.max(1, Math.min(Number(control.dataset.max), Number(control.dataset.value) + Number(step.dataset.step)));
            step.disabled = true;
            void mutate({ action: 'quantity', line: lineId, quantity: value }, () => {
                const row = $$('[data-line-id]').find((element) => element.dataset.lineId === lineId);
                if (row) $('button:not([disabled])', row)?.focus();
            }).then((ok) => { if (!ok) step.disabled = false; });
        } else if (event.target.closest('[data-line-remove]')) {
            void mutate({ action: 'remove', line: lineId }, () => {
                ($('[data-line-edit]:not([hidden])') ?? $('[data-cart-empty] a'))?.focus();
                toast('Item dihapus dari keranjang.');
            });
        }
    });
    const saveDetails = () => mutate({ action: 'details', type: type.value, note: note.value });
    type.addEventListener('change', saveDetails);
    note.addEventListener('change', saveDetails);
    next.addEventListener('click', async (event) => {
        event.preventDefault();
        if (!cart.state.valid || !cart.state.table) { error('Periksa meja, stok, dan pilihan di keranjang.'); return; }
        if (await saveDetails()) location.assign(next.href);
    });
    return render;
}

function startCheckout() {
    const form = $('[data-checkout-form]');
    const consent = $('[data-checkout-consent]');
    const button = $('[data-checkout-submit]');
    const region = $('[data-checkout-error]');
    const canPay = Boolean(consent);
    let busy = false;

    function error(message) {
        region.hidden = !message;
        region.textContent = message;
    }
    function validate() {
        if (!canPay) return;
        const fields = form.elements;
        const phone = fields.phone.value.trim();
        const digits = phone.replace(/\D/g, '').length;
        fields.name.setCustomValidity(fields.name.value && fields.name.value.trim().length < 2 ? 'Masukkan nama minimal 2 karakter.' : '');
        fields.phone.setCustomValidity(phone && (!/^\+?[\d ()-]+$/.test(phone) || digits < 8 || digits > 15) ? 'Masukkan nomor ponsel 8–15 digit.' : '');
        consent.hidden = !(fields.name.value.trim().length >= 2 && fields.payment.checked);
        fields.consent.disabled = consent.hidden;
    }
    function render() {
        const state = cart.state;
        const hasItems = state.lines.length > 0;
        $('[data-checkout-empty]').hidden = hasItems;
        form.hidden = !hasItems;
        $('[data-checkout-footer]').hidden = !hasItems;
        button.disabled = busy || !canPay || !state.valid || !state.table;
        button.textContent = busy ? 'Menyimpan…' : 'Lanjut Bayar';
        write('[data-checkout-type]', state.type === 'takeaway' ? 'Bawa pulang' : 'Makan di tempat');
        if (hasItems && (!state.valid || !state.table)) error('Periksa nomor meja dan ketersediaan item di keranjang.');
        renderTotals();
    }
    form.addEventListener('input', () => { error(''); validate(); });
    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        validate();
        if (button.disabled || !form.reportValidity()) return;
        const fields = Object.fromEntries(new FormData(form));
        busy = true;
        render();
        try {
            const { url } = await cart.checkout({
                name: fields.name, phone: fields.phone || null, email: fields.email || null,
                payment: fields.payment, consent: fields.consent, checkout_key: cart.state.key, quote: cart.state.signature,
            });
            location.replace(url);
        } catch (failure) {
            if (failure.status === 422) {
                try { await cart.refresh(); } catch { /* Keep the original validation message. */ }
            }
            busy = false;
            render();
            error(failure.message);
        }
    });
    validate();
    return render;
}

function startOutlet() {
    const contact = $('[data-contact-dialog]');
    $('[data-outlet-contact]').addEventListener('click', () => contact.showModal());
    $('[data-share]').addEventListener('click', async () => {
        try {
            if (navigator.share) await navigator.share({ title: 'Warkop Sumringah', url: location.href });
            else { await navigator.clipboard.writeText(location.href); toast('Tautan outlet disalin.'); }
        } catch (error) {
            if (error.name !== 'AbortError') toast('Tautan bisa disalin dari bilah alamat.');
        }
    });
}

let render = renderTotals;
switch (root.dataset.customerPage) {
    case 'catalog': startCatalog(); break;
    case 'item': render = startItem(); break;
    case 'cart': render = startCart(); break;
    case 'checkout': render = startCheckout(); break;
    case 'payment': startPayment($('[data-midtrans-payment]'), request, toast); break;
    case 'outlet': startOutlet(); break;
}
render();
window.addEventListener('pageshow', async (event) => {
    if (event.persisted) {
        try { await cart.refresh(); render(); } catch (error) { toast(error.message); }
    }
});
try {
    const message = sessionStorage.getItem('warkop.customer.toast');
    if (message) {
        sessionStorage.removeItem('warkop.customer.toast');
        toast(message);
        $('[data-cart-link] a')?.classList.add('customer-cart-pulse');
    }
} catch { /* Feedback is optional if browser storage is disabled. */ }
