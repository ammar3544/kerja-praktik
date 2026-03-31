<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;

class DashboardController extends Controller
{
    public function index()
    {

        $totalComments = Comment::count();

        $positif = Comment::where('sentiment','positif')->count();
        $netral  = Comment::where('sentiment','netral')->count();
        $negatif = Comment::where('sentiment','negatif')->count();

        $topWords = Comment::select('text')->limit(200)->get()
            ->flatMap(function ($c) {
                return explode(' ', strtolower($c->text));
            })
            ->filter(function ($w) {
                return strlen($w) > 3;
            })
            ->countBy()
            ->sortDesc()
            ->take(10)
            ->keys();

        return view('dashboard',[
            'totalComments'=>$totalComments,
            'positif'=>$positif,
            'netral'=>$netral,
            'negatif'=>$negatif,
            'topWords'=>$topWords,

            // dummy untuk sementara
            'highRisk'=>0,
            'clusterCount'=>0,
            'burstDetected'=>false
        ]);

    }

}