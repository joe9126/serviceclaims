<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IssuedParts extends Model
{
    use HasFactory;
      /**
    * The database table used by the model.
    *
    * @var string
    */
   protected $table ="issuedparts";
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
    'issuedto',
    'issuedby',
    'issuedon',
    ];

    /**
 * The attributes that should be cast.
 *
 * @var array<string, string>
 */
protected $casts = [
    'issuedon' => 'datetime',
   
 ];
}
