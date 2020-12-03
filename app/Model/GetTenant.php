<?php

namespace App\Model;

use App\Model\Traits\Timestamp;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class GetTenant extends Model
{
    use  Timestamp;
    protected $table = "tenant";
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
        $value= ltrim($value,'"[');
        $value= rtrim($value,']"');
        $value = json_decode($value,true);
        $data = "";
        $kn ="";
       foreach($value as $k=> $v){
           if($k =="name"){
                $kn ="姓名";
           } if($k =="tel"){
             $kn= "手机号";
           }
           if($k =="wx"){
            $kn="微信号";
           }
           $data .= $kn.":".$v.' ';
            
       }

       return $data;
    } 
}
