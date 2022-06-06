<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\StudentRequest;
use App\Interfaces\StudentRepositoryInterface;

class StudentController extends Controller
{
    private StudentRepositoryInterface $studentRepository;

    public function __construct(StudentRepositoryInterface $studentRepository)
    {
        $this->studentRepository = $studentRepository;
    }
    
    public function register(StudentRequest $request)
    {
        //Log::info($request->all()); dd();

        $studentId = DB::table('students')->latest('student_id')->value('student_id');
        
        if(empty($studentId)){
            $studentId = 10000;
        }else{
            $studentId += 1;
        }
        //dd($studentId);

        if($request->hasFile('avatar')){
            $filenameWithExt = $request->file('avatar')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('avatar')->getClientOriginalExtension();
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            $request->avatar->move(storage_path('app/public/studentavatar'), $fileNameToStore);
            $path = 'studentavatar/'.$fileNameToStore;
        }
        //dd($path ?? null);

        $studentInsert = [
            'student_id' => $studentId,
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

        $studentSkillInsert = [
            'student_id' => $studentId,
            'created_emp'=>$request->login_id,
            'updated_emp'=>$request->login_id,
            'created_at'=>now(),
            'updated_at'=>now(),
        ];
        
        DB::beginTransaction();
        try{
            DB::table('students')->insert($studentInsert);

            #add records to student_skills table
            foreach($request->skill as $val){
                $studentSkillInsert['skill_id'] = $val;
                    //dd($studentSkillInsert['skill_id']);
                DB::table('student_skills')->insert($studentSkillInsert);
            }

            DB::commit();
            return response()->json(['status'=>'OK', 'message'=>'Created Successfully!'],200);
        }catch(\Exception $e){
            DB::rollBack();
            Log::debug($e->getMessage());
            return response()->json(['status'=>'NG','message'=>'Fail to save!'],200);
        }

    }

    public function index()
    {
        $students = DB::table('students')->get();
        Log::info($students);
    }

    public function studentDetailQuerys ( $studentId )
    {
        $studentQuery = DB::table('students')->where('student_id',$studentId);
        $checkdata = $studentQuery->exists();

        if($checkdata){
            $result = $studentQuery->first();
            //Log::info($result);
            $skills = DB::table('student_skills')->where('student_id',$studentId)->pluck('skill_id');
            //Log::info($skills);
            $result->skill = $skills;
            $result->avatar = asset('storage/'.$result->avatar);
            return response()->json(['status'=>'OK', 'data'=>$result],200);

        }else{
            return response()->json(['status'=>'NG','message'=>'Student Record does not exist!'],200);
        }
    }

    public function checkStudentId($studentId){

    }

    public function update(StudentRequest $request, $studentId)
    {
        // if(checkStudentId($studentId)){

        // }else{

        // }
        
        if($request->hasFile('avatar')){
            $filenameWithExt = $request->file('avatar')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('avatar')->getClientOriginalExtension();
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            $request->avatar->move(storage_path('app/public/studentavatar'), $fileNameToStore);
            $path = 'studentavatar/'.$fileNameToStore;
        }

        $updates = [
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

        $studentSkillInsert = [
            'student_id' => $studentId,
            'created_emp'=>$request->login_id,
            'updated_emp'=>$request->login_id,
            'created_at'=>now(),
            'updated_at'=>now(),
        ];

        DB::beginTransaction();
        try {
            DB::table('students')->where('student_id',$studentId)->update($updates);

            #update skill attributes to student_skills table
            $query = DB::table('student_skills')->where('student_id',$studentId);
            $checkdata = $query->exists();
            if($checkdata){
                $query->delete();
            }
            foreach($request->skill as $val){
                $studentSkillInsert['skill_id'] = $val;
                    //dd($studentSkillInsert['skill_id']);
                DB::table('student_skills')->insert($studentSkillInsert);
            }

            DB::commit();
            return response()->json(['status'=>'OK', 'message'=>'Updated Successfully!'],200);
        } catch(\Exception $e){
            DB::rollBack();
            Log::debug($e->getMessage());
            return response()->json(['status'=>'NG','message'=>'Fail to update!'],200);
        }
    }

    public function delete ( $studentId )
    {
        $studentQuery = DB::table('students')->where('student_id',$studentId);
        $checkdata = $studentQuery->exists();

        if($checkdata){
            DB::beginTransaction();
            try {
                $studentQuery->delete();
                DB::table('student_skills')->where('student_id',$studentId)->delete();
                DB::commit();
                return response()->json(['status'=>'OK', 'message'=>'Deleted Successfully!'],200);
            } catch (\Exception $e) {
                DB::rollBack();
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
                    $searchType = 'student_id';
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
                $searchResult = DB::table('students')
                                    ->where('id', 'like' , $searchData."%")
                                    ->orWhere('name', 'like' , $searchData."%")
                                    ->orWhere('email', 'like' , $searchData."%")
                                    ->orWhere('career_path', 'like' , $searchData."%")
                                    ->get();
            }else{
                $searchResult = DB::table('students')->where($searchType, 'like' , $searchData."%")->get();
            }
            

            if($searchResult->isNotEmpty()){
                return response()->json(['status'=>'OK', 'data'=>$searchResult],200);
            }else{
                return response()->json(['status'=>'NG', 'message'=>'No result found!'],200);
            }
        }
    }

    

}
