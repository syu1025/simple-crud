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
<body class="antialiased bg-emerald-50 text-gray-800">
    <div class="min-h-screen flex flex-col justify-center items-center relative px-4">
        <!-- ログイン・登録リンク -->
        @if (Route::has('login'))
            <div class="absolute top-6 right-6">
                <div class="space-x-4">
                    @auth
                        <a href="{{ url('/index') }}" class="text-sm text-emerald-800 font-semibold hover:underline">メイン画面</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm text-emerald-800 font-semibold hover:underline">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="text-sm text-emerald-800 font-semibold hover:underline">Register</a>
                        @endif
                    @endauth
                </div>
            </div>
        @endif

        <!-- コンテンツカード -->
        <div class="bg-white rounded-2xl shadow-sm p-10 max-w-xl w-full text-center border border-emerald-100">
            <!-- ロゴ -->
            <div class="mb-6">
                <svg viewBox="0 0 62 65" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-14 w-auto mx-auto opacity-90">
                    <path d="M61.8548 14.6253..." fill="#3B8266"/>
                </svg>
            </div>
            <!-- タイトル -->
            <h1 class="text-3xl font-bold text-emerald-800 mb-4 tracking-wide">カロリー記録アプリ</h1>
            <!-- 説明文 -->
            <p class="text-base text-gray-600 mb-6 leading-relaxed">
            </p>
            <!-- Call to Action ボタン -->
            <a href="{{ route('register') }}" class="inline-block px-6 py-2 bg-emerald-600 text-white font-medium rounded-full hover:bg-emerald-700 transition">
                登録
            </a>
        </div>
    </div>
</body>
</html>
