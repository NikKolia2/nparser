<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkModel extends Model
{
    use HasFactory;
    protected $table = 'process_name';
    protected $guarded = false;

    public $timestamps = false;

    public function processes():HasMany {
        return $this->hasMany(WorkGroupsModel::class, "pid", "pid");
    }
}
