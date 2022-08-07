<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;


    protected $table = 'programs';
    protected $primaryKey = 'program_id';


    protected $fillable = ['program_code', 'program_desc', 'active'];

}
