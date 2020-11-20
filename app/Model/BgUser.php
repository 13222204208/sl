<?php

namespace App\Model; 

use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class BgUser extends Authenticatable
{
    use HasRoles;
    protected $table = 'users';

}
