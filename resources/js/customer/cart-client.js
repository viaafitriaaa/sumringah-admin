const idNumber = new Intl.NumberFormat('id-ID', { maximumFractionDigits: 2 });

export const number = (cents) => idNumber.format(cents / 100);
export const rupiah = (cents) => `Rp${number(cents)}`;

export function createRequest(csrf) {
    return async function request(url, body) {
        let response;
        try {
            response = await fetch(url, {
                method: body ? 'POST' : 'GET',
                credentials: 'same-origin',
                headers: { Accept: 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                body: body ? JSON.stringify(body) : undefined,
            });
        } catch {
            throw new Error('Koneksi terputus. Periksa sambungan dan coba lagi.');
        }
        const data = await response.json().catch(() => null);
        if (!response.ok || !data) {
            const validation = Object.values(data?.errors ?? {}).flat()[0];
            const message = response.status === 419 ? 'Sesi berakhir. Muat ulang halaman.'
                : validation ?? (response.status >= 500 ? 'Layanan belum tersedia. Coba lagi.' : data?.message) ?? 'Permintaan tidak berhasil.';
            throw Object.assign(new Error(message), { status: response.status });
        }
        return data;
    };
}

export function createCart(config, request) {
    let state = config.state;

    return {
        get state() { return state; },
        async refresh() { state = await request(config.urls.cartState); },
        async change(data) { state = await request(config.urls.cartUpdate, data); },
        checkout(data) { return request(config.urls.checkout, data); },
    };
}
