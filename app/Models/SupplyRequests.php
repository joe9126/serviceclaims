<?php

namespace App\Models;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplyRequests extends Model
{
    use HasFactory;

    protected $table ='supply_requests';
    public $timestamps = true;
    protected $primaryKey = 'id';

    protected static function boot(){
    	parent::boot();
    	static::creating(function($model){
    		$model->createdby = Auth::user()->id;
    	});
    }

    protected $fillable = [
    	'clientid',
        'csrno',
        'description',
        'csrdate',
        'ponumber',
        'podate',
        'currency',
        'csrvalue',
        'soldby',
        'status',
        'createdby'
    ];
}
