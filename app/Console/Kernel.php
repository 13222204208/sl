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
            $datas= GetTenant::whereDate('stop_time','<=',$dueTime)->get();
            foreach($datas as $data){ 
                $str= $data['houses_name'].$data['houses_info'].',租户名称：'.$data['tenant_name'].'联系人：'.$data['tenant_user'].',截止到'.$data['pay_time'].'为付款日期'; 

                try {
                    $sms->send($data['broker_phone'], [
                        'template' => 'SMS_195575357',
                        'data' => [
                            'code' => $str
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
 
         })->dailyAt('18:05');

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
