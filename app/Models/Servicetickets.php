<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicetickets extends Model
{
    use HasFactory;

    
    /**
    * The database table used by the model.
    *
    * @var string
    */
   protected $table ="servicetickets";
       /**
    * The attributes that are mass assignable.
    *
    * @var array<int, string>
    */

    protected $fillable = [
        'ticketno',
        'tiecketdate',
        'client',
        'billingrefno',
        'faultreported',
        'urgency',
        'location',
        'personnel',
        'createdby',
        'kmclaim',
        'status',
        ];

     /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
       'ticketdate' => 'datetime'
    ];
}
