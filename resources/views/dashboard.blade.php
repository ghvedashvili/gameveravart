<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    You're logged in!
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
@php
    use App\Models\Question;

    $userLevel = auth()->user()->level ?? 0;

    // ამ ლეველზე არსებული კითხვა
    $currentQuestion = Question::where('level', $userLevel)->first();
@endphp

@if($currentQuestion)
    <div class="card my-3">
        <div class="card-body">
            <h5>Level {{ $userLevel }}</h5>

            @if($currentQuestion->rules)
                <p><strong>Rules:</strong> {{ $currentQuestion->rules }}</p>
            @endif

            @if($currentQuestion->type === 'question')
                <form id="questionForm">
                    <input type="text" id="userAnswer" class="form-control mb-2" placeholder="Type your answer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            @elseif($currentQuestion->type === 'action')
                <button id="actionButton" class="btn btn-success">Do Action</button>
            @endif
        </div>
    </div>
@endif
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const currentLevel = {{ $userLevel ?? 0 }};
    const correctAnswer = @json($currentQuestion->answer ?? '');

    // Question ტიპის პასუხის შემოწმება
    const questionForm = document.getElementById('questionForm');
    if(questionForm) {
        questionForm.addEventListener('submit', function(e){
            e.preventDefault();
            const userAnswer = document.getElementById('userAnswer').value.trim();

            if(userAnswer.toLowerCase() === correctAnswer.toLowerCase()){
                Swal.fire('Correct!', 'You answered correctly!', 'success').then(() => {
                    window.location.href = '{{ route("level.complete", ["level" => $userLevel]) }}';
                });
            } else {
                Swal.fire('Wrong!', 'Try again!', 'error');
            }
        });
    }

    // Action ტიპის ღილაკი
    const actionButton = document.getElementById('actionButton');
    if(actionButton) {
        actionButton.addEventListener('click', function(){
            Swal.fire('Action done!', 'You completed this level.', 'success').then(() => {
                window.location.href = '{{ route("level.complete", ["level" => $userLevel]) }}';
            });
        });
    }
});
</script>
