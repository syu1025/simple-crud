<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserInfo;
use Illuminate\Support\Facades\Log;


class UserInfoController extends Controller
{
    public function edit()
    {
        return view("user_info.info_edit");
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'weight' => 'required|integer|min:30|max:100',
            'height' => 'nullable|integer|min:100|max:200',
            'age' => 'nullable|integer|min:15|max:100',
            'gender' => 'nullable|string|in:男性,女性,その他',
            'bmr_round' => 'nullable|integer|min:1|max:10000',
        ]);

        $user = Auth::user();
        $user->userInfo()->updateOrCreate(
            [
                'user_id' => $user->id,
            ],
            [
                'weight' => $validatedData['weight'],
                'height' => $validatedData['height'],
                'age' => $validatedData['age'],
                'gender' => $validatedData['gender'],
                'bmr_round' => $validatedData['bmr_round'],
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'プロフィールを更新しました',
            'user_info' => $user->userInfo
        ]);
    }
}


