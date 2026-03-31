<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Str;
use App\Services\SentimentService;

class ScraperController extends Controller
{

    public function index()
    {
        return view('scraper.index');
    }

    public function scrape(Request $request)
    {

        $url = $request->url;

        if(str_contains($url,'tiktok.com')){
            $platform = "tiktok";
        }
        elseif(str_contains($url,'youtube.com') || str_contains($url,'youtu.be')){
            $platform = "youtube";
        }
        else{
            return back()->with('error','Link tidak dikenali');
        }

        $script = base_path('scraper_engine/run_scraper.py');

        $platform = escapeshellarg($platform);
        $url = escapeshellarg($url);

        $command = "cd ".base_path('scraper_engine')." && python run_scraper.py $platform $url";

        $output = shell_exec($command);

        $comments = json_decode($output, true);

        if(!is_array($comments)){
            $comments = [];
        }

        $task = Str::uuid();

        $sentiment = new SentimentService();

        foreach($comments as $c){

            $text = $c['text'] ?? '';

            $label = $sentiment->analyze($text);

            Comment::create([
                'task_id' => $task,
                'user' => $c['user'] ?? 'unknown',
                'text' => $text,
                'platform' => $platform,
                'likes' => $c['likes'] ?? 0,
                'sentiment' => $label
            ]);

        }

        return redirect("/analysis?task=".$task);

        $data = json_decode(trim($output), true);

        $comments = $data["comments"] ?? [];
        $similar  = $data["similar"] ?? [];
        $clusters = $data["clusters"] ?? [];
        $topics   = $data["topics"] ?? [];

    }

    public function result()
    {
        return view('scraper.result');
    }

    
}