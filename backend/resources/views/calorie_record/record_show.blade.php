@extends('layouts.sidebar')

@section("content")
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>カロリー記録</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <style>
        .container {
                max-width: none;}
        }
        .calorie-card {
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .calorie-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .positive-diff {
            color: #EF4444;
        }

        .negative-diff {
            color: #10B981;
        }
    </style>
</head>
<body class="bg-gray-50">
@if(session('message'))
    <div x-data="{ show: true }"
        x-show="show"
        x-init="setTimeout(() => show = false, 3000)"
        class="fixed top-4 right-4 bg-green-50 border border-green-400 text-green-800 px-4 py-3 rounded shadow-lg transition-opacity duration-300">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <span>{{ session('message') }}</span>
        </div>
    </div>
@endif
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">{{ date('Y/m/d', strtotime($date)) }} のカロリー記録</h1>
        @if($each_records->isEmpty())
            <div class="bg-white rounded-lg shadow p-6 text-center text-gray-600">
                <p>この日付にはカロリー記録がありません。</p>
            </div>
        @else
            <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6">
                <div class="p-4 bg-gradient-to-r from-blue-500 to-indigo-600">
                    <div class="grid grid-cols-4 gap-4 text-white font-medium">
                        <div>摂取カロリー</div>
                        <div>消費カロリー</div>
                        <div>メモ</div>
                    </div>
                </div>

                <div class="divide-y divide-gray-200">
                    @foreach ($each_records as $record)
                        <a href="{{ route('records.edit', ['id' => $record->id]) }}">
                            <div class="grid grid-cols-4 gap-4 p-4 hover:bg-gray-50 calorie-card">
                                <div class="text-orange-500 font-medium">
                                    {{ number_format($record->calorie_intake) }} kcal
                                </div>
                                <div class="font-medium">
                                    {{ number_format($record->calorie_burned) }} kcal
                                </div>
                                <div class="text-gray-700">
                                    {{ $record->note }}
                                </div>
                                <form action="{{ route('records.destroy', ['id' => $record->id]) }}" method="POST" class="ml-2" style="text-align: right;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700" onclick="return confirm('削除しますか？')">
                                        <!-- ごみ箱アイコン（Heroiconsの例） -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4m-4 0a2 2 0 00-2 2v0a2 2 0 002 2h4a2 2 0 002-2v0a2 2 0 00-2-2m-4 0h4" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </a>

                    @endforeach
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex justify-between items-center">
                    <div class="text-lg font-medium text-gray-700">合計</div>
                    <div class="grid grid-cols-3 gap-8">
                        <div>
                            <span class="block text-sm text-gray-500">摂取カロリー</span>
                            <span class="text-orange-500 font-medium">{{ number_format($each_records->sum('calorie_intake')) }} kcal</span>
                        </div>
                        <div>
                            <span class="block text-sm text-gray-500">消費カロリー</span>
                            <span class="font-medium">{{ number_format($each_records->sum('calorie_burned')) }} kcal</span>
                        </div>
                        <div>
                            <span class="block text-sm text-gray-500">差分</span>
                            <span class="{{ $each_records->sum('calorie_intake') - $each_records->sum('calorie_burned') > 0 ? 'positive-diff' : 'negative-diff' }} font-medium">
                                {{ number_format($record->sum('calorie_intake') - $record->sum('calorie_burned')) }} kcal
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="mt-6 text-center">
            <a href="{{ isset($each_records[0]) ? route('records.index', ['year' => $each_records[0]->year, 'month' => $each_records[0]->month]) : route('records.index') }}" class="inline-block px-4 py-2 bg-gray-200 rounded-md text-gray-700 hover:bg-gray-300 transition">
                戻る
            </a>

        </div>
    </div>
</body>
</html>
@endsection
