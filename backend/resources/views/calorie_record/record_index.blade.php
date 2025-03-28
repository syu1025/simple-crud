<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>カロリー記録</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <style>
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

        .add-button {
            position: fixed;
            right: 30px;
            bottom: 30px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #4F46E5, #7C3AED);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 100;
        }

        .add-button:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        }

        .add-button:active {
            transform: scale(0.95);
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
        <h2 class="text-2xl font-bold text-gray-800 mb-6">カロリー記録</h2>

        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="p-4 bg-gradient-to-r from-blue-500 to-indigo-600">
                <div class="grid grid-cols-5 gap-4 text-white font-medium">
                    <div>日付</div>
                    <div>摂取カロリー</div>
                    <div>消費カロリー</div>
                    <div>差分</div>
                </div>
            </div>

            <div class="divide-y divide-gray-200">
                @foreach ($sum_up_calories as $record)
                    <a href="{{ route('records.show', ['date' => $record->date]) }}">
                        <div class="grid grid-cols-5 gap-4 p-4 hover:bg-gray-50 calorie-card">
                            <div class="font-semibold text-gray-700">
                                {{ $record->date }}
                            </div>
                            <div class="text-orange-500
                                    font-medium">
                                {{ number_format($record->total_intake) }} kcal
                            </div>
                            <div class="font-medium">{{ number_format($record->total_burned) }} kcal</div>
                            <div
                                class="{{ $record->total_intake - $record->total_burned > 0 ? 'positive-diff' : 'negative-diff' }} font-medium">
                                {{ number_format($record->total_intake - $record->total_burned) }} kcal
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
     <a href="{{ route('records.create')}}" class="add-button">
        ＋
    </a>
</body>

</html>
