<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnrolmentDetail extends Model
{
    use HasFactory;


    protected $table = 'enrolment_details';
    protected $primaryKey = 'enrolment_detail_id';


    protected $fillable = ['enrolment_id', 'schedule_id'];

    public function schedule(){
        return $this->hasOne(Schedule::class, 'schedule_id', 'schedule_id')
            ->with(['course']);
    }

}
