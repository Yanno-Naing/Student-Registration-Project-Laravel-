<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\StudentsExport;
use App\Imports\StudentsImport;
use Maatwebsite\Excel\Facades\Excel;

class ExcelController extends Controller
{
    public function importExportView()
    {
       return view('import');
    }
   
    public function export() 
    {
        return Excel::download(new StudentsExport, 'students.xlsx');
    }
   
    public function import() 
    {
        Excel::import(new StudentsImport,request()->file('excel_file'));
           
        return response()->json(['status'=>'OK', 'message'=>'Success']);
    }
}
