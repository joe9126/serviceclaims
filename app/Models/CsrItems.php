<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\User;
use Illuminate\Support\Facades\Auth;

class CsrItems extends Model
{
    use HasFactory;

    protected $table ='csr_items';
    public $timestamps = true;
  /*protected $primaryKey = 'id';
  	public $incrementing = true;*/
       
   /* protected static function boot(){
    	parent::boot();
    	static::creating(function($model){
    		$model->createdby = Auth::user()->id;
    	});
    }*/

    protected $fillable = [
    	'csrno','itemid','qty','unitbp','markup','unitsp','vatrate','itemtotal'
    ];
}
