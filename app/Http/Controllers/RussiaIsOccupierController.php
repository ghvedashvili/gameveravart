<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Question;

class RussiaIsOccupierController extends Controller
{
    // 👉 ყველა გზა აქ შემოდის
    public function entry()
    {
        $user = Auth::user();

        if ($user->level < 3) {
            abort(403, 'This level is locked');
        }

        // 🔥 ყოველთვის ახალი, დიდი URL
        $random = Str::random(40)
            . 'RUSSIAISNOTOCCUPIER'
            . Str::random(40);

        return redirect()->route('level3', [
            'code' => $random,
            
        ]);
    }

    // 👉 აქ ხდება puzzle
    public function index($code)
    {
        $user = Auth::user();

        if ($user->level < 3) {
            abort(403, 'This level is locked');
        }

        // ✔ სწორი სიტყვა URL-ში
        $completed = Str::contains($code, 'RUSSIAISOCCUPIER');

        // if ($completed && $user->level == 3) {
        //     $user->update(['level' => 4]);
        // }
$question = Question::where('level', 3)->firstOrFail();
        return view('levels.level3', [
            'question' => $question,
            'completed' => $completed,
            'userLevel' => $user->level,  // ✅ ასე Blade-ს აღარ ექნება შეცდომა
            'level' => 3  
        ]);
    }

    public function complete()
{
  //dd(Auth::id(), request()->all());
    $user = Auth::user();

    if ($user->level == 3) {
        $user->update(['level' => 4]);
    }

    return response()->json(['status' => 'ok']);
}

}
