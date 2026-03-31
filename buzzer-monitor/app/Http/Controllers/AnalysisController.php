<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;

class AnalysisController extends Controller
{

    public function index(Request $request)
    {

        $task = $request->task;
        $comments = Comment::where('task_id',$task)->get();
        $positif = $comments->where('sentiment','positif')->count();
        $netral = $comments->where('sentiment','netral')->count();
        $negatif = $comments->where('sentiment','negatif')->count();
        

        // TOP WORDS
        $words = [];

        foreach($comments as $c){

            $split = explode(" ", strtolower($c->text));

            foreach($split as $w){

                if(strlen($w) < 4) continue;

                $words[$w] = ($words[$w] ?? 0) + 1;

            }

        }

        arsort($words);

        $topWords = [];

        foreach(array_slice($words,0,10,true) as $word=>$count){

            $topWords[] = [
                'text'=>$word,
                'count'=>$count
            ];

        }

        // DUMMY DATA supaya blade tidak error
        $accountTypes = [];
        $topEmojis = [];
        $similar = [];
        $clusters = [];
        $topics = [];
        $burst = false;
        
    

        return view('analysis',[
            'comments'=>$comments,
            'positif'=>$positif,
            'netral'=>$netral,
            'negatif'=>$negatif,
            'topWords'=>$topWords,
            'accountTypes'=>$accountTypes,
            'topEmojis'=>$topEmojis,
            'similar'=>$similar,
            'clusters'=>$clusters,
            'topics'=>$topics,
            'burst'=>$burst
        ]);

    }

    public function data()
    {

        $comments = Comment::latest()->take(1000)->get()->toArray();

        $similar = app(SimilarityAnalyzer::class)->detect($comments);
        $clusters = app(NetworkAnalyzer::class)->detect($comments);
        $burst = app(BurstAnalyzer::class)->detect($comments);

        return response()->json([
            'comments_count' => count($comments),
            'similar_count' => count($similar),
            'cluster_count' => count($clusters),
            'burst' => $burst
        ]);

    }


}