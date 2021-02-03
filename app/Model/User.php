<?php

namespace App\Model;

use App\Model\Traits\Timestamp;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    use HasRoles, Timestamp;
    protected $table = 'users';
    protected $guarded = [];
    protected $appends = ['branch_value'];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        ];

        protected $hidden = [
            'password', 'remember_token',
        ];
    
        public function getJWTIdentifier()
        {
            return $this->getKey();
        }

    
        /**
         * Return a key value array, containing any custom claims to be added to the JWT.
         *
         * @return array
         */
        public function getJWTCustomClaims()
        {
            return [];
        }

        public function getBranchValueAttribute()
        {
            $value = $this->attributes['branch'];
            $bid = explode(',',$value);

            $bname ="";
            foreach($bid as $id){
                $str = DB::table('branch')->where('id',$id)->value('name');
                $bname .= $str.'，';
            }
            return $bname;
        }
}
