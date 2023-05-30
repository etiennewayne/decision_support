<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseTaught extends Model
{
    use HasFactory;

    protected $table = 'courses_taught';
    protected $primaryKey = 'course_taught_id';


    protected $fillable = ['faculty_id','course_code', 'course_desc'];


    public function faculty(){
        $this->belongsTo(Faculty::class, 'faculty_id', 'faculty_id');
    }


}
