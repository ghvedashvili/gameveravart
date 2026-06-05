<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GameVeravart</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="manifest" href="/manifest.json">
<meta name="theme-color" content="#0d6efd">
    <!-- Preconnect hints -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <!-- Goldman font (ერთი გამოძახება მთელ საიტზე) -->
    <link href="https://fonts.googleapis.com/css2?family=Goldman&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    @stack('head')
</head>
<body class="@yield('bodyClass')">

<div id="page-loader">
    <div class="spinner"></div>
</div>

<div id="pull-bar" style="position:fixed;top:var(--nav-h,56px);left:0;right:0;background:#f1f3f4;border-bottom:1px solid #ddd;padding:8px 12px;display:flex;align-items:center;gap:8px;z-index:1025;transform:translateY(-100%);transition:transform 0.25s ease;font-family:monospace;">
    <input id="pull-url-input" type="text" style="flex:1;font-size:1rem;border:1px solid #ccc;border-radius:4px;padding:6px 10px;outline:none;font-family:monospace;color:#222;background:#fff;" spellcheck="false" autocomplete="off">
    <button onclick="goPullUrl()" style="padding:6px 14px;background:#1a73e8;color:#fff;border:none;border-radius:4px;font-size:0.82rem;cursor:pointer;white-space:nowrap;">→</button>
</div>

@include('layouts.navigation')

<style>
    /* ── dot-grid სტილები ── */
    body.dot-light {
        background: #f5f5f5;
    }
    body.dot-light::before {
        content: '';
        position: fixed;
        inset: -100%;
        background-image: radial-gradient(rgba(0,0,0,0.13) 1px, transparent 1px);
        background-size: 28px 28px;
        animation: dotGrid 18s linear infinite;
        pointer-events: none;
        z-index: 0;
    }
    body.dot-dark {
        background: #080808;
    }
    body.dot-dark::before {
        content: '';
        position: fixed;
        inset: -100%;
        background-image: radial-gradient(rgba(255,255,255,0.13) 1px, transparent 1px);
        background-size: 28px 28px;
        animation: dotGrid 18s linear infinite;
        pointer-events: none;
        z-index: 0;
    }
    @keyframes dotGrid {
        0%   { transform: translate(0, 0); }
        100% { transform: translate(28px, 28px); }
    }
    /* ───────────────────── */

    body {
        padding-top: 56px; /* navbar-ის სიმაღლე */
    
    }

    #page-loader {
    position: fixed;
    inset: 0;
    background: #080808;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 999999;
    transition: opacity 0.3s ease;
}
#page-loader.fade-out {
    opacity: 0;
    pointer-events: none;
}

    .app-loader {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.65);
    backdrop-filter: blur(4px);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    z-index: 99999;
}

.app-loader.hidden {
    display: none;
}

.spinner {
    width: 52px;
    height: 52px;
    border: 4px solid rgba(255,255,255,0.25);
    border-top-color: #fff;
    border-radius: 50%;
    animation: spin 0.9s linear infinite;
}

.loader-text {
    margin-top: 14px;
    color: #fff;
    font-size: 14px;
    opacity: 0.85;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}


.rules-bar {
    background: #f8f9fa;
    border-bottom: 1px solid #ddd;
    font-size: 15px;
    font-weight: 500;
}

</style>


<div class="container-fluid px-0" style="position:relative;z-index:1;">
    @yield('content')
</div>

<!-- ✅ LOGIN MODAL -->
<div class="modal fade" id="loginModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header">
                <h5 class="modal-title">Login</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form method="POST" >
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary w-100">Login</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ✅ REGISTER MODAL -->
<div class="modal fade" id="registerModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header">
                <h5 class="modal-title">Register</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success w-100">Register</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="app-loader" class="app-loader hidden">
    <div class="spinner"></div>
    <div class="loader-text">Loading…</div>
</div>
<!-- Bootstrap JS (აუცილებელია) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>


(function() {
    const bar   = document.getElementById('pull-bar');
    const input = document.getElementById('pull-url-input');
    if (!bar || !input) return;
    let startY = null, shown = false;

    input.value = window.location.href;
    input.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') goPullUrl();
    });

    document.addEventListener('touchstart', function(e) {
        startY = e.touches[0].clientY;
    }, { passive: true });

    document.addEventListener('touchend', function(e) {
        if (startY === null) return;
        const deltaY = e.changedTouches[0].clientY - startY;
        startY = null;
        if (deltaY > 60 && window.scrollY === 0) {
            if (!shown) { bar.style.transform = 'translateY(0)'; shown = true; }
        } else if (deltaY < -30 && shown) {
            bar.style.transform = 'translateY(-100%)'; shown = false;
        }
    }, { passive: true });
})();

function goPullUrl() {
    const url = document.getElementById('pull-url-input').value.trim();
    if (url) window.location.href = url;
}

window.addEventListener('load', () => {
    const pl = document.getElementById('page-loader');
    if (!pl) return;
    pl.classList.add('fade-out');
    setTimeout(() => pl.remove(), 300);
});

window.AppLoader = {
    show(text = 'Loading…') {
        const loader = document.getElementById('app-loader');
        if (!loader) return;

        loader.querySelector('.loader-text').innerText = text;
        loader.classList.remove('hidden');
    },
    hide() {
        const loader = document.getElementById('app-loader');
        if (!loader) return;

        loader.classList.add('hidden');
    }
};

// ყველა form submit-ზე loader
// document.addEventListener('submit', () => {
//     AppLoader.show();
// });

// ყველა link-ზე სადაც data-loader არის
document.addEventListener('click', e => {
    const link = e.target.closest('a[data-loader]');
    if (link) {
        AppLoader.show(link.dataset.loaderText || 'Loading…');
    }
});
</script>

<script>
if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/sw.js')
        .then(() => console.log('Service Worker registered'))
        .catch(err => console.error('SW registration failed:', err));
}
</script>
@yield('scripts')
</body>
</html>
