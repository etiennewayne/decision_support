<?php

namespace App\Rules;

use App\Models\Schedule;
use Illuminate\Contracts\Validation\Rule;

class FacultyMaxUnitRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    private $acadyear;

    private $schedule_id;

    public function __construct($acadyear, $schedule_id)
    {
        //
        $this->acadyear = $acadyear;
        $this->schedule_id = $schedule_id;
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

        $countUnits = Schedule::where('faculty_id', $value)
            ->where('schedules.acadyear_id', $this->acadyear->acadyear_id)
            ->join('courses', 'schedules.course_id', 'courses.course_id')
            ->sum('course_unit');

        $scheduleUnit = Schedule::join('courses', 'schedules.course_id', 'courses.course_id')
            ->where('schedules.schedule_id',  $this->schedule_id)
            ->select('courses.course_unit')
            ->first();


        //return $countUnits;
        if(($countUnits + $scheduleUnit->course_unit) <= 24){
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
        return 'Faculty no. of unit exceed.';
    }
}
