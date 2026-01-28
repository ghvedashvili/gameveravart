

@extends('levels.layout')

@section('level-content')
    @includeIf('levels.level' . $level)
@endsection
