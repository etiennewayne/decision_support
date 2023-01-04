<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrolment extends Model
{
    use HasFactory;


    protected $table = 'enrolments';
    protected $primaryKey = 'enrolment_id';


    protected $fillable = ['acadyear_id', 'student_id', 'program_id'];

    public function enrolment_details(){
        return $this->hasMany(EnrolmentDetail::class, 'enrolment_id', 'enrolment_id')
            ->with(['schedule']);
    }

    public function academic_year(){
        return $this->hasOne(AcademicYear::class, 'acadyear_id', 'acadyear_id');
    }

    public function student(){
        return $this->hasOne(Student::class, 'student_id', 'student_id')
            ->with(['program']);
    }

    public function program(){
        return $this->hasOne(Program::class, 'program_id', 'program_id');
    }

}
