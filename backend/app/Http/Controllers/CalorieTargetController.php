<?php

namespace App\Http\Controllers;

use App\Models\CalorieTarget;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class CalorieTargetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'target_burned_calories_day' => 'required|integer|min:0',
        ]);

        $user = Auth::user();
        $user->calorieTarget()->create(
            ['target_burned_calories_day' => $data['target_burned_calories_day']]
        );

        //成功レスポンスを返す
        return response()->json([
            'success' => true,
            "target_burned_calories_day" => $data['target_burned_calories_day'],
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
