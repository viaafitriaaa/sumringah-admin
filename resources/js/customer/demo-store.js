const storageKey = 'warkop.customer.demo.v1';
const currency = new Intl.NumberFormat('id-ID');
const safeInteger = (value) => Number.isSafeInteger(value) ? value : 0;
const limitText = (value) => typeof value === 'string' ? value.slice(0, 160) : '';

export const number = (amount) => currency.format(amount);
export const rupiah = (amount) => `Rp${number(amount)}`;
export const orderTypeLabel = (type) => type === 'takeaway' ? 'Bawa pulang' : 'Makan di tempat';

export function createDemoStore({ catalog, options, taxRate, table }) {
    let memory;
    let storage;
    try { storage = window.localStorage; } catch { /* Use memory if storage is disabled. */ }

    function cleanOptions(item, selected) {
        if (!item.hasOptions) return {};
        return Object.fromEntries(Object.keys(options).flatMap((key) => {
            const quantity = Math.max(0, Math.min(options[key].max, safeInteger(selected?.[key])));
            return quantity ? [[key, quantity]] : [];
        }));
    }

    function cleanLines(lines) {
        if (!Array.isArray(lines)) return [];
        const used = new Map();
        const ids = new Set();
        const result = [];
        for (const line of lines) {
            if (!line || typeof line.item !== 'string' || !Object.hasOwn(catalog, line.item) || typeof line.id !== 'string' || !/^[a-z0-9-]+$/i.test(line.id) || ids.has(line.id)) continue;
            const item = catalog[line.item];
            const alreadyUsed = used.get(line.item) ?? 0;
            const quantity = Math.max(0, Math.min(item.stok - alreadyUsed, safeInteger(line.quantity)));
            if (!quantity) continue;
            used.set(line.item, alreadyUsed + quantity);
            ids.add(line.id);
            result.push({ id: line.id, item: line.item, quantity, options: cleanOptions(item, line.options), note: limitText(line.note) });
        }
        return result;
    }

    function read() {
        let raw = memory ?? {};
        try {
            if (storage) raw = JSON.parse(storage.getItem(storageKey) ?? 'null') ?? {};
        } catch { /* Use the in-memory state if storage is unavailable. */ }
        const receipt = raw.receipt;
        const receiptLines = cleanLines(receipt?.lines);
        return {
            lines: cleanLines(raw.lines),
            type: raw.type === 'takeaway' ? 'takeaway' : 'dine-in',
            note: limitText(raw.note),
            receipt: receiptLines.length && typeof receipt.id === 'string' && Number.isFinite(Date.parse(receipt.date))
                ? { ...receipt, lines: receiptLines }
                : null,
        };
    }

    function write(state) {
        memory = state;
        try {
            storage?.setItem(storageKey, JSON.stringify(state));
        } catch {
            storage = undefined;
        }
    }

    function unitPrice(itemId, selected = {}) {
        return catalog[itemId].harga + Object.entries(cleanOptions(catalog[itemId], selected))
            .reduce((total, [key, amount]) => total + options[key].price * amount, 0);
    }

    function totals(lines) {
        const subtotal = lines.reduce((total, line) => total + unitPrice(line.item, line.options) * line.quantity, 0);
        const tax = Math.round(subtotal * taxRate / 100);
        const count = lines.reduce((total, line) => total + line.quantity, 0);
        return { subtotal, tax, total: subtotal + tax, count };
    }

    function available(state, itemId, exceptId) {
        return catalog[itemId].stok - state.lines
            .filter((line) => line.item === itemId && line.id !== exceptId)
            .reduce((total, line) => total + line.quantity, 0);
    }

    function saveItem(itemId, draft, editId) {
        const state = read();
        const selected = cleanOptions(catalog[itemId], draft.options);
        const note = limitText(draft.note).trim();
        const match = editId
            ? state.lines.find((line) => line.id === editId && line.item === itemId)
            : state.lines.find((line) => line.item === itemId && line.note === note && JSON.stringify(line.options) === JSON.stringify(selected));
        if (editId && !match) return { error: 'Item yang ingin diubah sudah tidak ada di keranjang.' };
        const quantity = safeInteger(draft.quantity) + (editId ? 0 : match?.quantity ?? 0);
        if (quantity < 1 || quantity > available(state, itemId, match?.id)) return { error: 'Jumlah melebihi stok yang tersedia di demo.' };
        const line = { id: match?.id ?? createId(), item: itemId, quantity, options: selected, note };
        state.lines = match ? state.lines.map((entry) => entry.id === line.id ? line : entry) : [...state.lines, line];
        write(state);
        return { state };
    }

    function updateLine(id, quantity) {
        const state = read();
        const line = state.lines.find((entry) => entry.id === id);
        if (!line || quantity < 0 || quantity > available(state, line.item, id)) return false;
        state.lines = quantity === 0 ? state.lines.filter((entry) => entry.id !== id)
            : state.lines.map((entry) => entry.id === id ? { ...entry, quantity } : entry);
        write(state);
        return true;
    }

    function setDetails(details) {
        const state = read();
        if ('note' in details) state.note = limitText(details.note);
        if ('type' in details) state.type = details.type === 'takeaway' ? 'takeaway' : 'dine-in';
        write(state);
    }

    function simulateCheckout(expected) {
        const state = read();
        if (!state.lines.length || JSON.stringify(state.lines) !== JSON.stringify(expected.lines)
            || state.type !== expected.type || state.note !== expected.note) return null;
        const receipt = {
            id: `DEMO-${createId().slice(0, 8).toUpperCase()}`,
            date: new Date().toISOString(),
            table,
            type: state.type,
            note: state.note,
            lines: state.lines,
        };
        // Contact inputs are validated by the mock UI but never persisted or transmitted.
        write({ lines: [], type: 'dine-in', note: '', receipt });
        return receipt;
    }

    return { read, totals, unitPrice, available, saveItem, updateLine, setDetails, simulateCheckout, storageKey };
}

function createId() {
    return globalThis.crypto?.randomUUID?.() ?? `${Math.random().toString(36).slice(2)}-${Date.now().toString(36)}`;
}
