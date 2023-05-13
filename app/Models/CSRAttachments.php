<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\User;
use Illuminate\Support\Facades\Auth;

class CSRAttachments extends Model
{
    use HasFactory;

    protected $table ='csrattachments';
    public $timestamps = true;
  	protected $primaryKey = 'id'; 

    protected $fillable = [
    	'csrno','filename'
    ];
}
