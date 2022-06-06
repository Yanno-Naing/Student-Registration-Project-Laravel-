<?php

use Illuminate\Database\Seeder;

class ClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arr = array();

        for ($i=0; $i<5; $i++){
            $arr[] = [
                'name' => Str::random(10),
                'limit' => 30,
            ];
        }

        DB::table('classes')->insert($arr);
    }
}
