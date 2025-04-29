@extends('layouts.app')

@section('content')
    <h1>Create Suggestion</h1>
    <form action="{{ route('suggestions.store') }}" method="POST">
        @csrf
        <textarea name="message" required></textarea>
        <button type="submit">Submit</button>
    </form>
@endsection