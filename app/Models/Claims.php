<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Claims extends Model
{
    use HasFactory;


     /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table ="claims";
        /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ticketno',
        'jobcardno',
        'claimno',
        'claimdate',
        'psvfare',
        'accommodation',
        'petties',
        'dinner',
        'lunch',
        'km',
        'kmclaim',
        'laundry',
        'claimstatus',
        'claimamount'
    ];

     /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
       'claimdate' => 'datetime',
    ];
}
