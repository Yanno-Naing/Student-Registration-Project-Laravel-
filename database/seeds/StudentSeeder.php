<?php

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $arr = array();

        for ($i=0; $i<10; $i++){
            $arr[] = [
                'student_id' => 10001,
                'name' => Str::random(10),
                'father_name' => Str::random(10),
                'nrc_number' => '7/KaTaKha(N)16837',
                'phone_no' => '09123456789',
                'email' => Str::random(10).'@gmail.com',
                'gender' => 1,
                'date_of_birth' => date('Y-m-d H:i:s',mktime(0,0,0,5,17,2000)),
                'address' => Str::random(20),
                'career_path' => 3,
                'created_emp' => 10001,
                'updated_emp' => 10001,
                
            ];
        }

        DB::table('students')->insert($arr);
    }
}
