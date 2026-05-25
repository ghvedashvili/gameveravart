@if($userLevel == $level)

<style>
    /*! normalize + Laravel error page utilities — identical to vendor minimal.blade.php */
    html{line-height:1.15;-webkit-text-size-adjust:100%}body{margin:0}html{font-family:system-ui,-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica Neue,Arial,Noto Sans,sans-serif;line-height:1.5}*,:after,:before{box-sizing:border-box;border:0 solid #e2e8f0}.bg-gray-100{background-color:#f7fafc}.border-gray-400{border-color:#cbd5e0}.border-r{border-right-width:1px}.flex{display:flex}.items-center{align-items:center}.items-top{align-items:flex-start}.justify-center{justify-content:center}.text-sm{font-size:.875rem}.text-lg{font-size:1.125rem}.mx-auto{margin-left:auto;margin-right:auto}.mt-2{margin-top:.5rem}.mt-4{margin-top:1rem}.ml-4{margin-left:1rem}.max-w-xl{max-width:36rem}.min-h-screen{min-height:100vh}.px-4{padding-left:1rem;padding-right:1rem}.pt-8{padding-top:2rem}.relative{position:relative}.text-center{text-align:center}.text-gray-400{color:#cbd5e0}.text-gray-500{color:#a0aec0}.text-xs{font-size:.75rem}.uppercase{text-transform:uppercase}.antialiased{-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}.tracking-wider{letter-spacing:.05em}@media(min-width:640px){.sm\:items-center{align-items:center}.sm\:justify-start{justify-content:flex-start}.sm\:pt-0{padding-top:0}.sm\:px-6{padding-left:1.5rem;padding-right:1.5rem}}@media(min-width:1024px){.lg\:px-8{padding-left:2rem;padding-right:2rem}}

    nav.navbar { display: none !important; }

    html, body {
        background-color: #f7fafc !important;
        font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        margin: 0;
        padding: 0 !important;
        height: 100vh;
    }

    body.dot-light::before { display: none; }
</style>

<div class="relative flex items-center justify-center min-h-screen bg-gray-100">
    <div class="max-w-xl mx-auto px-6">
        <div class="flex items-center justify-center" style="flex-wrap:nowrap;">

            <div class="px-4 text-lg text-gray-500 border-r border-gray-400 tracking-wider" style="white-space:nowrap;">
                ERROR 004
            </div>

            <div class="ml-4">
                <div class="text-gray-500 uppercase tracking-wider" style="font-size:clamp(12px,2.5vw,1.125rem);">
                    Type: user not recognized
                </div>
                <div class="text-sm text-gray-500 tracking-wider mt-2">
                    Severity: not fol <span class="text-xs" style="opacity:.5;font-weight:400;">(low error)</span>
                </div>
            </div>

        </div>
        <div class="mt-4 text-sm text-gray-500 tracking-wider text-center">
            iniciator: @veravart_game
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
