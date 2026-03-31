<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function runDetection($taskId)
{
    $result = $this->detectionService->analyzeTask($taskId);

    $this->ActionService->handle($taskId, $result);

    return view('buzzer.result', compact('result'));
}
}

