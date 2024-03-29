<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    use HasFactory;

    protected $table = 'faculty';
    protected $primaryKey = 'faculty_id';


    protected $fillable = ['faculty_id', 'lname', 'fname', 'mname', 'sex', 'active'];


    public function schedules(){
        return $this->hasMany(Schedule::class, 'faculty_id', 'faculty_id');
    }

    public function courses_taught(){
        return $this->hasMany(CourseTaught::class, 'faculty_id', 'faculty_id');
    }

}
