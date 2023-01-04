<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $table = 'students';
    protected $primaryKey = 'student_id';


    protected $fillable = ['student_ref', 'lname', 'fname', 'mname', 'sex', 'program_id'];


    public function program(){
        return $this->hasOne(Program::class, 'program_id', 'program_id');
    }


}
