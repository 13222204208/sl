<?php

namespace App\Console;

use App\Model\GetTenant;
use Illuminate\Support\Facades\DB;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Overtrue\EasySms\Exceptions\NoGatewayAvailableException;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
         $schedule->command('inspire')->hourly();
         $schedule->call(function () {

            $sms = app('easysms');

            $dueTime= date("Y-m-d",strtotime("+1 month",time()));
            $yDay= date("Y-m-d",strtotime("-2 day"));
            $datas= GetTenant::whereDate('pay_time','<=',$dueTime)->where('pay_time','>',$yDay)->get(); 
            foreach($datas as $data){    
                $user = $data->tenant_user;     
                $leg = strpos($user,'手机号'); 
        
                $info = substr($data->houses_name,0,19);
                $name = substr($data->tenant_name,0,18);
                $phone= substr($user,$leg+10,$leg-2);
                $time = $data->pay_time;
             
                try {//付款提醒
                    $sms->send($data->broker_phone, [
                        'template' => 'SMS_207961094',
                        'data' => [
                            'info' => $info,
                            'name' => $name,
                            'user' => $phone,
                            'time' => $time
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
    
            $datas= GetTenant::whereDate('stop_time','<=',$dueTime)->where('pay_time','>',$yDay)->get(); 
            foreach($datas as $data){ 
                $user = $data->tenant_user;     
                $leg = strpos($user,'手机号'); 
        
                $info = substr($data->houses_name,0,19);
                $name = substr($data->tenant_name,0,18);
                $phone= substr($user,$leg+10,$leg-2);
                $time = $data->stop_time;
    
                try {//付款提醒
                    $sms->send($data->broker_phone, [
                        'template' => 'SMS_207961096',
                        'data' => [
                            'info' => $info,
                            'name' => $name,
                            'user' => $phone,
                            'time' => $time
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
         })->dailyAt('10:00');


    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
