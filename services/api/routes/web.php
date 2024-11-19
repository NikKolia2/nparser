<?php

use App\Models\WorkModel;
use App\Models\ProcessModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix("v1")->group(function(){
    Route::get('/status', function () {
        $countSuccess = ProcessModel::where("status_id", 4)->count();
        $remians = ProcessModel::where("status_id", 1)->count();
        $countInLoaders = ProcessModel::where("status_id", 2)->count();
        
        return  response()->json([
            "countProcessSuccess" => $countSuccess,
            "countProcessRemains" => $remians,
            "countProcessInLoaders" => $countInLoaders
        ]);
    });
    
    Route::prefix("works")->group(function(){
        Route::get("/", function(){
            $works = WorkModel::get();
            $newWorks = [];

            foreach($works as $work){
                $urls = collect($work->processes)->map(function($item){
                    return $item->url;
                });

                $countSucessProcess = ProcessModel::where("status_id", 4)->whereIn("url", $urls)->count();
                $countRemains = ProcessModel::where("status_id", "!=", 4)->whereIn("url", $urls)->count();

                $newWorks[] = [
                    "id" => $work->pid,
                    "name" => $work->name,
                    "progress" => [
                        'countSuccess' => $countSucessProcess,
                        'countRemains' => $countRemains 
                    ]
                ];
            }

            return response()->json($newWorks);
        });

        Route::get("/{pid}", function(Request $request){
           
            $work = WorkModel::where("pid", "001f138cde15f7e4096ca3fd54688821516cf1e19548be3e835a332b664693fd")->first();
            $urls = collect($work->processes)->map(function($item){
                return $item->url;
            });

            $countSucessProcess = ProcessModel::where("status_id", 4)->whereIn("url", $urls)->count();
            $countRemains = ProcessModel::where("status_id", "!=", 4)->whereIn("url", $urls)->count();

            $newWork = [
                "id" => $work->pid,
                "name" => $work->name,
                "progress" => [
                    'countSuccess' => $countSucessProcess,
                    'countRemains' => $countRemains 
                ]
            ];
            

            return response()->json($newWork);
        });
    });
});

Route::get('/', function () {
    $countSuccess = ProcessModel::where("status_id", 4)->count();
    $remians = ProcessModel::where("status_id", 1)->count();
    $countInLoaders = ProcessModel::where("status_id", 2)->count();
    
    return view('status-parser', [
        "countSuccess" => $countSuccess,
        "remains" => $remians,
        "countInLoaders" => $countInLoaders
    ]);
});
