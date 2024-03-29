<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $table = 'schedules';
    protected $primaryKey = 'schedule_id';


    protected $fillable = ['acadyear_id', 'program_id',
        'course_id', 'room_id','start_time', 'end_time', 'faculty_id',
        'mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'
    ];


    public function acadyear(){
        return $this->hasOne(AcademicYear::class, 'acadyear_id', 'acadyear_id');
    }

    public function program(){
        return $this->hasOne(Program::class, 'program_id', 'program_id');
    }

    public function course(){
        return $this->hasOne(Course::class, 'course_id', 'course_id');
    }

    public function room(){
        return $this->hasOne(Room::class, 'room_id', 'room_id');
    }

    public function faculty(){
        return $this->hasOne(Faculty::class, 'faculty_id', 'faculty_id');
    }

    public function institute(){
        return $this->hasOne(Institute::class, 'institute_id', 'institute_id');
    }


}
