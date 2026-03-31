<?php

namespace App\Http\Controllers;

use App\Models\Comment;

class BuzzerController extends Controller
{

    public function analyze($task)
    {

        $comments = Comment::where("task_id",$task)->get();

        if($comments->count() == 0){
            return view("buzzer.analysis",[
                "comments"=>[],
                "analysis"=>[],
                "topWords"=>[],
                "burst"=>false
            ]);
        }

        $json = $comments->toJson();

        $script = base_path("scraper_engine/run_scraper.py");

        $command = "python \"$script\" '".addslashes($json)."'";

        $output = shell_exec($command);

        $analysis = json_decode($output,true);

        return view("buzzer.analysis",[

            "comments"=>$comments,
            "analysis"=>$analysis,
            "topWords"=>[],
            "burst"=>false

        ]);

    }

}