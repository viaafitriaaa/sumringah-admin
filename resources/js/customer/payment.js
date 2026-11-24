export function startPayment(panel, request, toast) {
    if (!panel) return;

    const find = (selector) => panel.querySelector(selector);
    const checkButton = find('[data-payment-check]');
    const retryButton = find('[data-payment-retry]');
    const message = find('[data-payment-message]');
    const countdown = find('[data-payment-countdown]');
    const code = find('[data-payment-code]');
    const image = find('[data-payment-image]');
    const qrLink = find('[data-payment-qr-link]');
    let qrUrl = panel.dataset.qrUrl;
    let expiry = Date.parse(panel.dataset.expiresAt);
    let timer;
    let busy = false;
    let done = false;
    let delay = 8000;

    function renderCountdown() {
        const hasExpiry = Number.isFinite(expiry);
        const remaining = Math.max(0, Math.ceil((expiry - Date.now()) / 1000));
        countdown.hidden = !hasExpiry;
        code.hidden = !qrUrl || (hasExpiry && remaining === 0);
        if (hasExpiry) {
            const minutes = String(Math.floor(remaining / 60)).padStart(2, '0');
            const seconds = String(remaining % 60).padStart(2, '0');
            countdown.textContent = remaining ? `Berlaku ${minutes}:${seconds}` : 'Waktu habis. Menunggu konfirmasi Midtrans.';
        }
    }

    async function check(retry = false, manual = false) {
        if (busy || done) return;
        clearTimeout(timer);
        busy = true;
        checkButton.disabled = true;
        retryButton.disabled = true;
        if (manual) message.textContent = retry ? 'Menyiapkan kode QR…' : 'Mengecek status…';
        try {
            const payment = await request(panel.dataset.statusUrl, { retry });
            if (payment.redirect) {
                done = true;
                clearInterval(clock);
                location.replace(payment.redirect);
                return;
            }
            if (payment.qrUrl && payment.qrUrl !== qrUrl) {
                qrUrl = payment.qrUrl;
                image.src = qrUrl;
                qrLink.href = qrUrl;
            }
            expiry = Date.parse(payment.expiresAt);
            retryButton.hidden = Boolean(qrUrl);
            message.textContent = qrUrl ? 'Menunggu pembayaran…' : 'Kode QR belum tersedia. Tekan Coba Lagi.';
            delay = 8000;
            renderCountdown();
        } catch (failure) {
            message.textContent = [401, 404, 419].includes(failure.status)
                ? 'Pembayaran tidak tersedia di sesi ini. Muat ulang halaman.'
                : failure.message;
            delay = Math.min(delay * 2, 30000);
            done = [401, 404, 419].includes(failure.status);
        } finally {
            busy = false;
            checkButton.disabled = done;
            retryButton.disabled = done;
            if (!done && !document.hidden) timer = setTimeout(check, delay);
        }
    }

    checkButton.addEventListener('click', () => void check(false, true));
    retryButton.addEventListener('click', () => void check(true, true));
    find('[data-payment-copy]').addEventListener('click', async () => {
        try {
            await navigator.clipboard.writeText(qrUrl);
            toast('Tautan QR disalin.');
        } catch {
            toast('Buka gambar QR lalu salin alamatnya.');
        }
    });
    document.addEventListener('visibilitychange', () => {
        if (document.hidden) clearTimeout(timer);
        else void check();
    });

    const clock = setInterval(renderCountdown, 1000);
    renderCountdown();
    if (!document.hidden) timer = setTimeout(check, qrUrl ? delay : 0);
}
