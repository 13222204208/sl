<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
/*          $this->call(ActivityTableSeeder::class);
         $this->call(ApplyActivityTableSeeder::class);
         $this->call(BettingTableSeeder::class); */
         $this->call(BgUsersTableSeeder::class);
         $this->call(CleanTableSeeder::class);
         $this->call(TenantTableSeeder::class);
/*          $this->call(FeedbackTableSeeder::class);
         $this->call(FRechargeTableSeeder::class);
         $this->call(PlatformTableSeeder::class);
         $this->call(TransactionTableSeeder::class);
         $this->call(UserInfoTableSeeder::class);
         $this->call(UserStatisticsTableSeeder::class);
         $this->call(WithdrawalTableSeeder::class);
         $this->call(MemberRechargeTableSeeder::class);
         $this->call(MemberDrawMoneyTableSeeder::class);
         $this->call(RotationChartTableSeeder::class);
         $this->call(RunHorseTableSeeder::class);
         $this->call(BankCardTableSeeder::class);
         $this->call(WechatPayTableSeeder::class);
         $this->call(AlipayTableSeeder::class);
         $this->call(UserDetailTableSeeder::class); */
    }
}
