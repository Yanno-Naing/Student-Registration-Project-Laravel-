<?php

namespace App\Http\Controllers;

use App\Teacher;
use App\TeachersSkill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\TeacherRequest;

class TeacherController extends Controller
{

    public function register(TeacherRequest $request)
    {
            //Log::info($request->all()); dd();

        $teacherId = Teacher::latest('teacher_id')->value('teacher_id');
        
        if(empty($teacherId)){
            $teacherId = 10000;
        }else{
            $teacherId += 1;
        }
            //dd($studentId);

        if($request->hasFile('avatar')){
            $filenameWithExt = $request->file('avatar')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('avatar')->getClientOriginalExtension();
            $fileNameToStore = $filename.'_'.time().'.'.$extension;

            $request->avatar->move(storage_path('app/public/teacheravatar'), $fileNameToStore);
            $path = 'teacheravatar/'.$fileNameToStore;
        }
        //dd($path ?? null);

        $teacherInsert = [
            'teacher_id' => $teacherId,
            'name'=>$request->name,
            'father_name'=>$request->father_name,
            'nrc_number'=>$request->nrc_number,
            'phone_no'=>$request->phone_no,
            'email'=>$request->email,
            'gender'=>$request->gender,
            'date_of_birth'=>$request->date_of_birth,
            'address'=>$request->address,
            'career_path'=>$request->career_path,
            'created_emp'=>$request->login_id,
            'updated_emp'=>$request->login_id,
            'avatar' => $path ?? null,
            'created_at'=>now(),
            'updated_at'=>now(),
        ];

        $teacherSkillInsert = [
            'teacher_id' => $teacherId,
            'created_emp'=>$request->login_id,
            'updated_emp'=>$request->login_id,
            'created_at'=>now(),
            'updated_at'=>now(),
        ];
        
        DB::beginTransaction();
        try{
            Teacher::create($teacherInsert);

            #add records to student_skills table
            foreach($request->skill as $val){
                $teacherSkillInsert['skill_id'] = $val;
                TeachersSkill::create($teacherSkillInsert);
            }

            DB::commit();
            return response()->json(['status'=>'OK', 'message'=>'Created Successfully!'],200);
        }catch(\Exception $e){
            DB::rollBack();
            Log::debug($e->getMessage());
            return response()->json(['status'=>'NG','message'=>'Fail to save!'],200);
        }

    }

    public function teacherDetailQuerys ( $teacherId )
    {
        $teacherQuery = Teacher::find($teacherId);

        if(!empty($teacherQuery)){
            $result = $teacherQuery->with(['skills' => function($query){
                    $query->select('skills.name as skill_name','skills.id as skill_id');
                }])->first();
                //dd($result);
            $result->avatar = asset('storage/'.$result->avatar);
            
            return response()->json(['status'=>'OK', 'data'=>$result],200);
        }else{
            return response()->json(['status'=>'NG','message'=>'Student Record does not exist!'],200);
        }
    }

    public function index ()
    {
        $result = Teacher::paginate(10);

        if($result->isNotEmpty()){
            return response()->json(['status'=>'OK', 'data'=>$result],200);
        }else{
            return response()->json(['status'=>'NG', 'message'=>'No result found!'],200);
        }
    }

    public function update(TeacherRequest $request, $teacherId)
    {
            //Log::info($request->all()); dd();
        $teacherQuery = Teacher::find($teacherId);

        if(!empty($teacherQuery)){

            if($request->hasFile('avatar')){
                $filenameWithExt = $request->file('avatar')->getClientOriginalName();
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension = $request->file('avatar')->getClientOriginalExtension();
                $fileNameToStore = $filename.'_'.time().'.'.$extension;
    
                $request->avatar->move(storage_path('app/public/teacheravatar'), $fileNameToStore);
                $path = 'teacheravatar/'.$fileNameToStore;
            }
            //dd($path ?? null);
    
            $teacherInsert = [
                'name'=>$request->name,
                'father_name'=>$request->father_name,
                'nrc_number'=>$request->nrc_number,
                'phone_no'=>$request->phone_no,
                'email'=>$request->email,
                'gender'=>$request->gender,
                'date_of_birth'=>$request->date_of_birth,
                'address'=>$request->address,
                'career_path'=>$request->career_path,
                'updated_emp'=>$request->login_id,
                'avatar' => $path ?? null,
                'updated_at'=>now(),
            ];
    
            $teacherSkillInsert = [
                'teacher_id' => $teacherId,
                'created_emp'=>$request->login_id,
                'updated_emp'=>$request->login_id,
                'created_at'=>now(),
                'updated_at'=>now(),
            ];
            
            DB::beginTransaction();
            try{
                $teacherQuery->update($teacherInsert);
    
                #add records to student_skills table
                $query = TeachersSkill::where('teacher_id',$teacherId);
                if(!empty($query)){
                    $query->delete();
                }
                foreach($request->skill as $val){
                    $teacherSkillInsert['skill_id'] = $val;
                        //dd($studentSkillInsert['skill_id']);
                    TeachersSkill::create($teacherSkillInsert);
                }
    
                DB::commit();
                return response()->json(['status'=>'OK', 'message'=>'Updated Successfully!'],200);
            }catch(\Exception $e){
                DB::rollBack();
                Log::debug($e->getMessage());
                return response()->json(['status'=>'NG','message'=>'Fail to update!'],200);
            }
        }else{
            return response()->json(['status'=>'NG','message'=>'Student Record does not exist!'],200);
        }

    }

    public function delete($teacherId)
    {
        $teacherQuery = Teacher::find($teacherId);
        //dd($teacherQuery);
        if(!empty($teacherQuery)){
            try {
                 //dd($teacherQuery);
                $teacherQuery->delete();
                TeachersSkill::where('teacher_id',$teacherId)->delete();

                return response()->json(['status'=>'OK', 'message'=>'Deleted Successfully!'],200);
            } catch (\Exception $e) {
                Log::debug($e->getMessage());
                return response()->json(['status'=>'NG','message'=>'Fail to delete!'],200);
            }
        }else{
            return response()->json(['status'=>'NG','message'=>'Student Record does not exist!'],200);
        }
    }

    public function search ( Request $request )
    {
        if($request->has('searchData') && $request->has('searchType')){
            $searchData = $request->searchData;
            switch($request->searchType){
                case "0":
                    $searchType = 'all';
                break;
                case "1":
                    $searchType = 'teacher_id';
                break;
                case "2":
                    $searchType = 'name';
                break;
                case "3":
                    $searchType = 'email';
                break;
                case "4":
                    $searchType = 'career_path';
                break;
                default:
                    $searchType = 'all';
            }

            if($searchType === 'all'){
                $searchResult = Teacher::where('teacher_id', 'like' , $searchData."%")
                                    ->orWhere('name', 'like' , $searchData."%")
                                    ->orWhere('email', 'like' , $searchData."%")
                                    ->orWhere('career_path', 'like' , $searchData."%")
                                    ->paginate(10);
            }else{
                $searchResult = Teacher::where($searchType, 'like' , $searchData."%")->paginate(10);
            }

            if($searchResult->isNotEmpty()){
                return response()->json(['status'=>'OK', 'data'=>$searchResult],200);
            }else{
                return response()->json(['status'=>'NG', 'message'=>'No result found!'],200);
            }
        }
    }

}
