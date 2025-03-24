<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>カロリー記録 - 編集</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <style>
        .form-card {
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .form-card:hover {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .form-input:focus {
            border-color: #4F46E5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
        }

        .submit-button {
            background: linear-gradient(135deg, #4F46E5, #7C3AED);
            transition: all 0.3s ease;
        }

        .submit-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        }

        .submit-button:active {
            transform: translateY(0);
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">カロリー記録 - 編集</h1>
        <div class="bg-white rounded-lg shadow-lg overflow-hidden form-card">
            <div class="p-4 bg-gradient-to-r from-blue-500 to-indigo-600">
                <h2 class="text-xl font-semibold text-white">記録を編集</h2>
            </div>

            <div class="p-6">
                <form action="{{ route('records.update', ['id'=> $original_record->id]) }}" method="post">
                    @csrf
                    @method('put')
                    <!-- 日付選択フィールド -->
                    <div class="mb-6">
                        <label for="date" class="block text-sm font-medium text-gray-700 mb-1">日付</label>
                        <div class="relative">
                            <input
                                type="date"
                                name="date"
                                id="date"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none form-input"
                                value="{{ $original_record->date }}"
                                required
                            >
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        </div>
                        @error('date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-6">
                        <!-- 摂取カロリー -->
                        <div>
                            <label for="calorie_intake" class="block text-sm font-medium text-gray-700 mb-1">摂取カロリー (kcal)</label>
                            <div class="relative">
                                <input
                                    type="number"
                                    name="calorie_intake"
                                    id="calorie_intake"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none form-input"
                                    value="{{ $original_record->calorie_intake }}"
                                    required
                                    min="0"
                                >
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500">kcal</span>
                                </div>
                            </div>
                            @error('calorie_intake')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- 消費カロリー -->
                        <div>
                            <label for="calorie_burned" class="block text-sm font-medium text-gray-700 mb-1">消費カロリー (kcal)</label>
                            <div class="relative">
                                <input
                                    type="number"
                                    name="calorie_burned"
                                    id="calorie_burned"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none form-input"
                                    value="{{ $original_record->calorie_burned }}"
                                    required
                                    min="0"
                                >
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500">kcal</span>
                                </div>
                            </div>
                            @error('calorie_burned')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- メモ -->
                        <div>
                            <label for="note" class="block text-sm font-medium text-gray-700 mb-1">メモ（任意）</label>
                            <textarea
                                name="note"
                                id="note"
                                rows="3"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none form-input"
                                value="{{ $original_record->note }}"
                            ></textarea>
                            @error('note')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- ボタン -->
                        <div class="flex items-center justify-between pt-4">
                            <a
                                href="{{ route('records.show', ['date' => $original_record->date]) }}"
                                class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50 transition"
                            >
                                キャンセル
                            </a>
                            <button
                                type="submit"
                                class="px-6 py-2 text-white font-medium rounded-md submit-button"
                            >
                                更新する
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
