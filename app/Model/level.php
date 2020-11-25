<?php

namespace App\Model;

use App\Model\Traits\Timestamp;
use Kalnoy\Nestedset\NodeTrait;
use Illuminate\Database\Eloquent\Model;

class level extends Model
{
    use  Timestamp;
    use NodeTrait;
    protected $table = 'level';
    protected $guarded = [];
}
