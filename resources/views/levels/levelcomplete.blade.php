<div class="container mt-5 pt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">level{{ $level }}</h5>
                    @if($userLevel > $level)
                        <div class="alert alert-success">  <p>{{ $question->rules }}</p>თქვენ გაიარეთ ეს ტური წარმატებით</div>
                    @else
                        <div class="alert alert-warning">⚠️ ეს დონე ჯერ არ არის ხელმისაწვდომი</div>
                    @endif
                    <a href="{{ route('levels.show', ['level' => $userLevel]) }}" class="btn btn-primary swal-loader">გადადით მიმდინარე დონეზე</a>
                </div>
            </div>
        </div>
    </div>
</div>