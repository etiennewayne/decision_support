<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Schedule;

class DetectConflictRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    private $ayid, $startTime, $endTime, $mon, $tue, $wed,$thu, $fri, $sat, $sun;

    public function __construct($ayid, $startTime, $endTime,  $mon, $tue, $wed, $thu, $fri, $sat, $sun)
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


        $countExist = Schedule::with('acadyear')
            ->whereHas('acadyear', function($q) use ($ayid){
                $q->where('acadyear_id', $ayid);
            })
            ->where(function($query) use ($sTime, $eTime){
                $query->whereBetween('start_time', [$sTime, $eTime])
                    ->orWhereBetween('end_time', [$sTime, $eTime]);
            });
        
        if($mon == 1 || $tue == 1 || $wed == 1 || $thu == 1 || $fri == 1 || $sat == 1 || $sun == 1){
            $countExist->where(function($q) use ($mon, $tue,$wed, $thu, $fri, $sat, $sun){
                $mon == 1 ? $q->orWhere('mon', 1) : '';
                $tue == 1 ? $q->orWhere('tue', 1) : '';
                $wed == 1 ? $q->orWhere('wed', 1) : '';
                $thu == 1 ? $q->orWhere('thu', 1) : '';
                $fri == 1 ? $q->orWhere('fri', 1) : '';
                $sat == 1 ? $q->orWhere('sat', 1) : '';
                $sun == 1 ? $q->orWhere('sun', 1): '';
            });
        }
    
        $count = $countExist->count();

        if($count > 0){
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
        //return 'The validation error message.';
        return 'Schedule already exist.';
    }
}
