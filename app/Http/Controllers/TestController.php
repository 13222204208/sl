<?php

namespace App\Http\Controllers;

use App\Model\GetTenant;
use Illuminate\Http\Request;
use Overtrue\EasySms\Exceptions\NoGatewayAvailableException;

class TestController extends Controller
{
    public function testsms()
    {
        $sms = app('easysms');

        $dueTime= date("Y-m-d",strtotime("+1 month",time()));
        $datas= GetTenant::whereDate('pay_time','<=',$dueTime)->get(); 
        foreach($datas as $data){ 
/*             $str= $data['houses_name'].$data['houses_info'].',租户名称：'.$data['tenant_name'].'联系人：'.$data['tenant_user'].',截止到'.$data['pay_time'].'为付款日期';  */

            try {
                $sms->send('13222204208', [
                    'template' => 'SMS_207961096',
                    'data' => [
                        'info' => '123', //$data['houses_name'].$data['houses_info'],
                        'name' => '123',//$data['tenant_name'],
                        'user' => '123',//$data['tenant_user'],
                        'time' => '2020-20',//$data['pay_time']
                    ],    
                ]);
            } catch (NoGatewayAvailableException $exception) {
                $message = $exception->getException('aliyun')->getMessage();
                return response()->json([
                    'code' => 0,
                    'msg' => $message
                ], 200);
            }
        }
        
    }
}
