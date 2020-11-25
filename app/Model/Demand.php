<?php

namespace App\Model;

use Kalnoy\Nestedset\NodeTrait;
use Illuminate\Database\Eloquent\Model;

class Demand extends Model
{
    use NodeTrait;
    protected $table = 'demand';
    protected $guarded = [];
}
