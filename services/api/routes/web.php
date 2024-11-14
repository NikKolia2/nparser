<?php

use App\Models\ProcessModel;
use Illuminate\Support\Facades\Route;

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
