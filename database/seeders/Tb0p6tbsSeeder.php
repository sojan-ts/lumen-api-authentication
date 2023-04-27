<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Date;

class Tb0p6tbsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tb0p6tbs')->insert([
            [
                'title' => 'Delhi',
                'visibility' => 1,
                'tb0p5tbs' => 1,
                'created_at' => Date::now(),
                'updated_at' => Date::now(),
            ],
            [
                'title' => 'Karnataka',
                'visibility' => 1,
                'tb0p5tbs' => 1,
                'created_at' => Date::now(),
                'updated_at' => Date::now(),
            ],
            [
                'title' => 'Kerala',
                'visibility' => 1,
                'tb0p5tbs' => 1,
                'created_at' => Date::now(),
                'updated_at' => Date::now(),
            ],
        ]);
    }
}
