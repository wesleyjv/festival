<?php
/**
 * @var string $bodyClass
 * @var string $mainClass
 */
require __DIR__ . '/../partials/header.php';
?>

<div class="<?= htmlspecialchars($mainClass, ENT_QUOTES, 'UTF-8') ?>">
    <div class="ticket-scan-wrap mx-auto px-3 py-4" style="max-width: 28rem;">
        <h1 class="h4 fw-bold mb-1">Ticket scanner</h1>
        <p class="text-muted small mb-4">Scan a QR code or type the ticket code. Camera requires a secure connection (HTTPS) on most phones.</p>

        <div id="scan-feedback" class="alert d-none mb-3" role="alert"></div>

        <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
            <div class="card-body p-0">
                <div id="qr-reader" class="bg-dark" style="min-height: 220px;"></div>
            </div>
            <div class="card-footer bg-white border-0 py-3 d-grid gap-2">
                <button type="button" class="btn btn-dark rounded-3" id="btn-start-camera">
                    <i class="bi bi-camera-fill me-1"></i> Start camera
                </button>
                <button type="button" class="btn btn-outline-secondary rounded-3 d-none" id="btn-stop-camera">
                    <i class="bi bi-stop-circle me-1"></i> Stop camera
                </button>
            </div>
        </div>

        <form id="manual-scan-form" class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <label for="manual-code" class="form-label fw-semibold">Manual entry</label>
                <input type="text" class="form-control form-control-lg rounded-3 mb-3" id="manual-code"
                       name="code" autocomplete="off" placeholder="e.g. JZ-12-A1B2C3D4E"
                       inputmode="text" autocapitalize="characters">
                <button type="submit" class="btn btn-primary w-100 rounded-3">
                    <i class="bi bi-keyboard me-1"></i> Check ticket
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/html5-qrcode@2.3.8/html5-qrcode.min.js" crossorigin="anonymous"></script>
<script>
(function () {
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    const feedback = document.getElementById('scan-feedback');
    const readerElId = 'qr-reader';
    let html5Qr = null;
    let scanning = false;
    let submitPending = false;
    let lastDecode = '';
    let lastDecodeAt = 0;

    function showFeedback(kind, message) {
        feedback.classList.remove('d-none', 'alert-success', 'alert-warning', 'alert-danger', 'alert-info');
        if (kind === 'success') {
            feedback.classList.add('alert-success');
        } else if (kind === 'warning') {
            feedback.classList.add('alert-warning');
        } else if (kind === 'error') {
            feedback.classList.add('alert-danger');
        } else {
            feedback.classList.add('alert-info');
        }
        feedback.textContent = message;
    }

    function hideFeedback() {
        feedback.classList.add('d-none');
        feedback.textContent = '';
    }

    async function submitCode(raw, opts) {
        const fromCamera = opts && opts.fromCamera;
        const code = (raw || '').trim();
        if (!code) {
            showFeedback('error', 'Enter or scan a ticket code.');
            return;
        }
        if (fromCamera) {
            const now = Date.now();
            if (code === lastDecode && now - lastDecodeAt < 3500) {
                return;
            }
            lastDecode = code;
            lastDecodeAt = now;
        }
        if (submitPending) {
            return;
        }
        submitPending = true;
        hideFeedback();
        try {
            const res = await fetch('/employee/scan', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': csrf
                },
                body: JSON.stringify({ code: code })
            });
            const data = await res.json();
            if (!data.ok) {
                showFeedback('error', data.error || 'Request failed.');
                return;
            }
            const name = data.ticket ? data.ticket.name : '';
            if (data.status === 'success') {
                showFeedback('success', (name ? name + ' — ' : '') + data.message);
            } else if (data.status === 'warning') {
                showFeedback('warning', (name ? name + ' — ' : '') + data.message);
            } else if (data.status === 'not_found') {
                showFeedback('error', data.message);
            } else if (data.status === 'invalid') {
                showFeedback('error', data.message);
            } else {
                showFeedback('info', data.message || 'Done.');
            }
        } catch (e) {
            showFeedback('error', 'Network error. Try again.');
        } finally {
            submitPending = false;
        }
    }

    document.getElementById('manual-scan-form').addEventListener('submit', function (e) {
        e.preventDefault();
        submitCode(document.getElementById('manual-code').value, { fromCamera: false });
    });

    const btnStart = document.getElementById('btn-start-camera');
    const btnStop = document.getElementById('btn-stop-camera');

    btnStart.addEventListener('click', async function () {
        if (typeof Html5Qrcode === 'undefined') {
            showFeedback('error', 'Scanner library failed to load. Use manual entry.');
            return;
        }
        hideFeedback();
        if (!html5Qr) {
            html5Qr = new Html5Qrcode(readerElId);
        }
        if (scanning) {
            return;
        }
        const config = { fps: 8, qrbox: { width: 240, height: 240 } };
        try {
            await html5Qr.start(
                { facingMode: 'environment' },
                config,
                function (decodedText) {
                    submitCode(decodedText, { fromCamera: true });
                },
                function () { /* frame noise; ignore */ }
            );
            scanning = true;
            btnStart.classList.add('d-none');
            btnStop.classList.remove('d-none');
        } catch (err) {
            showFeedback('error', 'Could not start camera. Allow permission or use manual entry.');
        }
    });

    btnStop.addEventListener('click', async function () {
        if (!html5Qr || !scanning) {
            return;
        }
        try {
            await html5Qr.stop();
            html5Qr.clear();
        } catch (e) { /* ignore */ }
        scanning = false;
        btnStart.classList.remove('d-none');
        btnStop.classList.add('d-none');
    });
})();
</script>

<style>
.ticket-scan-page {
    padding-top: 56px;
}
.ticket-scan-main {
    max-width: 100%;
    margin: 0;
    padding: 0;
}
#qr-reader video {
    width: 100% !important;
    border-radius: 0;
}
</style>

<?php require __DIR__ . '/../partials/footer.php'; ?>
