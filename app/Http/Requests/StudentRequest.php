<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|regex:/^([a-zA-Z-\' ]*)$/',
            'father_name' => 'nullable|regex:/^[a-zA-Z-\' ]*$/',
            'nrc_number'=>['required','regex:/(^([0-9]{1,2})\/([A-Z][a-z]|[A-Z][a-z][a-z])([A-Z][a-z]|[A-Z][a-z][a-z])([A-Z][a-z]|[A-Z][a-z][a-z])\([N,P,E]\)[0-9]{6}$)/u'],
            'phone_no' => 'bail|required|regex:/^([0-9]*)$/|digits:11',
            'email' => 'required|email:rfc,dns',
            'gender' => 'required|integer|between:1,2',
            'date_of_birth' => 'required|date',
            'address' => 'nullable',
            'career_path' => 'required|integer|between:1,2',
            'skill' => 'required',
            'skill.*' => 'integer|between:1,6',
            'avatar' => 'nullable|image|mimes:png,jpeg,jpg|max:10240',
            'login_id' => 'required|integer'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([$validator->errors()],422));
    }


}
