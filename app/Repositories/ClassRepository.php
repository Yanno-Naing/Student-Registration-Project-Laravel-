<?php

namespace App\Repositories;

use App\Classes;
use Illuminate\Support\Facades\DB;
use App\Interfaces\ClassRepositoryInterface;

class ClassRepository implements ClassRepositoryInterface
{

    public function getAllClasses()
    {
        return Classes::paginate(10);
    }

    public function createClass(array $classDetail)
    {
        $insert = [
            'name' => $classDetail['name'],
            'limit' => $classDetail['limit'],
        ];

        DB::beginTransaction();
        try {
            Classes::create($insert);
            DB::commit();
            return true;
        } catch(\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function getClassesById($classId)
    {
        $class = Classes::find($classId);

        if(!empty($class)){
            return $class;
        }else{
            return false;
        }
    }

    public function updateClass(array $classDetail, $classId)
    {
        $update = [
            'name' => $classDetail['name'],
            'limit' => $classDetail['limit'],
        ];

        $class = Classes::find($classId);

        if(!empty($class)){
            DB::beginTransaction();
            try {
                $class->update($update);
                DB::commit();
                return true;
            } catch(\Exception $e) {
                DB::rollBack();
                return false;
            }
        }else{
            return false;
        }
    }

    public function deleteClass($classId)
    {
        $class = Classes::find($classId);

        if(!empty($class)){
            DB::beginTransaction();
            try {
                $delete = $class->delete();
                DB::commit();
                return $delete;
            } catch(\Exception $e) {
                DB::rollBack();
                return false;
            }
        }else{
            return false;
        }
    }
}