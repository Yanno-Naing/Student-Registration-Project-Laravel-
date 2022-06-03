<?php

use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $career = ['C++','Java','PHP','Android','React','Laravel'];
        $arr= array();
        $content = array();
        for($i=1; $i<=count($career); $i++){
            $content['id'] = $i;
            $content['name'] = $career[($i-1)];
            $content['created_emp'] = 10001;
            $content['updated_emp'] = 10001;
            $arr[] = $content;
        }
        DB::table('skills')->insert($arr);
    }
}
