<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddedParts extends Model
{
      /**
    * The database table used by the model.
    *
    * @var string
    */
   protected $table ="addedparts";
   /**
* The attributes that are mass assignable.
*
* @var array<int, string>
*/

protected $fillable = [
    'client',
    'partno',
    'quantity',
    'refno',
    'ticketno',
    'receivedby',
    'receivedon',
    ];

 /**
 * The attributes that should be cast.
 *
 * @var array<string, string>
 */
protected $casts = [
   'receivedon' => 'datetime',
  
];
}
