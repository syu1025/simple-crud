@extends('layouts.sidebar')  <!-- 親ビューを継承 -->

@section("content")
    <!DOCTYPE html>
    <html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>カロリー記録</title>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
        <style>
            @media (min-width: 640px) {
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

        <!-- ヘッダー（ログアウトボタン付き） -->
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 py-4 flex justify-end">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded transition duration-150 ease-in-out">
                        ログアウト
                    </button>
                </form>
            </div>
        </header>

        <div class="container-fluid px-2 py-4">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden w-full">
                <div class="p-4 bg-gradient-to-r from-blue-500 to-indigo-600">
                    <div class="grid grid-cols-4 gap-4 text-white font-medium">
                        <div>日付</div>
                        <div>摂取カロリー</div>
                        <div>消費カロリー</div>
                        <div>差分</div>
                    </div>
                </div>

                <div class="divide-y divide-gray-200">
                    @foreach ($sum_up_calories as $record)
                        <a href="{{ route('records.show', ['date' => $record->date]) }}" class="block">
                            <div class="grid grid-cols-4 gap-4 p-4 hover:bg-gray-50 calorie-card">
                                <div class="font-semibold text-gray-700">
                                    {{ date('Y/m/d', strtotime($record->date)) }}
                                </div>
                                <div class="text-orange-500 font-medium">
                                    {{ number_format($record->total_intake) }} kcal
                                </div>
                                <div class="font-medium">
                                    {{ number_format($record->total_burned) }} kcal
                                </div>
                                <div class="{{ $record->total_intake - $record->total_burned > 0 ? 'text-red-500' : 'text-green-500' }} font-medium">
                                    {{ number_format($record->total_intake - $record->total_burned) }} kcal
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
        <a href="{{ route('records.create')}}" class="fixed bottom-6 right-6 w-14 h-14 flex items-center justify-center rounded-full bg-purple-600 text-white text-2xl shadow-lg hover:bg-purple-700 transition-colors">
            ＋
        </a>
    </body>
    </html>
@endsection
