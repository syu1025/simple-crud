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
                        {{ $user->name }}さんのユーザー情報
                    </h3>
                </div>
                <dialog id="userModal" class="rounded-lg shadow-xl p-6 max-w-md w-full bg-gradient-to-br from-white to-gray-50 border border-gray-100">
                    <p>こんにちは、{{ $user->name }}さん！</p>
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
                                    {{ number_format($record->total_burned) }} kcal
                                </div>
                                <div
                                    class="{{ $record->total_intake - $record->total_burned > 0 ? 'text-red-500' : 'text-green-500' }} font-medium">
                                    {{ number_format($record->total_intake - $record->total_burned) }} kcal
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
