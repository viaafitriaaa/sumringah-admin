import { createDemoStore, number, orderTypeLabel, rupiah } from './demo-store.js';

const root = document.querySelector('[data-customer-page]');
const config = JSON.parse(document.getElementById('customer-data').textContent);
const store = createDemoStore(config);
const $ = (selector, container = root) => container.querySelector(selector);
const $$ = (selector, container = root) => [...container.querySelectorAll(selector)];
const writeText = (selector, value, container = root) => $$(selector, container).forEach((element) => { element.textContent = value; });
const checkoutDate = new Intl.DateTimeFormat('id-ID', { dateStyle: 'medium', timeStyle: 'short', timeZone: config.timeZone });
let toastTimer;

function toast(message) {
    const region = $('[data-toast]');
    clearTimeout(toastTimer);
    region.textContent = message;
    toastTimer = setTimeout(() => { region.textContent = ''; }, 3200);
}

function money(state = store.read()) {
    const totals = store.totals(state.lines);
    writeText('[data-cart-count]', totals.count);
    writeText('[data-cart-subtotal]', number(totals.subtotal));
    writeText('[data-subtotal]', rupiah(totals.subtotal));
    writeText('[data-tax]', rupiah(totals.tax));
    writeText('[data-total]', rupiah(totals.total));
    for (const link of $$('[data-cart-link]')) link.hidden = totals.count === 0;
    return totals;
}

function setQuantity(control, value, max = Number(control.dataset.max), min = Number(control.dataset.min ?? 0)) {
    control.dataset.max = max;
    const next = Math.max(0, Math.min(max, Math.max(min, value)));
    control.dataset.value = next;
    $('[data-count]', control).textContent = next;
    $('[data-step="-1"]', control).disabled = next <= min;
    $('[data-step="1"]', control).disabled = next >= max;
    return next;
}

function bindQuantity(container, callback) {
    container.addEventListener('click', (event) => {
        const step = event.target.closest('[data-step]');
        if (!step) return;
        const control = step.closest('[data-quantity]');
        const next = setQuantity(control, Number(control.dataset.value) + Number(step.dataset.step));
        callback(control, next, step);
    });
}

function startCatalog() {
    const toggle = $('[data-search-toggle]');
    const search = $('#menu-search');
    const input = $('[data-search-input]');
    const rows = $$('[data-menu-row]');
    const activeTab = $('[aria-current="page"]');
    if (activeTab) {
        const navigation = activeTab.parentElement;
        navigation.scrollLeft = Math.max(0, activeTab.getBoundingClientRect().right - navigation.getBoundingClientRect().right + 8);
    }
    const filter = () => {
        const query = input.value.trim().toLocaleLowerCase('id');
        let count = 0;
        for (const row of rows) {
            row.hidden = !row.dataset.menuName.toLocaleLowerCase('id').includes(query);
            if (!row.hidden) count++;
        }
        $('[data-no-results]').hidden = count !== 0;
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
    const itemId = $('[data-item]').dataset.item;
    const item = config.catalog[itemId];
    const editId = new URLSearchParams(location.search).get('ubah');
    const state = store.read();
    const initial = state.lines.find((line) => line.id === editId && line.item === itemId);
    const qty = $('[data-main-quantity]');
    const lunchBox = $('[data-lunch-box]');
    const note = $('[data-item-note]');
    const add = $('[data-add-item]');
    const action = $('[data-item-action]');
    const originalLabel = initial ? 'Simpan Perubahan' : action.textContent;
    const optionControls = $$('[data-option]');
    let active = false;

    function draft() {
        const options = Object.fromEntries(optionControls.map((control) => [control.dataset.option, Number(control.dataset.value)]));
        if (lunchBox?.checked) options['lunch-box'] = 1;
        return { options, quantity: Number(qty.dataset.value), note: note.value };
    }

    function refresh() {
        const current = store.read();
        const max = store.available(current, itemId, editId);
        setQuantity(qty, Number(qty.dataset.value || 1), max);
        add.disabled = active || max < 1 || (editId && !current.lines.some((line) => line.id === editId && line.item === itemId));
        action.textContent = max < 1 && item.stok > 0 ? 'Stok sudah di keranjang' : originalLabel;
        writeText('[data-item-total]', number(store.unitPrice(itemId, draft().options) * Number(qty.dataset.value)));
    }

    setQuantity(qty, initial?.quantity ?? 1, store.available(state, itemId, editId));
    for (const control of optionControls) setQuantity(control, initial?.options[control.dataset.option] ?? 0);
    if (lunchBox) {
        lunchBox.checked = Boolean(initial?.options['lunch-box']);
        lunchBox.addEventListener('change', refresh);
    }
    note.value = initial?.note ?? '';
    if (editId && !initial) toast('Item yang ingin diubah sudah tidak ada di keranjang.');
    bindQuantity(root, refresh);
    add.addEventListener('click', () => {
        const result = store.saveItem(itemId, draft(), editId);
        if (result.error) { toast(result.error); refresh(); return; }
        active = true;
        add.disabled = true;
        sessionToast(editId ? 'Pesanan diperbarui.' : 'Menu ditambahkan ke keranjang.');
        location.assign(editId ? config.urls.cart : `${config.urls.catalog}/${item.category}`);
    });
    refresh();
    return refresh;
}

function sessionToast(message) {
    try { sessionStorage.setItem('warkop.customer.toast', message); } catch { /* Optional feedback. */ }
}

function focusRow(id, selector) {
    const row = $$('[data-line-id]').find((element) => element.dataset.lineId === id);
    const target = row && ($(selector, row)?.disabled ? $('button:not(:disabled)', row) : $(selector, row));
    target?.focus();
}

function lineOptions(line) {
    return Object.entries(line.options).map(([key, qty]) => `${qty}× ${config.options[key].name}`).join(' · ');
}

function startCart() {
    const lines = $('[data-cart-lines]');
    const template = $('[data-cart-line-template]');
    const type = $('[data-order-type]');
    const note = $('[data-cart-note]');

    function render() {
        const state = store.read();
        const hasItems = state.lines.length > 0;
        $('[data-cart-empty]').hidden = hasItems;
        $('[data-cart-content]').hidden = !hasItems;
        $('[data-cart-footer]').hidden = !hasItems;
        type.value = state.type;
        if (document.activeElement !== note) note.value = state.note;
        money(state);
        lines.replaceChildren(...state.lines.map((line) => {
            const item = config.catalog[line.item];
            const row = template.content.firstElementChild.cloneNode(true);
            row.dataset.lineId = line.id;
            writeText('[data-line-name]', item.nama_menu, row);
            writeText('[data-line-price]', rupiah(store.unitPrice(line.item, line.options) * line.quantity), row);
            for (const [selector, text] of [['[data-line-options]', lineOptions(line)], ['[data-line-note]', line.note]]) {
                const element = $(selector, row);
                element.hidden = !text;
                element.textContent = text;
            }
            const edit = $('[data-line-edit]', row);
            edit.href = `${config.urls.item.replace('__item__', line.item)}?dari=pesanan&ubah=${line.id}`;
            edit.setAttribute('aria-label', `Ubah ${item.nama_menu}`);
            $('[data-line-remove]', row).setAttribute('aria-label', `Hapus ${item.nama_menu}`);
            setQuantity($('[data-quantity]', row), line.quantity, store.available(state, line.item, line.id));
            return row;
        }));
    }

    bindQuantity(lines, (control, next, step) => {
        const { lineId } = control.closest('[data-line-id]').dataset;
        if (!store.updateLine(lineId, next)) toast('Stok atau isi keranjang sudah berubah.');
        render();
        focusRow(lineId, `[data-step="${step.dataset.step}"]`);
    });
    lines.addEventListener('click', (event) => {
        const remove = event.target.closest('[data-line-remove]');
        if (!remove) return;
        store.updateLine(remove.closest('[data-line-id]').dataset.lineId, 0);
        render();
        ($('[data-line-edit]') ?? $('[data-cart-empty] a'))?.focus();
        toast('Item dihapus dari keranjang.');
    });
    type.addEventListener('change', () => store.setDetails({ type: type.value }));
    note.addEventListener('input', () => store.setDetails({ note: note.value }));
    render();
    return render;
}

function startCheckout() {
    const form = $('[data-checkout-form]');
    const consent = $('[data-checkout-consent]');
    const dialog = $('[data-payment-dialog]');
    const confirm = $('[data-confirm-payment]');
    const pay = $('[data-checkout-submit]');
    const error = $('[data-checkout-error]');
    let pending;
    let paying = false;

    function showError(message) {
        error.hidden = !message;
        error.textContent = message;
    }
    function validateInputs() {
        const { name, phone, payment } = form.elements;
        const phoneText = phone.value.trim();
        const digits = phoneText.replace(/\D/g, '').length;
        name.setCustomValidity(name.value && name.value.trim().length < 2 ? 'Masukkan nama minimal 2 karakter.' : '');
        phone.setCustomValidity(phoneText && (!/^\+?[\d ()-]+$/.test(phoneText) || digits < 8 || digits > 15) ? 'Masukkan nomor ponsel 8–15 digit.' : '');
        consent.hidden = !(name.value.trim().length >= 2 && payment.value);
        form.elements.consent.disabled = consent.hidden;
    }
    function render() {
        const state = store.read();
        const hasItems = state.lines.length > 0;
        $('[data-checkout-empty]').hidden = hasItems;
        form.hidden = !hasItems;
        $('[data-checkout-footer]').hidden = !hasItems;
        writeText('[data-checkout-type]', orderTypeLabel(state.type));
        money(state);
    }
    form.addEventListener('input', () => { validateInputs(); showError(''); });
    form.addEventListener('submit', (event) => {
        event.preventDefault();
        validateInputs();
        if (paying || !form.reportValidity()) return;
        pending = store.read();
        if (!pending.lines.length) { render(); return; }
        writeText('[data-payment-total]', rupiah(store.totals(pending.lines).total));
        dialog.showModal();
    });
    $('[data-cancel-payment]').addEventListener('click', () => dialog.close());
    dialog.addEventListener('cancel', (event) => { if (paying) event.preventDefault(); });
    confirm.addEventListener('click', () => {
        if (paying || !pending) return;
        paying = true;
        confirm.disabled = true;
        $('[data-cancel-payment]').disabled = true;
        pay.disabled = true;
        confirm.textContent = 'Memproses…';
        dialog.setAttribute('aria-busy', 'true');
        setTimeout(() => {
            if (store.simulateCheckout(pending)) {
                location.replace(config.urls.success);
                return;
            }
            paying = false;
            pay.disabled = false;
            confirm.disabled = false;
            $('[data-cancel-payment]').disabled = false;
            confirm.textContent = 'Konfirmasi Demo';
            dialog.removeAttribute('aria-busy');
            dialog.close();
            render();
            showError('Keranjang berubah. Periksa ringkasan lalu coba lagi.');
            toast('Keranjang berubah. Periksa pesanan.');
        }, 420);
    });
    validateInputs();
    render();
    return render;
}

function renderReceipt() {
    const receipt = store.read().receipt;
    $('[data-order-empty]').hidden = Boolean(receipt);
    $('[data-order-content]').hidden = !receipt;
    if (!receipt) return;
    const totals = store.totals(receipt.lines);
    const entries = {
        id: receipt.id, date: checkoutDate.format(new Date(receipt.date)), table: receipt.table,
        type: orderTypeLabel(receipt.type), method: 'QRIS Demo', subtotal: rupiah(totals.subtotal),
        tax: rupiah(totals.tax), total: rupiah(totals.total), rate: config.taxRate,
    };
    for (const [key, value] of Object.entries(entries)) writeText(`[data-order-field="${key}"]`, value);
    const lines = $('[data-order-lines]');
    if (!lines) return;
    const note = $('[data-receipt-note]');
    note.hidden = !receipt.note.trim();
    note.textContent = note.hidden ? '' : `Catatan pesanan: ${receipt.note}`;
    const template = $('[data-order-line-template]');
    lines.replaceChildren(...receipt.lines.map((line) => {
        const item = config.catalog[line.item];
        const row = template.content.firstElementChild.cloneNode(true);
        const photo = $('[data-order-line-photo]', row);
        photo.src = `${config.assets}/${item.foto}`;
        photo.classList.toggle('customer-product-image--banner', item.foto.startsWith('cat-'));
        writeText('[data-order-line-name]', item.nama_menu, row);
        writeText('[data-order-line-price]', rupiah(store.unitPrice(line.item, line.options)), row);
        writeText('[data-order-line-extra]', [lineOptions(line), line.note].filter(Boolean).join(' · '), row);
        writeText('[data-order-line-quantity]', `×${line.quantity}`, row);
        return row;
    }));
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

let refresh = money;
switch (root.dataset.customerPage) {
    case 'catalog': startCatalog(); break;
    case 'item': refresh = startItem(); break;
    case 'cart': refresh = startCart(); break;
    case 'checkout': refresh = startCheckout(); break;
    case 'success':
    case 'order': refresh = renderReceipt; break;
    case 'outlet': startOutlet(); break;
}
refresh();
window.addEventListener('storage', (event) => { if (event.key === store.storageKey || event.key === null) refresh(); });
window.addEventListener('pageshow', (event) => { if (event.persisted) refresh(); });
try {
    const message = sessionStorage.getItem('warkop.customer.toast');
    if (message) {
        sessionStorage.removeItem('warkop.customer.toast');
        toast(message);
        $('[data-cart-link] a')?.classList.add('customer-cart-pulse');
    }
} catch { /* Storage permission does not prevent navigation. */ }
