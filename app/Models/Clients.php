<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clients extends Model
{
    use HasFactory;

     /**
    * The database table used by the model.
    *
    * @var string
    */
   protected $table ="clients";
   /**
* The attributes that are mass assignable.
*
* @var array<int, string>
*/

protected $fillable = [
    'clientname ',
    'address',
    'phone',
    'addedby',
    'city',
    'contactperson',
    'email',
    'secemail',
    'createdby'
    ];

     /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
     ];
}
