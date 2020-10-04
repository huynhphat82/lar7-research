<?php

use App\Channel;
use App\Company;
use App\Order;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('companies')->truncate();
        DB::table('channels')->truncate();
        DB::table('orders')->truncate();

        $countChannel = 1;

        for ($i = 1; $i <= 10; $i++) {
            factory(Company::class)->create([
                'code' => 'C000'.($i < 10 ? '0'.$i : $i)
            ])->each(function ($company) {

            });
            for ($j = 1; $j <= 2; $j++) {
                factory(Channel::class)->create([
                    'code' => 'R000'.($countChannel  < 10 ? '0'.$countChannel : $countChannel),
                    'company_id' => $i
                ]);
                for ($k = 1; $k <= 10; $k++) {
                    factory(Order::class)->create([
                        'channel_id' => $countChannel,
                    ]);
                }
                $countChannel++;
            }
        }
    }
}
