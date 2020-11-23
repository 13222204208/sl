<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Traits\Timestamp;

class Phone extends Model
{  
    use Timestamp;
    protected $table = "phone";
    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        ];
}
