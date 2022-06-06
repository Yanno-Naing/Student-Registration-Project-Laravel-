<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $primaryKey = 'student_id';

    public function skills(){
        return $this->belongsToMany('App\Skill', 'student_skills', 'student_id', 'skill_id');
    }

}
