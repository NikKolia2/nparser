<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProcessModel extends Model
{
    use HasFactory;
    protected $table = 'process';
    protected $guarded = false;

    public $timestamps = false;
}
