<?php

namespace App\Imports;

use App\Student;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentsImport implements ToModel, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        //
    }

    public function model(array $row)
    {
        return new Student([
            'student_id' => $row['student_id'],
            'name'=> $row['name'],
            'father_name'=> $row['father_name'],
            'nrc_number'=> $row['nrc_number'],
            'phone_no'=> $row['phone_no'],
            'email'=> $row['email'],
            'gender'=> $row['gender'],
            'date_of_birth'=> $row['date_of_birth'],
            'address'=> $row['address'],
            'career_path'=> $row['career_path'],
            'created_emp'=> $row['created_emp'],
            'updated_emp'=> $row['updated_emp'],
            'avatar' => $row['avatar'],
        ]);

        
    }
}
