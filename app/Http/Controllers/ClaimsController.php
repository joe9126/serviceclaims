<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Claims;
use App\Models\Servicetickets;
use App\Models\ClaimstoPrint;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
//use PDF;
use Carbon\Carbon;

class ClaimsController extends Controller
{
    public function index(){ //route: /ticketclaims
        $ticketclaims =  Servicetickets::select(
            "servicetickets.faultreported",
            "servicetickets.ticketno",
            "servicetickets.ticketdate",
            "servicetickets.location",
            "servicetickets.status",
            "clients.clientname",
            "serviceentries.start_time",
            "serviceentries.end_time",
            "users.name",
            "claims.claimstatus",
            "claims.claimdate",
            "claims.claimamount",
            "claims.claimno"
            )
        ->leftjoin("claims","servicetickets.ticketno","=","claims.ticketno")
        ->leftjoin("serviceentries","servicetickets.ticketno","=","serviceentries.ticketno")
        ->leftjoin("clients","servicetickets.client","=","clients.id")
        ->leftjoin("users","servicetickets.personnel","=","users.id")
        ->orderBy("servicetickets.ticketdate","desc")
        ->where("servicetickets.personnel","=",Auth::user()->id)->get();
      // ->paginate(10);
       // dump( $ticketclaims);
        return view('mileage.ticketclaims',['ticketclaims'=> $ticketclaims]);
    }

    public function showclaim(Request $request){
        $claimdetails = Servicetickets::select(
            "servicetickets.ticketno",
            "servicetickets.ticketdate",
            "servicetickets.location",
            DB::raw('IFNULL(claims.claimno,"NA") as claimno'),
            DB::raw('IFNULL(claims.psvfare,"0") as psvfare'),
            DB::raw('IFNULL(claims.accommodation,"0") as accommodation'),
            DB::raw('IFNULL(claims.petties,"0") as petties'),
            DB::raw('IFNULL(claims.dinner,"0") as dinner'),
            DB::raw('IFNULL(claims.lunch,"0") as lunch'),
            DB::raw('IFNULL(claims.km,"0") as km'),
            DB::raw('IFNULL(claims.kmclaim,"0") as kmclaim'),
            DB::raw('IFNULL(claims.laundry,"0") as laundry'),
            DB::raw('IFNULL(claims.others,"0") as others'),
            "claims.others",
            "claims.claimstatus",
            DB::raw('IFNULL(claims.claimamount,"0") as claimamount'),
           
             "clients.clientname",
        )
        ->leftjoin("claims","servicetickets.ticketno","=","claims.ticketno")
        ->leftjoin("clients","servicetickets.client","=","clients.id")
       ->leftjoin("users","servicetickets.personnel","=","users.id")
       ->where("servicetickets.ticketno","=",$request->ticketno)
       ->where("users.id","=",Auth::id()) //->toSql();
       ->get();
       // dump($claimdetails);
          return $claimdetails;
    }

    public function store(Request $request){ //route claims/claimupdate
       $response =  Claims::updateOrCreate(
                        ['ticketno'=>$request->ticketno],
                        ['ticketno'=>$request->ticketno,
                        'psvfare'=>$request->psvfare,
                        'accommodation'=>$request->accommodation,
                        'petties'=>$request->petties,
                       'dinner'=>$request->dinner,
                       'lunch'=>$request->lunch,
                       'km'=>$request->km,
                       'kmclaim'=>floatval($request->kmclaim),
                       'claimamount'=>$request->claimtotal,
                       'claimno'=>$request->claimno,
                       'claimstatus'=>"Unclaimed"]
       );

        if($response){
            return response()->json([
                'success'=>true,
                'message'=>'Your claim updated successfully.'
            ],200);
        }
        else{
            return response()->json([
                'success'   =>  false,
                'message'   =>  'Could not update the claim.'
            ], 200);
        }
    }

    public function getClaims(){ //route: /claims/print
        $ticketclaims = Servicetickets::select(
            "servicetickets.faultreported",
            "servicetickets.ticketno",
            "serviceentries.servicedate",
            "servicetickets.location",
            "servicetickets.billingrefno",
            "serviceentries.start_time",
            "serviceentries.end_time",
            "serviceentries.jobcardno",
            "clients.clientname",
            "users.name",
            "claims.claimstatus",
            "claims.claimdate",
            "claims.claimamount"
            )
        ->leftjoin("claims","servicetickets.ticketno","=","claims.ticketno")
        ->leftjoin("serviceentries","claims.ticketno","=","serviceentries.ticketno")
        ->leftjoin("clients","servicetickets.client","=","clients.id")
       ->leftjoin("users","servicetickets.personnel","=","users.id")
       ->where("users.id","=",Auth::id())
       ->where("claims.claimstatus","=","Unclaimed")
      ->where("claims.claimamount",">",0)
       ->orderBy("servicetickets.ticketdate","desc")->get();
      // ->paginate(10);
           // dump([$ticketclaims]);
        return view('mileage.printclaims',['claims'=>$ticketclaims]);
       // echo $ticketclaims;
    }

    public function deleteClaim(Request $request){
       $response = //[$request->ticketno]
       DB::table("claims")->where("ticketno","=",$request->ticketno)->delete();
      //  Claims::where("ticketno",$request->ticketno)->orderBy("ticketno","desc")->toSql();

                           if($response){
                                return response()->json([
                                    'success'=>true,
                                    'message'=>'Selected claims deleted successfully.'
                                ],200);
                            }
                            else{
                                return response()->json([
                                    'success'   =>  false,
                                    'message'   =>  'Could not delete the claims.'
                                ], 200);
                            }
                           // return response()->json($response);
                           //dump($response);


    }

 public function tempstoreclaimPrint(Request $request){ //route:post  printpreviewstore //store the print data temporarily to db
       
       $claimdata = json_decode($request->get("claimsdata"));
      // dump( $claimdata);

     $i = count($claimdata);
    
      ClaimstoPrint::where('userid',Auth::user()->id)->delete();
  
 
        for( $j=0;$j<$i;$j++) {
            $response =  ClaimstoPrint::updateOrCreate(
               ['ticketno'=>$claimdata[$j]->ticketno],//update where or insert
   
               [ 'userid'=>Auth::user()->id,
               'ticketno'=>$claimdata[$j]->ticketno,
               'jobcardno'=>$claimdata[$j]->jobcardno,
               'billingrefno'=>$claimdata[$j]->billingrefno,
               'client'=>$claimdata[$j]->client,
               'task'=>$claimdata[$j]->task,
               'location'=>$claimdata[$j]->location,
              'date'=>$claimdata[$j]->date,
              'time'=>$claimdata[$j]->time,
              'amount'=>$claimdata[$j]->amount          
              ]
           );
          }
    
     
    

        if($response){
            return response()->json([
                'success'=>true,
                'message'=>'Preparing the print preview...'
            ],200);
        }
        else{
            return response()->json([
                'success'   =>  false,
                'message'   =>  'Could not prepare print preview.'
            ], 200);
        }
  
    }




public function printPreview(Request $request){
        $claimsdata = ClaimstoPrint::all();  //retrieve all data
                        //->where('userid',Auth::user()->id);
 
      return view('mileage.printpreview',compact('claimsdata'));
    }


public function resetprintClaims(Request $request){
$tickets = ClaimstoPrint::select("ticketno","jobcardno")
            ->where("userid",Auth::user()->id)
            ->get();


foreach($tickets as $ticket){
    $response = Claims::where("ticketno",$ticket->ticketno)
                    ->update(
       
                        [ 'claimno'=> $request->claimno,
                          'claimdate'=>Carbon::now()->format('Y-m-d'),
                          'claimstatus'=>"Claimed"
                          ]
                    );
}

if($response){
    $response = ClaimstoPrint::where("userid",Auth::user()->id)->delete();
}
   
    if($response){
        return response()->json([
            'success'=>true,
            'message'=>'Print successful.'
        ],200);
    }
    else{
        return response()->json([
            'success'   =>  false,
            'message'   =>  'Could not print.'
        ], 200);
    }
}


public function dashInfo(){
    $dashinfo = [];
   $claimstotal = Claims::select(DB::raw("SUM(claimamount) as totalclaim"))
                        ->leftjoin("servicetickets","servicetickets.ticketno","=","claims.ticketno")
                         ->where("claimstatus","Unclaimed")
                         ->where("servicetickets.personnel",Auth::user()->id)
                         ->groupBy("servicetickets.personnel")
                         ->first();
           array_push($dashinfo, $claimstotal);

   $unupdatedclaims = DB::table("servicetickets")
                        ->select(
                            DB::raw("COUNT(servicetickets.ticketno) as unupdatedclaims")  
                        )->rightjoin("claims","servicetickets.ticketno","=","claims.ticketno")
                        ->where("servicetickets.personnel",Auth::user()->id)
                        ->where("claims.claimamount","=","0")
                        ->groupBy("servicetickets.personnel")
                        ->first();
                  array_push($dashinfo, $unupdatedclaims);

    $pendingtickets = DB::table("servicetickets")->select(
                          DB::raw("COUNT(ticketno) as pendingtickets")  
                        )
                        ->where("status","pending")
                        ->where("personnel",Auth::user()->id)
                        ->groupBy("personnel")
                        ->first();
                        array_push($dashinfo, $pendingtickets);

              // dump($dashinfo);
                        return $dashinfo;
}
}
