<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkGroupsModel extends Model
{
    use HasFactory;
    protected $table = 'process_groups';
    protected $guarded = false;

    public $timestamps = false;
}
