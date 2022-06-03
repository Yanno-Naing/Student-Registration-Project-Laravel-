<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model
{
    protected $guarded = [];

    protected $primaryKey = 'teacher_id';

    use SoftDeletes;

    public function skills(){
        return $this->belongsToMany('App\Skill', 'teachers_skill', 'teacher_id', 'skill_id');
    }

}
