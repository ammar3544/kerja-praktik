<?php

namespace App\Services;

use App\Models\Task;
use App\Models\Account;
use App\Models\Comment;

class ScraperService
{
    public function scrape(Task $task)
    {
        // Simulasi scraping
        $data = [
            ['username' => 'user123', 'content' => 'Ini kebijakan gagal total'],
            ['username' => 'akun9999', 'content' => 'Bohong semua ini']
        ];

        foreach ($data as $item) {

            $account = Account::firstOrCreate(
                ['username' => $item['username']]
            );

            Comment::create([
                'task_id' => $task->id,
                'account_id' => $account->id,
                'content' => $item['content']
            ]);
        }

        $task->update(['status' => 'scraped']);
    }
}