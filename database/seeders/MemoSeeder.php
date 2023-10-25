<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Memo;

class MemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //php artisan db:seed --class=MemoSeeder
        $user = \App\Models\User::where('email', 'test@example.com')->firstOrFail();

        Memo::create(['user_id' => $user->id, 'title' => 'фронтенд на TailwindCSS']);
        Memo::create(['user_id' => $user->id, 'title' => 'дашборд с разными способами отображения заметок']);
        Memo::create(['user_id' => $user->id, 'title' => 'поиск, фильтрация']);
        Memo::create(['user_id' => $user->id, 'title' => 'списки / группы заметок']);
        Memo::create(['user_id' => $user->id, 'title' => 'тэги?']);
        Memo::create(['user_id' => $user->id, 'title' => 'drag-n-drop на календаре => обновление due_date']);
        Memo::create(['user_id' => $user->id, 'title' => 'можно ли подключить Liveware к API?']);
        Memo::create(['user_id' => $user->id, 'title' => 'если due_date => напоминалки? (frontend, email, telegram)']);
        Memo::create(['user_id' => $user->id, 'title' => 'комментарии к заметкам']);
    }
}
