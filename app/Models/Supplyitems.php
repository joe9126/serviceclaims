<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\User;
use Illuminate\Support\Facades\Auth;

class Supplyitems extends Model
{
    use HasFactory;

    protected $table ='supplyitems_inventory';
    public $timestamps = true;
  	protected $primaryKey = 'id';
      
    protected static function boot(){
    	parent::boot();
    	static::creating(function($model){
    		$model->createdby = Auth::user()->id;
    	});
    }

    protected $fillable = [
    	'partno','name','unitbp','supplierid','createdby'
    ];
}
