<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ClassRequest;
use App\Interfaces\ClassRepositoryInterface;

/**
 * To handle class data
 * 
 * @author yannaingaung
 * @create 26/05/2022
 */
class ClassController extends Controller
{
    private ClassRepositoryInterface $classRepository;

    public function __construct(ClassRepositoryInterface $classRepository)
    {
        $this->classRepository = $classRepository;
    }
    
    /**
     * Display a listing of the classes.
     *
     * @author yannaingaung
     * @create 26/05/2022
     * @return Response object
     */
    public function index ()
    {
        $result = $this->classRepository->getAllClasses();

        if($result->isNotEmpty()){
            return response()->json(['status'=>'OK', 'data'=> $result], 200);
        }else{
            return response()->json(['status'=>'NG', 'message'=>'No result found!'],200);
        }
    }

    /**
     * Store a newly created category in storage.
     *
     * @author yannaingaung
     * @create 26/05/2022
     * @param  ClassRequest $request
     * @return Response object
     */
    public function store (ClassRequest $request)
    {
        $result = $this->classRepository->createClass($request->input());

        if($result){
            return response()->json(['status'=>'OK', 'message'=> 'Class Record Created Successfully!'], 200);
        }else{
            return response()->json(['status'=>'NG', 'message'=>'Fail to create!'],200);
        }
    }
    
    /**
     * Display class detail.
     *
     * @author yannaingaung
     * @create 26/05/2022
     * @param  int $id
     * @return Response object
     */
    public function classDetailQuerys ($id)
    {
        $result = $this->classRepository->getClassesById($id);

        if($result){
            return response()->json(['status'=>'OK', 'data'=> $result], 200);
        }else{
            return response()->json(['status'=>'NG', 'message'=>'No Record found!'],200);
        }
    }

    /**
     * Update a class information in storage.
     *
     * @author yannaingaung
     * @create 26/05/2022
     * @param  ClassRequest  $request
     * @param  int  $id
     * @return Response object
     */
    public function update (ClassRequest $request, $id)
    {
        $result = $this->classRepository->updateClass($request->input(), $id);

        if($result){
            return response()->json(['status'=>'OK', 'message'=> 'Class Record Updated Successfully!'], 200);
        }else{
            return response()->json(['status'=>'NG', 'message'=>'Fail to update!'],200);
        }
    }

    /**
     * Remove a class from storage.
     *
     * @author yannaingaung
     * @create 26/05/2022
     * @param  int $id
     * @return Response object
     */
    public function delete ($id)
    {
        $result = $this->classRepository->deleteClass($id);

        if($result){
            return response()->json(['status'=>'OK', 'message'=> 'Class Record Deleted Successfully!'], 200);
        }else{
            return response()->json(['status'=>'NG', 'message'=>'Fail to delete!'],200);
        }
    }
}
