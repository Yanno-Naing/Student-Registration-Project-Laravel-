<?php

namespace App\Interfaces;

interface ClassRepositoryInterface
{
    public function getAllClasses();
    public function getClassesById($classId);
    public function deleteClass($classId);
    public function createClass(array $classDetail);
    public function updateClass(array $classDetail, $classId);
}