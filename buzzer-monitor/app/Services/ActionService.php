<?php

namespace App\Services;

use App\Models\Action;

class ActionService
{
    public function handle($taskId, $result)
    {
        if ($result['final_score'] > 70) {
            Action::create([
                'task_id' => $taskId,
                'action_type' => 'flag_cib',
                'description' => 'Auto-detected coordinated campaign'
            ]);
        }
    }
}