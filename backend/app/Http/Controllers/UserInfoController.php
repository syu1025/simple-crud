<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserInfo;

class UserInfoController extends Controller
{
    public function edit()
    {
        return view("user_info.info_edit");
    }

    public function update(Request $request)
{
    $validated = $request->validate([
        'weight' => 'nullable|integer|min:1|max:300',
        'height' => 'nullable|integer|min:50|max:250',
        'age' => 'nullable|integer|min:1|max:120',
        'gender' => 'nullable|string|in:男性,女性,その他',
    ]);

    $user = Auth::user();
    $userInfo = $user->userInfo;

    if (!$userInfo) {
        // ユーザー情報がまだ存在しない場合は新規作成
        $userInfo = new UserInfo();
        $userInfo->user_id = $user->id;
    }

    // データ更新
    $userInfo->weight = $request->weight;
    $userInfo->height = $request->height;
    $userInfo->age = $request->age;
    $userInfo->gender = $request->gender;
    $userInfo->save();

    // AJAXリクエストの場合はJSONレスポンスを返す
    if ($request->ajax() || $request->wantsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'プロフィールを更新しました',
            'user_info' => $userInfo
        ]);
    }

    // 通常のリクエストの場合はリダイレクト
    return redirect()->route('records.index')->with('success', 'プロフィールを更新しました');
}
}
