<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use Illuminate\Support\Facades\Auth;

class LevelController extends Controller
{
    // Load level page
    public function show($level = null)
    {
        $user = Auth::user();

        // Current user level
        $currentLevel = $user->level ?? 0;

        // თუ level parameter არ არის მითითებული, currentLevel
        $levelToShow = $level ?? $currentLevel;

        // Load all levels up to current
        $questions = Question::where('level', '<=', $levelToShow)
            ->orderBy('level')
            ->get();

        return view('levels.show', compact('questions', 'currentLevel'));
    }

    // Check answer
    public function check(Request $request, $level)
    {
        $user = Auth::user();
        $question = Question::where('level', $level)->firstOrFail();

        $answer = trim(strtolower($request->answer));
        $correct = trim(strtolower($question->answer));

        if ($answer === $correct && $user->level == $level) {
            $user->increment('level'); // Level +1
            $user->save();

            return response()->json(['status' => 'correct', 'nextLevel' => $user->level]);
        }

        return response()->json(['status' => 'wrong']);
    }

    
}
