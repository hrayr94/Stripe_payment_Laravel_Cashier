<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlanSeeder extends Seeder
{
    public function run()
    {
        $plans = [
            [
                'plan_id' => 'plan_Nj5dnQHB58OMTx',
                'name' => 'Silver',
                'price' => '100',
                'billing_method' => 'day',
                'interval_count' => 1,
                'currency' => 'usd'
            ],
            [
                'plan_id' => 'plan_Nj4z17ve27mlYQ',
                'name' => 'Gold',
                'price' => '255',
                'billing_method' => 'week',
                'interval_count' => 3,
                'currency' => 'usd'
            ]
        ];

        foreach ($plans as $plan) {
            Plan::create($plan);
        }
    }
}
