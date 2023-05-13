<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartsInventory extends Model
{
    
    /**
    * The database table used by the model.
    *
    * @var string
    */
   protected $table ="partsinventory";
   /**
* The attributes that are mass assignable.
*
* @var array<int, string>
*/

protected $fillable = [
    'partno',
    'description',
    'status',
    'addedby',
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
