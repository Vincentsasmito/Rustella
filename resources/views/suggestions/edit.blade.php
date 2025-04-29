@extends('layouts.app')

@section('content')
    <h1>Edit Suggestion</h1>
    <form action="{{ route('suggestions.update', $suggestion->id) }}" method="POST">
        @csrf
        @method('PUT')
        <textarea name="message" required>{{ $suggestion->Message }}</textarea>
        <button type="submit">Update</button>
    </form>
@endsection