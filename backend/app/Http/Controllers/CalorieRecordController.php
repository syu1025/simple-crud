<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CalorieRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
use App\Http\Controllers\BaseController;
use App\Models\CalorieTarget;

class CalorieRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $year = request('year');
        $month = request('month');

        $sum_up_calories = $user->calorieRecords()
            ->when($year && $month, function ($query) use ($year, $month) {
                $query->whereYear('date', $year)
                    ->whereMonth('date', $month);
            })
            ->selectRaw('date, SUM(calorie_intake) as total_intake, SUM(calorie_burned) as total_burned')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();

        $target = $user->calorieTarget()
                        ->orderBy('created_at', 'desc')
                        ->first();
        return view('calorie_record.record_index', ["sum_up_calories" => $sum_up_calories, "target" => $target, ]);
    }

    /**
     * Show the form for creating a new resource.
     */

    public function create()
    {
        return view('calorie_record.record_create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'calorie_intake' => 'required|integer',
            'calorie_burned' => 'required|integer',
            'note' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('records.create')
                ->withErrors($validator)
                ->withInput();
        }

        $record = new CalorieRecord();
        $record->date = $request->date;
        $record->calorie_intake = $request->calorie_intake;
        $record->calorie_burned = $request->calorie_burned;
        $record->note = $request->note;
        $record->user_id = Auth::id();
        $record->save();


    session()->flash('message', '記録を追加しました');
    return redirect()->route('records.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($date)
    {
        $user = auth()->user();
        $each_records = $user->calorieRecords()->where('date', $date)
        ->select('*',
                \DB::raw('YEAR(date) as year'),
                \DB::raw('MONTH(date) as month'))
        ->get();
        return view('calorie_record.record_show', compact('each_records', 'date'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)//パラメーター系ならどこでも
    {
        $original_record = CalorieRecord::find($id);

        return view('calorie_record.record_edit', compact('original_record'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'date' => 'required|date',
            'calorie_intake' => 'required|integer',
            'calorie_burned' => 'required|integer',
            'note' => 'nullable|string|max:255',
        ]);

        $record = CalorieRecord::findOrFail($id);
        $record->fill($validatedData)->save();

        session()->flash('message', '記録を更新しました');
        return redirect()->route('records.show', ['date' => $record->date]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $record = CalorieRecord::findOrFail($id);
        $record->delete();

        session()->flash('message', '記録を削除しました');
        return redirect()->back();
    }

}
