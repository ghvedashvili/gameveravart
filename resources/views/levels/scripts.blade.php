<script>
document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('answerForm');
    if (!form) return;

    form.addEventListener('submit', function(e){
        e.preventDefault();

        fetch("{{ route('levels.check', $level) }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                answer: document.getElementById('answer').value
            })
        })
        .then(r => r.json())
        .then(data => {
    if(data.status === 'correct') {
        Swal.fire({
            icon: 'success',
            title: 'Correct! 🎉',
            text: 'Moving to next level...',
            timer: 1200,
            showConfirmButton: false
        }).then(() => {
            window.location.href = "/levels/" + data.nextLevel;
        });
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Wrong answer 😕',
            text: 'Think again and try once more.',
            confirmButtonText: 'Retry'
        });
    }
});

    });
});
</script>
