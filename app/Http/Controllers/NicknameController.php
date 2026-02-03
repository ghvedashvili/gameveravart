<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NicknameController extends Controller
{
    private function getRules(string $nickname): array
    {
        return [
            ['id'=>1,'text'=>'Nickname უნდა შეიცავდეს მინიმუმ 5 სიმბოლოს','passed'=>strlen($nickname)>=5],
            ['id'=>2,'text'=>'Nickname უნდა შეიცავდეს ციფრს','passed'=>preg_match('/\d/',$nickname)],
            ['id'=>3,'text'=>'Nickname უნდა შეიცავდეს დიდ ასოს','passed'=>preg_match('/[A-Z]/',$nickname)],
            ['id'=>4,'text'=>'Nickname უნდა შეიცავდეს სპეციალურ სიმბოლოს','passed'=>preg_match('/[!@#$%^&*()_\-+=\[\]{};:"\\|,.<>\/?]/',$nickname)],
            ['id'=>5,'text'=>'Nickname-ში ციფრების ჯამი უნდა იყოს 15','passed'=>function() use($nickname){preg_match_all('/\d/',$nickname,$nums);return array_sum($nums[0])===15;}],
            ['id'=>6,'text'=>'RU აკრძალულია','passed'=>!str_contains(strtoupper($nickname),'RU')],
            ['id'=>999,'text'=>'აკრიფე ეს ქაფთჩა: "G7X9Z"','passed'=>str_contains($nickname,'G7X9Z')]
        ];
    }

    private function normalizeRules(array $rules): array
    {
        return array_map(fn($r)=>[
            'id'=>$r['id'],
            'text'=>$r['text'],
            'passed'=>is_callable($r['passed'])?(bool)$r['passed'](): (bool)$r['passed']
        ], $rules);
    }

    public function live(Request $request, $level)
    {
        if (!$request->expectsJson()) return response()->json(['locked'=>true],400);

        $user=Auth::user();
        if(!$user || $user->level!=$level) return response()->json(['locked'=>true]);

        return response()->json([
            'locked'=>false,
            'rules'=>$this->normalizeRules($this->getRules($request->nickname??''))
        ]);
    }

    public function submit(Request $request,$level)
    {
        if(!$request->expectsJson()) return response()->json(['status'=>'error'],400);

        $user=Auth::user();
        if(!$user || $user->level!=$level) return response()->json(['status'=>'locked']);

        $nickname=$request->nickname??'';
        $rules=$this->normalizeRules($this->getRules($nickname));

        if(!collect($rules)->every(fn($r)=>$r['passed'])){
            return response()->json(['status'=>'error','rules'=>$rules]);
        }

        try{
            $user->nickname=$nickname;
            $user->level+=1;
            $user->save();
        }catch(\Throwable $e){
            return response()->json(['status'=>'error'],500);
        }

        return response()->json(['status'=>'success','nickname'=>$nickname,'newLevel'=>$user->level]);
    }
}
