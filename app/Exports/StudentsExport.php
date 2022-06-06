<?php

namespace App\Exports;

use App\Student;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class StudentsExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $result =Student::with(['skills'=>function($query){
            $query->select('skills.name');
        }])->get();

        foreach($result as $row){
            $arr = array();
            //Arr::pluck($row, 'skills.name')
            foreach($row['skills'] as $skill){
                $arr[] = $skill['name'];
            }
            $row['skills'] = $arr;
        }
        return $result;
    }

    public function headings(): array
    {
        return [
            "id","student_id", "name", "father_name","nrc_number","phone_no","email","gender","date_of_birth","avatar","address","career_path","deleted_at","created_emp","updated_emp","created_at","updated_at","skills"
        ];
    }
}
