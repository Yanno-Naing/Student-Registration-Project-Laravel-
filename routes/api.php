<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });



Route::get('/students', 'StudentController@index');

Route::post('/students/register', 'StudentController@register');

Route::get('/students/detail/{studentId}', 'StudentController@studentDetailQuerys');

Route::get('/students/edit/{studentId}', 'StudentController@studentDetailQuerys');

Route::post('/students/update/{studentId}', 'StudentController@update');

Route::delete('/students/delete/{studentId}', 'StudentController@delete');

Route::get('/students/search', 'StudentController@search');


Route::get('/teachers', 'TeacherController@index');

Route::post('/teachers/register', 'TeacherController@register');

Route::get('/teachers/detail/{teacherId}', 'TeacherController@teacherDetailQuerys');

Route::put('/teachers/update/{teacherId}', 'TeacherController@update');

Route::delete('/teachers/delete/{teacherId}', 'TeacherController@delete');

Route::get('/teachers/search', 'TeacherController@search');


# Testing Repository Patten Class sample

Route::get('/classes', 'ClassController@index');

Route::post('/classes/register', 'ClassController@store');

Route::put('/classes/update/{classId}', 'ClassController@update');

Route::get('/classes/detail/{classId}', 'ClassController@classDetailQuerys');

Route::delete('/classes/delete/{classId}', 'ClassController@delete');


#Excel export and import

Route::get('export', 'ExcelController@export')->name('export');
Route::get('importExportView', 'ExcelController@importExportView');
Route::post('import', 'ExcelController@import')->name('import');