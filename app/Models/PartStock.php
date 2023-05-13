<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartStock extends Model
{
    use HasFactory;

     /**
    * The database table used by the model.
    *
    * @var string
    */
   protected $table ="partstock";
   /**
* The attributes that are mass assignable.
*
* @var array<int, string>
*/

protected $fillable = [
    'client',
    'partno',
    'quantity',
    ];

 /**
 * The attributes that should be cast.
 *
 * @var array<string, string>
 */

}
