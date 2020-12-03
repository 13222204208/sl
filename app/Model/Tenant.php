<?php

namespace App\Model;

use App\Model\Traits\Timestamp;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use  Timestamp;
    protected $table = "tenant";
    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        ];

}
