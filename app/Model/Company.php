<?php

namespace App\Model;

use Kalnoy\Nestedset\NodeTrait;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use NodeTrait;
    protected $guarded = [];
    protected $table = 'company_type';
}
