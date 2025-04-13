<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Carbon\Carbon;

class CalorieSeeder extends Seeder
{
    /**
     * データベースシードを実行します。
     */
    public function run(): void
    {
        // まず、ユーザーがいることを確認します
        // すでにユーザーがある場合は、この部分をコメントアウトできます
        if (User::count() == 0) {
            $this->createUsers();
        }

        $users = User::all();

        // 各ユーザーのカロリー目標を作成
        foreach ($users as $user) {
            DB::table('calorie_targets')->insert([
                'user_id' => $user->id,
                'target_burned_calories_day' => rand(300, 800),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 各ユーザーのカロリー記録を作成（過去30日分のデータ）
        foreach ($users as $user) {
            for ($i = 0; $i < 90; $i++) {
                $date = Carbon::now()->subDays($i);

                DB::table('calorie_records')->insert([
                    'user_id' => $user->id,
                    'date' => $date->format('Y-m-d'),
                    'calorie_intake' => rand(1500, 3000),
                    'calorie_burned' => rand(200, 1000),
                    'note' => $this->getRandomNote(),
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
            }
        }
    }

    /**
     * ユーザーが存在しない場合、ダミーユーザーを作成します
     */
    private function createUsers(): void
    {
        $users = [
            [
                'name' => 'テストユーザー',
                'email' => 'test@example.com',
                'password' => bcrypt('password'),
            ],
            [
                'name' => '山田太郎',
                'email' => 'yamada@example.com',
                'password' => bcrypt('password'),
            ],
            [
                'name' => '佐藤花子',
                'email' => 'sato@example.com',
                'password' => bcrypt('password'),
            ],
        ];

        foreach ($users as $userData) {
            DB::table('users')->insert([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => $userData['password'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * カロリー記録用のランダムなメモを取得します
     */
    private function getRandomNote(): string
    {
        $notes = [
            'チートデイでした',
            '軽いワークアウト',
            '激しい有酸素運動',
            'カロリー目標内に収まりました',
            'ランチをスキップ',
            'ランニングをしました',
            'ジムでトレーニング',
            '休息日',
            '今日は疲れていました',
            'タンパク質中心の食事',
            null,
        ];

        return $notes[array_rand($notes)] ?? '';
    }
}
