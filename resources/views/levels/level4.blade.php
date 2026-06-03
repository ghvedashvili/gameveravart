@if($userLevel == $level)

<style>
    nav.navbar { display: none !important; }

    body {
        background-color: #080808 !important;
        color: #636b6f;
        font-family: 'Goldman', monospace;
        margin: 0;
        padding: 0 !important;
        height: 100dvh;
        height: 100vh;
    }

    body.dot-light::before { display: none; }

    .full-height {
        height: 100dvh;
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 24px;
        box-sizing: border-box;
    }

    .error-block {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 28px;
        text-align: center;
    }

    .access-denied {
        font-size: clamp(1.6rem, 8vw, 3.2rem);
        color: #c0392b;
        letter-spacing: 0.15em;
        text-transform: uppercase;
        animation: blink 2.4s step-end infinite;
    }

    @keyframes blink {
        0%, 100% { opacity: 1; }
        50%       { opacity: 0.35; }
    }

    .error-divider {
        width: clamp(120px, 40vw, 280px);
        height: 1px;
        background: #2a2a2a;
    }

    .error-wrap {
        display: flex;
        align-items: center;
        gap: 0;
    }

    .code {
        border-right: 2px solid #2a2a2a;
        font-size: clamp(11px, 3vw, 18px);
        padding: 0 clamp(10px, 3vw, 18px);
        text-align: center;
        white-space: nowrap;
        flex-shrink: 0;
        letter-spacing: 0.06em;
    }

    .message {
        font-size: clamp(11px, 3vw, 16px);
        padding: clamp(6px, 2vw, 10px);
        white-space: nowrap;
        letter-spacing: 0.04em;
    }

    @media (max-width: 400px) {
        .error-wrap {
            flex-direction: column;
            align-items: center;
            gap: 14px;
        }
        .code {
            border-right: none;
            border-bottom: 1px solid #2a2a2a;
            padding: 0 0 10px 0;
        }
        .message { padding: 0; }
    }
</style>

<div class="full-height">
    <div class="error-block">
        <div class="access-denied">ACCESS DENIED</div>
        <div class="error-divider"></div>
        <div class="error-wrap">
            <div class="code">NOT FOL LOW ERROR</div>
            <div class="message">iniciator: @veravart_game</div>
        </div>
    </div>
</div>

@if($completed)
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
Swal.fire({
    icon: 'success',
    title: 'Correct',
    text: 'Truth matters.',
    confirmButtonText: 'Next Level'
}).then(() => {
    window.location.href = '/levels/{{ $userLevel }}';
});
</script>
@endif

@else

@include('levels.levelcomplete', ['level' => $level, 'userLevel' => auth()->user()->level])

@endif
