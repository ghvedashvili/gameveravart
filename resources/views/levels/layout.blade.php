@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-body">

            <h4>Level {{ $level }}</h4>

            @if($userLevel == $level)
             {{-- წესები --}}
                @if($question->rules)
                    <p>{{ $question->rules }}</p>
                @endif
            @endif
           
           

            {{-- უნიკალური კონტენტი ლეველისთვის --}}
           @if($level == 1)
                @yield('level-content')
            @endif

            {{-- დასრულებული ლეველი --}}
            @if($userLevel > $level)
                <div class="alert alert-info mt-3">
                    ✅ Completed
                </div>
            @endif

        </div>
    </div>
</div>
@endsection
@include('levels.scripts')
