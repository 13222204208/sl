<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Traits\Timestamp;

class Clean extends Model
{
    protected $table = "clean";
    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        ];
}
