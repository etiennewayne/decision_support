<?php

namespace App\Rules;

use App\Models\AcademicYear;
use App\Models\Schedule;
use Illuminate\Contracts\Validation\Rule;

class FacultyPreparationRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    private $acadyear;
    public function __construct($acadyear)
    {
        //
        $this->acadyear = $acadyear;
    }
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        //

        //count course distinct faculty
        //must only have 4 preparation course
        $countCourse = Schedule::where('faculty_id', $value)
            ->where('schedules.acadyear_id', $this->acadyear->acadyear_id)
            ->join('courses', 'schedules.course_id', 'courses.course_id')
            ->select('course_code')
            ->groupBy('course_code')
            //->distinct()
            ->get();

        $count = $countCourse->count();

        if($count  < 5){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Faculty already reach the maximum preparation.';
    }
}
