<?php

namespace App\Model;

use App\Model\Tenant;
use App\Model\Traits\Timestamp;
use Illuminate\Database\Eloquent\Model;

class FollowUp extends Model
{  
    use Timestamp;
    protected $table = "follow_up";
    protected $guarded = [];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class,'id','tenant_id');
    }
}
