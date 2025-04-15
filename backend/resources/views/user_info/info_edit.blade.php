<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>プロフィール更新</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body class="bg-gray-100">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- ヘッダー部分 -->
                    <div class="mb-6 border-b pb-4">
                        <h2 class="text-2xl font-bold text-gray-800">プロフィール更新</h2>
                    </div>

                    <!-- 成功メッセージ表示エリア -->
                    <div id="success-message" class="hidden mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm leading-5 font-medium"></p>
                            </div>
                        </div>
                    </div>

                    <!-- エラーメッセージ表示エリア -->
                    <div id="error-message" class="hidden mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm leading-5 font-medium"></p>
                            </div>
                        </div>
                    </div>

                    <form id="profile-form" class="space-y-6">
                        @csrf

                        <!-- 性別 user_infoがある、かつgenderが男性ならselected, そうでないなら何もしない -->
                        <div class="grid md:grid-cols-3 md:gap-6">
                            <div class="md:col-span-1">
                                <label for="gender" class="block text-sm font-medium text-gray-700">性別</label>
                            </div>
                            <div class="md:col-span-2">
                                <select id="gender" name="gender" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">選択してください</option>
                                    <option value="男性" {{ isset($user_info) && $user_info?->gender == '男性' ? 'selected' : '' }}>男性</option>
                                    <option value="女性" {{ isset($user_info) && $user_info?->gender == '女性' ? 'selected' : '' }}>女性</option>
                                    <option value="その他" {{ isset($user_info) && $user_info?->gender == 'その他' ? 'selected' : '' }}>その他</option>
                                </select>
                            </div>
                        </div>

                        <!-- 年齢 -->
                        <div class="grid md:grid-cols-3 md:gap-6">
                            <div class="md:col-span-1">
                                <label for="age" class="block text-sm font-medium text-gray-700">年齢</label>
                                <p class="mt-1 text-xs text-gray-500">15～100歳の範囲で入力してください</p>
                            </div>
                            <div class="md:col-span-2">
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <input type="number" name="age" id="age" min="15" max="100" step="1" value="{{ isset($user_info) ? $user_info?->age : '' }}" class="block w-full rounded-l-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <span class="inline-flex items-center rounded-r-md border border-l-0 border-gray-300 bg-gray-50 px-3 text-gray-500">歳</span>
                                </div>
                                <p id="age-error" class="mt-1 hidden text-sm text-red-600"></p>
                            </div>
                        </div>

                        <!-- 身長 -->
                        <div class="grid md:grid-cols-3 md:gap-6">
                            <div class="md:col-span-1">
                                <label for="height" class="block text-sm font-medium text-gray-700">身長</label>
                                <p class="mt-1 text-xs text-gray-500">100～200cmの範囲で入力してください</p>
                            </div>
                            <div class="md:col-span-2">
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <input type="number" name="height" id="height" min="100" max="200" step="0.1" value="{{ isset($user_info) ? $user_info?->height : '' }}" class="block w-full rounded-l-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <span class="inline-flex items-center rounded-r-md border border-l-0 border-gray-300 bg-gray-50 px-3 text-gray-500">cm</span>
                                </div>
                                <p id="height-error" class="mt-1 hidden text-sm text-red-600"></p>
                            </div>
                        </div>

                        <!-- 体重 -->
                        <div class="grid md:grid-cols-3 md:gap-6">
                            <div class="md:col-span-1">
                                <label for="weight" class="block text-sm font-medium text-gray-700">体重</label>
                                <p class="mt-1 text-xs text-gray-500">30～100kgの範囲で入力してください</p>
                            </div>
                            <div class="md:col-span-2">
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <input type="number" name="weight" id="weight" min="30" max="100" step="0.1" value="{{ isset($user_info) ? $user_info?->weight : '' }}" class="block w-full rounded-l-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <span class="inline-flex items-center rounded-r-md border border-l-0 border-gray-300 bg-gray-50 px-3 text-gray-500">kg</span>
                                </div>
                                <p id="weight-error" class="mt-1 hidden text-sm text-red-600"></p>
                            </div>
                        </div>

                        <!-- 基礎代謝プレビュー (身長・体重・年齢・性別が全て入力された場合のみ表示) -->
                        <div id="bmr-preview" class="hidden mt-6 p-4 bg-green-50 rounded-lg">
                            <div class="flex items-center space-x-2 mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5 10a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1z" clip-rule="evenodd" />
                                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v8a1 1 0 11-2 0V6a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                                <h3 class="font-medium text-green-800">入力情報に基づく基礎代謝予測</h3>
                            </div>
                            <div class="text-xl font-bold text-green-700" id="bmr-value">
                                計算中...
                            </div>
                            <div class="text-sm text-green-600 mt-1">※ハリス・ベネディクト方程式による概算値</div>
                        </div>

                        <!-- ボタン -->
                        <div class="flex justify-end pt-6 border-t border-gray-200 mt-6">
                            <a href="{{ route('records.index') }}" class="mr-3 px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors duration-200">
                                キャンセル
                            </a>
                            <button type="submit" id="save-button" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                <span>保存する</span>
                                <span id="loading-spinner" class="hidden ml-2 animate-spin">&#9696;</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('profile-form');
            const successMessage = document.getElementById('success-message');
            const errorMessage = document.getElementById('error-message');
            const saveButton = document.getElementById('save-button');
            const loadingSpinner = document.getElementById('loading-spinner');
            const bmrPreview = document.getElementById('bmr-preview');
            const bmrValue = document.getElementById('bmr-value');  //計算中のid

            // 入力フィールド
            const genderField = document.getElementById('gender');
            const ageField = document.getElementById('age');
            const heightField = document.getElementById('height');
            const weightField = document.getElementById('weight');

            // エラーメッセージ表示用要素
            const ageError = document.getElementById('age-error');
            const heightError = document.getElementById('height-error');
            const weightError = document.getElementById('weight-error');

            // リアルタイムバリデーションと基礎代謝計算
            function validateAndCalculate() {
                // バリデーションフラグ
                let isValid = true;

                // 年齢のバリデーション
                if (ageField.value) {
                    if (ageField.value < 15 || ageField.value > 100) {
                        ageError.textContent = '年齢は15〜100の間で入力してください';
                        ageError.classList.remove('hidden');
                        ageField.classList.add('border-red-500');
                        isValid = false;
                    } else {
                        ageError.classList.add('hidden');
                        ageField.classList.remove('border-red-500');
                    }
                }

                // 身長のバリデーション
                if (heightField.value) {
                    if (heightField.value < 100 || heightField.value > 200) {
                        heightError.textContent = '身長は100〜200cmの間で入力してください';
                        heightError.classList.remove('hidden');
                        heightField.classList.add('border-red-500');
                        isValid = false;
                    } else {
                        heightError.classList.add('hidden');
                        heightField.classList.remove('border-red-500');
                    }
                }

                // 体重のバリデーション
                if (weightField.value) {
                    if (weightField.value < 30 || weightField.value > 100) {
                        weightError.textContent = '体重は30〜100kgの間で入力してください';
                        weightError.classList.remove('hidden');
                        weightField.classList.add('border-red-500');
                        isValid = false;
                    } else {
                        weightError.classList.add('hidden');
                        weightField.classList.remove('border-red-500');
                    }
                }

                // 全てのフィールドが有効な値で入力されている場合、基礎代謝を計算して表示
                if (isValid && genderField.value && ageField.value && heightField.value && weightField.value) {
                    let bmr = 0;

                    if (genderField.value === '男性') {
                        bmr = 13.397 * weightField.value + 4.799 * heightField.value - 5.677 * ageField.value + 88.362;
                    } else {
                        bmr = 9.247 * weightField.value + 3.098 * heightField.value - 4.330 * ageField.value + 447.593;
                    }

                    bmrValue.textContent = `${Math.round(bmr)} kcal/日`; //概算
                    bmrPreview.classList.remove('hidden');
                } else {
                    bmrPreview.classList.add('hidden');
                }

                return isValid;
            }

            // 入力フィールドの変更を監視
            [genderField, ageField, heightField, weightField].forEach(field => {
                field.addEventListener('input', validateAndCalculate);
            });

            // フォーム送信時の処理
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                // 送信前の最終バリデーション
                if (!validateAndCalculate()) {
                    // エラーメッセージの集約
                    let errorList = [];
                    if (!ageError.classList.contains('hidden')) errorList.push(ageError.textContent);
                    if (!heightError.classList.contains('hidden')) errorList.push(heightError.textContent);
                    if (!weightError.classList.contains('hidden')) errorList.push(weightError.textContent);

                    // エラーメッセージの表示
                    errorMessage.querySelector('p').innerHTML = errorList.join('<br>');
                    errorMessage.classList.remove('hidden');
                    successMessage.classList.add('hidden');

                    // フォームの先頭までスクロール
                    window.scrollTo({top: form.offsetTop - 20, behavior: 'smooth'});
                    return;
                }

                // 送信中の表示
                saveButton.disabled = true;
                loadingSpinner.classList.remove('hidden');

                // FormDataオブジェクトの作成
                const formData = new FormData(form);

                // CSRFトークンの取得
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                // Fetch APIでデータを送信
                fetch('{{ route("profile.update") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    // 送信中の表示を解除
                    saveButton.disabled = false;
                    loadingSpinner.classList.add('hidden');

                    if (data.success) {
                        // 成功メッセージの表示
                        successMessage.querySelector('p').textContent = data.message || 'プロフィールを更新しました';
                        successMessage.classList.remove('hidden');
                        errorMessage.classList.add('hidden');

                        // フォームの先頭までスクロール
                        window.scrollTo({top: form.offsetTop - 20, behavior: 'smooth'});

                        // 3秒後にプロフィール表示ページへリダイレクト
                        setTimeout(() => {
                            window.location.href = '{{ route("records.index") }}';
                        }, 3000);
                    } else {
                        // エラーメッセージの表示
                        if (data.errors) {
                            let errorList = [];
                            for (const key in data.errors) {
                                errorList.push(data.errors[key]);
                            }
                            errorMessage.querySelector('p').innerHTML = errorList.join('<br>');
                        } else {
                            errorMessage.querySelector('p').textContent = data.message || '更新に失敗しました';
                        }
                        errorMessage.classList.remove('hidden');
                        successMessage.classList.add('hidden');

                        // フォームの先頭までスクロール
                        window.scrollTo({top: form.offsetTop - 20, behavior: 'smooth'});
                    }
                })
                .catch(error => {
                    // 送信中の表示を解除
                    saveButton.disabled = false;
                    loadingSpinner.classList.add('hidden');

                    // エラーメッセージの表示
                    errorMessage.querySelector('p').textContent = '通信エラーが発生しました。後でもう一度お試しください。';
                    errorMessage.classList.remove('hidden');
                    successMessage.classList.add('hidden');

                    // フォームの先頭までスクロール
                    window.scrollTo({top: form.offsetTop - 20, behavior: 'smooth'});
                    console.error('Error:', error);
                });
            });

            // 初期表示時のバリデーションと基礎代謝計算
            validateAndCalculate();
        });
    </script>
</body>
</html>
