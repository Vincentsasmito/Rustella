@extends('layouts.app')

@section('content')
    <h1>Suggestions</h1>
    <a href="{{ route('suggestions.create') }}">Create New Suggestion</a>
    <ul>
        @foreach($suggestions as $suggestion)
            <li>
                {{ $suggestion->message }}
                <a href="{{ route('suggestions.edit', $suggestion->id) }}">Edit</a>
                <form action="{{ route('suggestions.destroy', $suggestion->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Delete</button>
                </form>
            </li>
        @endforeach
    </ul>
@endsection