<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Validator,Response,Redirect;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Session;
use Illuminate\Support\Facades\DB;
use App\Models\Supplyitems;

class SupplyItemsController extends Controller
{
       public function __construct() {
        $this->middleware('auth')->except(['logout','authenticateuser']);
    }

 public function create(){
    	
    }

 public function store(){
    	
    }

 public function show(Supplyitems $items){
 		$items = Supplyitems::all();    			
    	return $items;
    }



 public function filter(Request $request){
 	$result = Supplyitems::where("name","=",$request->searchphrase)
 				->get();
 	return $result;
 } 

 public function update(){
    	
    }
 public function delete(){
    	
    }
}
