<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClaimstoPrint extends Model
{
    use HasFactory;
  /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table ="claimstoprint";
        /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'userid',
        'ticketno',
        'jobcardno',
        'billingrefno',
        'client',
        'task',
        'location',
        'date',
        'time',
        'amount'
    ];
}
