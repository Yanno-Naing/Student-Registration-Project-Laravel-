<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->integer('student_id')->nullable(false);
            $table->string('name', 255)->nullable(false);
            $table->string('father_name', 255)->nullable();
            $table->string('nrc_number', 40)->nullable();
            $table->string('phone_no', 30)->nullable();
            $table->string('email', 255)->nullable(false);
            $table->tinyInteger('gender')->length(3)->comment('1 for Male, 2 for Female')->nullable(false)->default('1');
            $table->date('date_of_birth')->nullable();
            $table->string('avatar', 255)->nullable();
            $table->string('address', 500)->nullable();
            $table->tinyInteger('career_path')->length(3)->comment('1: Front End, 2: Back End')->nullable()->default('1');
            $table->softDeletes();
            $table->integer('created_emp');
            $table->integer('updated_emp');
            $table->timestamp('created_at')->nullable(false)->useCurrent();
            $table->timestamp('updated_at')->nullable(false)->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('students');
    }
}
