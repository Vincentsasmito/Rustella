<?php

namespace App\Http\Controllers;

//Imports
use App\Models\Suggestion;
use Illuminate\Http\Request;

class SuggestionController extends Controller
{
    public function index()
    {
        $suggestions = Suggestion::all();
        return view('suggestions.index', compact('suggestions'));
    }

    public function create()
    {
       return view('suggestions.create');
    }

    public function store(Request $request)
    {
        $validInput = $request->validate([
            'message' => 'required|string',
        ]);

        Suggestion::create($validInput);
        return redirect()->route('suggestions.index')->with('success', 'Suggestion uypdated successfully.');
    }

    public function destroy(Suggestion $suggestion)
    {
        $suggestion->delete();
        return redirect()->route('suggestions.index')->with('success', 'Suggestion deleted successfully.');
    }
}
