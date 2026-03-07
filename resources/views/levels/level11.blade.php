@if($userLevel == $level)

    @if($question->type == 'action')

    <form id="answerForm">
        @csrf
        <button class="btn btn-success w-100">
            Continue →
        </button>
    </form>

    @else

    <form id="answerForm">
        @csrf
        <input type="text" class="form-control mb-2" id="answer">
        <button class="btn btn-primary">Submit</button>
    </form>

    @endif
@else

    @include('levels.levelcomplete', ['level' => $level,  'userLevel' => auth()->user()->level])

@endif
