<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>カロリー記録チャンネル</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600;700&display=swap" rel="stylesheet" />
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Figtree', sans-serif;
        }
    </style>
</head>
<body class="antialiased bg-gradient-to-br from-purple-50 to-purple-100 text-gray-800">
    <div class="min-h-screen flex flex-col justify-center items-center relative px-4">
        <!-- ログイン・登録リンク -->
        @if (Route::has('login'))
            <div class="absolute top-6 right-6">
                <div class="space-x-4">
                    @auth
                        <a href="{{ url('/index') }}" class="text-sm text-purple-700 font-semibold hover:underline">メイン画面</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm text-purple-700 font-semibold hover:underline">ログイン</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="text-sm text-purple-700 font-semibold hover:underline">登録</a>
                        @endif
                    @endauth
                </div>
            </div>
        @endif

        <!-- コンテンツカード -->
        <div class="bg-white rounded-2xl shadow-2xl p-10 max-w-xl w-full text-center border border-purple-200 space-y-6">
            <!-- ロゴ -->
            <div>
                <svg viewBox="0 0 62 65" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-14 w-auto mx-auto opacity-90">
                    <path d="M61.8548 14.6253..." fill="#A78BFA"/>
                </svg>
            </div>

            <!-- タイトル -->
            <h1 class="text-3xl font-bold text-purple-700 tracking-wide drop-shadow-sm">
                カロリー記録アプリ
            </h1>

            <!-- Call to Action ボタン -->
            <a href="{{ route('register') }}"
               class="inline-block px-8 py-3 bg-purple-400 text-white font-semibold rounded-full shadow-lg hover:bg-purple-500 transform hover:scale-105 active:scale-95 transition duration-300">
                登録
            </a>
        </div>

    </div>
</body>
</html>
