<?php

namespace App\Model;

use App\Model\Traits\Timestamp;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class GetClean extends Model
{  
    use Timestamp;
    protected $table = "clean";
    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        ];

        public function getIsWeCompanyAttribute($value){
            if($value == 1){
                return '是';
            }else{
                return "否";
            }      
        } 
    
        public function getCompanyTypeAttribute($value){
            $companyType = DB::table('company_type')->where('id',intval($value))->value('type_name');
            return $companyType;   
        }
    
        public function getPayTypeAttribute($value){
            $payType = DB::table('paytype')->where('id',intval($value))->value('type_name');
            return $payType;   
        }
    
        public function getTenantNeedAttribute($value){
            $payType = DB::table('demand')->where('id',intval($value))->value('type_name');
            return $payType;   
        } 
    
        public function getTenantUserAttribute($value){
            $values = json_decode($value,true); 
            $data = "";
            $kn ="";
            foreach($values as $value){
                foreach($value as $k=> $v){
                    if($k =="name"){
                            $kn ="姓名";
                    } if($k =="tel"){
                        $kn= "手机号";
                    }
                    if($k =="wx"){
                        $kn="微信号";
                    }
                    $data .= $kn.":".$v.' | ';
                        
                }
            }
               return $data;
        } 

        public function getHousesNumAttribute($value)
        {
            $h_n ="";
            $houses_num = $value;
            $hnum= array_filter(explode(',',$houses_num));
            foreach($hnum as $num){
               $h_num=  Level::where('id',intval($num))->value('type_name');

               $h_n .= ','.$h_num;
            }
             $h_n= substr($h_n,1);

             return $h_n;
        }

        public function getPositionAttribute($value)
        {
            $data ="";
            
            //foreach($value as $v)
        }
}
