<?php

namespace App\Model;

use Kalnoy\Nestedset\NodeTrait;
use Illuminate\Database\Eloquent\Model;
use Jiaxincui\ClosureTable\Traits\ClosureTable;

class Branch extends Model
{
    use NodeTrait;
    protected $guarded = [];
    protected $table = "branch";
   // protected $closureTable = 'branch_closure';

}
