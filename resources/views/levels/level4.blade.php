@if($userLevel == $level)

<style>
    nav.navbar { display: none !important; }

    body {
        background-color: #080808 !important;
        color: #636b6f;
        font-family: sans-serif;
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

    .error-wrap {
        display: flex;
        align-items: center;
        gap: 0;
    }

    .code {
        border-right: 2px solid #2a2a2a;
        font-size: clamp(14px, 4vw, 26px);
        padding: 0 clamp(10px, 3vw, 15px);
        text-align: center;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .message {
        font-size: clamp(13px, 3.5vw, 18px);
        padding: clamp(6px, 2vw, 10px);
        white-space: nowrap;
    }

    @media (max-width: 400px) {
        .error-wrap {
            flex-direction: column;
            align-items: center;
            gap: 16px;
        }
        .code {
            border-right: none;
            border-bottom: 2px solid #2a2a2a;
            padding: 0 0 12px 0;
            width: 100%;
            text-align: center;
        }
        .message {
            padding: 0;
            text-align: center;
        }
    }
</style>

<div class="full-height">
    <div class="error-wrap">
        <div class="code">NOTFOLLOWERROR</div>
        <div class="message">iniciator: @veravart_game</div>
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
