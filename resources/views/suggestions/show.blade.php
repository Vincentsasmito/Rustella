@extends('layouts.app')

@section('content')
    <h1>Suggestion Details</h1>
    <p>{{ $suggestion->message }}</p>
    <a href="{{ route('suggestions.index') }}">Back to List</a>
@endsection