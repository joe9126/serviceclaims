<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Serviceentries extends Model
{
    use HasFactory;


    /**
    * The database table used by the model.
    *
    * @var string
    */
   protected $table ="serviceentries";
       /**
    * The attributes that are mass assignable.
    *
    * @var array<int, string>
    */

    protected $fillable = [
        'ticketno',
        'jobcardno',
        'servicedate',
        'start_time',
        'end_time',
        'model',
        'serialno',
        'city',
        'findings',
        'action_taken',
        'recommendations',
        'attachment',
        'updatedby',
        ];

     /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
       'servicedate' => 'date',
       'start_time' => 'datetime',
       'end_time' => 'datetime',
    ];
}
