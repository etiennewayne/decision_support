<?php

namespace App\Rules;

use App\Models\Schedule;
use Illuminate\Contracts\Validation\Rule;

class ConflictFacultyRule implements Rule
{
    private $ayid, $startTime, $endTime, $mon, $tue, $wed,$thu, $fri, $sat, $sun,$facultyId;


    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(
        $ayid, $startTime, $endTime,  $mon, $tue, $wed, $thu, $fri, $sat, $sun
    )
    {
        //
        $this->ayid = $ayid;

        $this->startTime = $startTime;
        $this->endTime = $endTime;

        $this->mon = $mon;
        $this->tue = $tue;
        $this->wed = $wed;
        $this->thu = $thu;
        $this->fri = $fri;
        $this->sat = $sat;
        $this->sun = $sun;

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
        $sTime = $this->startTime;
        $eTime = $this->endTime;

        $ayid = $this->ayid;
        $mon = $this->mon;
        $tue = $this->tue;
        $wed = $this->wed;
        $thu = $this->thu;
        $fri = $this->fri;
        $sat = $this->sat;
        $sun = $this->sun;
        $facultyId = $value;

        //check if conflict
        $exists = Schedule::where('faculty_id', $facultyId)
            ->where(function($query) use ($sTime, $eTime){
                $query->whereBetween('start_time', [$sTime, $eTime])
                    ->orWhereBetween('end_time', [$sTime, $eTime]);
            });

        if($mon == 1 || $tue == 1 || $wed == 1 || $thu == 1 || $fri == 1 || $sat == 1 || $sun == 1){
            $exists->where(function($q) use ($mon, $tue,$wed, $thu, $fri, $sat, $sun){
                $mon == 1 ? $q->orWhere('mon', 1) : '';
                $tue == 1 ? $q->orWhere('tue', 1) : '';
                $wed == 1 ? $q->orWhere('wed', 1) : '';
                $thu == 1 ? $q->orWhere('thu', 1) : '';
                $fri == 1 ? $q->orWhere('fri', 1) : '';
                $sat == 1 ? $q->orWhere('sat', 1) : '';
                $sun == 1 ? $q->orWhere('sun', 1): '';
            });
        }

        $exists = $exists->where('acadyear_id', $ayid)
            ->exists();

        if($exists){
            return false;
        }else{
            return true;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Faculty schedule conflict.';
    }
}
