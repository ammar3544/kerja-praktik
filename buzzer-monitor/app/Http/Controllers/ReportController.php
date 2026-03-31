<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{

    public function index()
    {

        $reports = Comment::select(
            'task_id',
            DB::raw('COUNT(*) as total'),
            DB::raw('MIN(platform) as platform'),
            DB::raw('MIN(created_at) as date')
        )
        ->groupBy('task_id')
        ->orderBy('date','desc')
        ->get();

        return view('reports',[
            'reports'=>$reports
        ]);

    }


    public function delete($task)
    {   

    \App\Models\Comment::where('task_id',$task)->delete();

    return redirect()->route('reports')
        ->with('success','Report berhasil dihapus');

    }
}