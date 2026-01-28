@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-body">

            <h4>Level {{ $level }}</h4>

            {{-- წესები --}}
            @if($question->rules)
                <p>{{ $question->rules }}</p>
            @endif

            {{-- უნიკალური კონტენტი ლეველისთვის --}}
            @yield('level-content')

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
