<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Servicetickets;

class ServiceticketsController extends Controller
{
    //
    public function index(){
        $tickets = DB::table('servicetickets')
        ->select(
            "servicetickets.faultreported",
            "servicetickets.ticketno",
            "servicetickets.ticketdate",
            "servicetickets.location",
            "servicetickets.status",
            "servicetickets.urgency",
            "servicetickets.billingrefno",
            "clients.clientname",
            "users.name",
            "clients.clientname",
            )
        ->leftjoin("clients","servicetickets.client","=","clients.id")
       ->leftjoin("users","servicetickets.personnel","=","users.id")
       ->where("servicetickets.personnel",Auth::user()->id)
        ->orderBy("servicetickets.ticketdate","desc")
        ->get();
     // dump($tickets);
        return view('service.tickets',['tickets'=>$tickets]);
    }


}
