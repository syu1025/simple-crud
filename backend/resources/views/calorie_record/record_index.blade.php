@extends('layouts.sidebar')

@section('content')
    <!DOCTYPE html>
    <html lang="ja">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>カロリー記録</title>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
        <style>
            @media (min-width: 640px) {
                .container {
                    max-width: none;
                }
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

            .chart-container {
                position: relative;
                height: 300px;
                width: 100%;
                margin-bottom: 20px;
            }

            #modal::backdrop {
                background-color: rgba(0, 0, 0, 0.5);
            }
        </style>
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>

    <body class="bg-gray-50">
        @if (session('message'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
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
            <div class="max-w-7xl mx-auto px-4 py-4 flex items-center">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800" onclick="document.getElementById('userModal').showModal()">
                        {{ $user->name }}さんの基礎代謝:<span class="font-medium text-green-800 mb-1">{{ $user_info->bmr_round }}</span> kcal/日
                    </h3>
                </div>
                <dialog id="userModal" class="rounded-lg shadow-xl p-6 max-w-md w-full bg-gradient-to-br from-white to-gray-50 border border-gray-100">
                    <div class="space-y-4">
                        <!-- ヘッダー部分 -->
                        <div class="flex items-center justify-between border-b pb-3">
                            <h3 class="text-xl font-bold text-gray-800">{{ $user->name }}さんのプロフィール</h3>
                            <button onclick="document.getElementById('userModal').close()" class="text-gray-500 hover:text-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>

                        <!-- ユーザー情報 -->
                        <div class="bg-blue-50 rounded-lg p-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="flex items-center space-x-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 005 10a6 6 0 0012 0c0-.552-.08-1.087-.234-1.584A5.01 5.01 0 0010 11z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-gray-600">性別</span>
                                </div>
                                <div class="font-medium text-gray-800">{{ $user_info?->gender ?? '未設定' }}</div>

                                <div class="flex items-center space-x-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M6 3a1 1 0 011-1h6a1 1 0 011 1v2a1 1 0 01-1 1H7a1 1 0 01-1-1V3zm1 2h6V4H7v1zm6 4a1 1 0 01-1 1H8a1 1 0 01-1-1V8a1 1 0 011-1h4a1 1 0 011 1v1zM8 8h4v1H8V8zm1 4a1 1 0 100 2h2a1 1 0 100-2H9z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-gray-600">年齢</span>
                                </div>
                                <div class="font-medium text-gray-800">{{ $user_info->age ?? '未設定' }}歳</div>

                                <div class="flex items-center space-x-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 1.18.898 2.043 2.17 2.507a3.5 3.5 0 001.537.33c.425 0 .839-.055 1.228-.157.653-.173 1.058-.44 1.058-.992 0-.512-.317-.878-.952-.878a1 1 0 100 2c.4 0 .756-.19.981-.51.196-.278.335-.648.335-1.112 0-.893-.598-1.668-1.32-2.147A4.535 4.535 0 0011 5.092V5zm-4 2.405a.75.75 0 01.75-.75h3.5a.75.75 0 010 1.5h-3.5a.75.75 0 01-.75-.75z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-gray-600">身長</span>
                                </div>
                                <div class="font-medium text-gray-800">{{ $user_info->height ?? '未設定' }}cm</div>

                                <div class="flex items-center space-x-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-gray-600">体重</span>
                                </div>
                                <div class="font-medium text-gray-800">{{ $user_info->weight ?? '未設定' }}kg</div>
                            </div>
                        </div>

                        <!-- 基礎代謝情報 -->
                        @if(isset($user_info) && $user_info->height && $user_info->weight && $user_info->age && $user_info->gender)
                        <div class="bg-green-50 rounded-lg p-4">
                            <div class="font-medium text-green-800 mb-1">基礎代謝（概算）</div>
                            <div class="text-xl font-bold text-green-700">
                                <!-- BMR計算結果を表示 -->
                                {{ $user_info->bmr_round }} kcal/日
                            </div>
                            <div class="text-sm text-green-600 mt-1">※ハリス・ベネディクト方程式による概算値</div>
                        </div>
                        @else
                        <div class="bg-yellow-50 rounded-lg p-4">
                            <div class="font-medium text-yellow-800">基礎代謝を計算するには</div>
                            <div class="text-sm text-yellow-700 mt-1">身長・体重・年齢・性別の情報が必要です。プロフィールを更新してください。</div>
                        </div>
                        @endif

                        <!-- 編集ボタン -->
                        <div class="pt-3 flex justify-end">
                            <a  href="{{ route('user_profile.edit') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                </svg>
                                プロフィール編集
                            </a>
                        </div>
                    </div>
                </dialog>
                <div onclick="document.getElementById('targetModal').showModal()" style="margin: auto" id="currentTarget" class="text-lg font-semibold text-gray-800">
                    @if ($target !== null)
                        <h3>一日の目標消費カロリー: <span id="currentTargetValue">{{ $target->target_burned_calories_day }}</span>kcal</h3>
                    @else
                        <h3><span id="currentTargetValue">まだ目標が設定されていません</span></h3>
                    @endif
                </div>
                <dialog id="targetModal" class="rounded-lg shadow-xl p-6 max-w-md w-full bg-gradient-to-br from-white to-gray-50 border border-gray-100">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">目標消費カロリーを設定</h3>
                    <div class="space-y-4">
                        <!-- 入力フォーム -->
                        <form id="targetCaloriesForm">
                            @csrf
                            <div class="relative">
                                <input
                                    id="targetCaloriesBurned"
                                    type="number"
                                    name="target_burned_calories_day"
                                    placeholder="目標消費カロリー(日)"
                                    class="border border-gray-300 rounded-lg p-3 w-full shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 outline-none transition-all duration-200"
                                />
                                <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm">kcal</span>
                            </div>
                        </form>

                        <!-- ボタンを横並びに -->
                        <div class="flex space-x-3 pt-2">
                            <!-- 設定ボタン -->
                            <button
                                type="submit"
                                form="targetCaloriesForm"
                                class="flex-1 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-medium py-2.5 px-4 rounded-lg transition duration-200 ease-in-out shadow-sm hover:shadow transform hover:-translate-y-0.5"
                            >
                                設定
                            </button>

                            <!-- 閉じるボタン -->
                            <form method="dialog" class="flex-1">
                                <button
                                    type="submit"
                                    class="w-full bg-gradient-to-r from-gray-100 to-gray-200 hover:from-gray-200 hover:to-gray-300 text-gray-700 font-medium py-2.5 px-4 rounded-lg transition duration-200 ease-in-out border border-gray-300 shadow-sm hover:shadow transform hover:-translate-y-0.5"
                                >
                                    閉じる
                                </button>
                            </form>
                        </div>
                    </div>
                </dialog>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded transition duration-150 ease-in-out">
                        ログアウト
                    </button>
                </form>
            </div>
        </header>

        <div class="container-fluid px-2 py-4">
            <!-- 月別カロリーグラフ -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden w-full mb-6">
                <div class="p-4 bg-gradient-to-r from-purple-500 to-indigo-600">
                    <h2 class="text-white font-bold text-lg">月別カロリーデータ</h2>
                </div>
                <div class="p-4">
                    <div class="flex mb-4">
                        <select id="yearSelector"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-32 p-2.5">
                            @foreach ($per_years as $yearData)
                                <option value="{{ $yearData->year }}">{{ $yearData->year }}年</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="chart-container">
                            <canvas id="monthlyCalorieChart"></canvas>
                        </div>
                        <div class="chart-container">
                            <canvas id="monthlyBalanceChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 日別カロリーリスト -->
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
                                    {{ number_format($record->total_burned + $user_info->bmr_round) }} kcal
                                </div>
                                <div
                                    class="{{ $record->total_intake - $record->total_burned - $user_info->bmr_round > 0 ? 'text-red-500' : 'text-green-500' }} font-medium">
                                    {{ number_format($record->total_intake - $record->total_burned - $user_info->bmr_round ) }} kcal
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
        <a href="{{ route('records.create') }}"
            class="fixed bottom-6 right-6 w-14 h-14 flex items-center justify-center rounded-full bg-purple-600 text-white text-2xl shadow-lg hover:bg-purple-700 transition-colors">
            ＋
        </a>

        <script>
            const targetModal = document.getElementById("targetModal");
            targetModal.addEventListener("click", e => {
                if (e.target === targetModal) {
                    targetModal.close();
                }
            });

            const userModal = document.getElementById("userModal");
            userModal.addEventListener("click", e => {
                if (e.target === userModal) {
                    userModal.close();
                }
            });

            document.addEventListener('DOMContentLoaded', function() {
                // サイドバーから月ごとのデータを取得
                const monthlyData = @json($per_months);
                const yearSelector = document.getElementById('yearSelector');
                let calorieChart;
                let balanceChart;

                // グラフを描画する関数
                function renderCharts(selectedYear) {
                    // 選択された年のデータをフィルタリング
                    const filteredData = monthlyData.filter(item => item.year == selectedYear);

                    // 月の名前を設定
                    const monthNames = ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'];

                    // データを月順に並べ替え
                    filteredData.sort((a, b) => a.month - b.month);

                    // グラフ用のデータを準備
                    const labels = filteredData.map(item => monthNames[item.month - 1]);
                    const intakeValues = filteredData.map(item => item.month_total_intake);
                    const burnedValues = filteredData.map(item => item.month_total_burned);
                    const balanceValues = filteredData.map(item => item.month_total_intake - item.month_total_burned);

                    // 既存のチャートがあれば破棄
                    if (calorieChart) {
                        calorieChart.destroy();
                    }
                    if (balanceChart) {
                        balanceChart.destroy();
                    }

                    // カロリー比較チャート（棒グラフ）
                    const ctxCalorie = document.getElementById('monthlyCalorieChart').getContext('2d');
                    calorieChart = new Chart(ctxCalorie, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                    label: '摂取カロリー',
                                    data: intakeValues,
                                    backgroundColor: 'rgba(255, 159, 64, 0.7)',
                                    borderColor: 'rgba(255, 159, 64, 1)',
                                    borderWidth: 1
                                },
                                {
                                    label: '消費カロリー',
                                    data: burnedValues,
                                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                                    borderColor: 'rgba(54, 162, 235, 1)',
                                    borderWidth: 1
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                title: {
                                    display: true,
                                    text: selectedYear + '年 月別カロリー比較',
                                    font: {
                                        size: 16
                                    }
                                },
                                legend: {
                                    position: 'bottom'
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: 'カロリー (kcal)'
                                    }
                                }
                            }
                        }
                    });

                    // バランスチャート（線グラフ）
                    const ctxBalance = document.getElementById('monthlyBalanceChart').getContext('2d');
                    balanceChart = new Chart(ctxBalance, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'カロリー収支',
                                data: balanceValues,
                                fill: true,
                                backgroundColor: function(context) {
                                    const chart = context.chart;
                                    const {
                                        ctx,
                                        chartArea
                                    } = chart;
                                    if (!chartArea) {
                                        return null;
                                    }
                                    const gradient = ctx.createLinearGradient(0, chartArea.bottom,
                                        0, chartArea.top);
                                    gradient.addColorStop(0.5, 'rgba(75, 192, 192, 0.1)');
                                    gradient.addColorStop(1, 'rgba(255, 99, 132, 0.1)');
                                    return gradient;
                                },
                                borderColor: function(context) {
                                    const value = context.raw;
                                    return value >= 0 ? 'rgba(255, 99, 132, 1)' :
                                        'rgba(75, 192, 192, 1)';
                                },
                                borderWidth: 2,
                                pointBackgroundColor: function(context) {
                                    const value = context.raw;
                                    return value >= 0 ? 'rgba(255, 99, 132, 1)' :
                                        'rgba(75, 192, 192, 1)';
                                },
                                tension: 0.1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                title: {
                                    display: true,
                                    text: selectedYear + '年 月別カロリー収支',
                                    font: {
                                        size: 16
                                    }
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            let label = context.dataset.label || '';
                                            let value = context.raw;
                                            if (value >= 0) {
                                                return label + ': +' + value + ' kcal（摂取超過）';
                                            } else {
                                                return label + ': ' + value + ' kcal（消費超過）';
                                            }
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    title: {
                                        display: true,
                                        text: '収支 (kcal)'
                                    }
                                }
                            }
                        }
                    });
                }

                // 年選択のイベントリスナー
                yearSelector.addEventListener('change', function() {
                    renderCharts(this.value);
                });

                // 初期表示
                if (yearSelector.options.length > 0) {
                    renderCharts(yearSelector.value);
                }
            });
        </script>
        <script src="{{ asset('js/calorieTarget.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    </body>

    </html>
@endsection
