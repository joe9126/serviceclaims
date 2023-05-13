<?php

namespace App\Http\Controllers;

use Validator,Response,Redirect;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Servicetickets;
use App\Models\Serviceentries;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Exception;
class ServiceticketsController extends Controller
{
    //
    public function index(){
        if(Auth::user()->role=="Admin"){
            $tickets = DB::table('servicetickets')
            ->select(
                "servicetickets.faultreported",
                "servicetickets.ticketno",
                DB::raw("IFNULL(serviceentries.jobcardno,'no update') as jobcardno"),
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
            ->leftjoin("serviceentries","serviceentries.ticketno","=","servicetickets.ticketno")
           ->leftjoin("users","servicetickets.personnel","=","users.id")
            ->orderBy("servicetickets.ticketdate","desc")
            ->get();
        }else{
        $tickets = DB::table('servicetickets')
        ->select(
            "servicetickets.faultreported",
            "servicetickets.ticketno",
            DB::raw("IFNULL(serviceentries.jobcardno,'no update') as jobcardno"),
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
        ->leftjoin("serviceentries","serviceentries.ticketno","=","servicetickets.ticketno")
       ->leftjoin("users","servicetickets.personnel","=","users.id")
       ->where("servicetickets.personnel",Auth::user()->id)
        ->orderBy("servicetickets.ticketdate","desc")
        ->get();
        }
     // dump($tickets);
        return view('service.tickets',['tickets'=>$tickets]);
    }

    public function showTicket(Request $request){
        $ticket = DB::table('servicetickets')
        ->select(
            "servicetickets.faultreported",
            "servicetickets.ticketdate",
            "servicetickets.ticketno",
            "servicetickets.location",
            DB::raw("IFNULL(serviceentries.jobcardno,'no update') as jobcardno"),
            DB::raw("IFNULL(serviceentries.start_time,'no update') as start_time"),
            DB::raw("IFNULL(serviceentries.end_time,'no update') as end_time"),
            DB::raw("IFNULL(serviceentries.serialno,'no update') as serialno"),
            DB::raw("IFNULL(serviceentries.model,'no update') as model"),
            DB::raw("IFNULL(serviceentries.city,'no update') as site"),
            DB::raw("IFNULL(serviceentries.findings,'no update') as findings"),
            DB::raw("IFNULL(serviceentries.action_taken,'no update') as action_taken"),
            DB::raw("IFNULL(serviceentries.recommendations,'no update') as recommendations"),
            DB::raw("IFNULL(serviceentries.attachment,'no file found') as attachment"),
            "users.name",
            "clients.clientname",
            )
        ->leftjoin("serviceentries","serviceentries.ticketno","=","servicetickets.ticketno")
        ->leftjoin("clients","servicetickets.client","=","clients.id")
       ->leftjoin("users","servicetickets.personnel","=","users.id")
       ->where("servicetickets.ticketno",$request->ticketno) //->toSql();
        ->get();
          //  dump($ticket);
        return $ticket;
    }

    
    public function updateTicket(Request $request){
      
        try{
            $response = Serviceentries::updateOrCreate(
                ['ticketno'=>$request->ticketno, 'jobcardno'=>$request->jobcardno],
                ['ticketno'=>$request->ticketno,
                'jobcardno'=>$request->jobcardno,
                'servicedate'=>$request->start_time,
                'start_time'=>$request->start_time,
                'end_time'=>$request->end_time,
                'model'=>$request->model,
                'serialno'=>$request->serialno,
                'city'=>$request->city,
                'findings'=>$request->findings,
                'action_taken'=>$request->action_taken,
                'recommendations'=>$request->recommendations,
                'updatedby'=>Auth::user()->id,
                'attachment'=>$request->filename,]
            );
            $response = Servicetickets::where("ticketno",$request->ticketno)
            ->update(
               ["status"=>$request->status]
            );
    
            if( $response){
                if ($request->hasFile('attachment')) {
                    $attachment = $request->file('attachment');
                    $filename = $request->filename.'.'.$attachment->getClientOriginalExtension();
                     $destinationPath = base_path('Uploads');
                     $filecheck = $destinationPath.'/'.$request->filename;
    
                        if(Storage::exists($filecheck)){
                            Storage::delete($filecheck);
                        }
                          $response = $attachment->move($destinationPath,$request->filename);
                    }
            }
            if($response){
                return response()->json([
                    'success'=>true,
                    'message'=>'Your service ticket updated successfully.'
                ],200);
            }
            else{
                return response()->json([
                    'success'   =>  false,
                    'message'   =>  'An error occured. Could not update the service ticket.'
                ], 200);
            }

        }
        catch(Exception $except){
            return response()->json([
                'success'   =>  false,
                'message'   =>  'An error occured. Possible duplicate job card number.'
            ], 200);
        }    
            
    }

   //CHECK IF FILE  EXISTS BEFORE DOWNLOAD
    public function checkFile(Request $request){
        $file =  base_path('Uploads').'/'.$request->filename;
        if(File::exists($file)){

                return response()->json([
                    'success'   =>  true,
                    'message'   =>  'Download successful.'
                ], 200);

        }
        else{
            return response()->json([
                'success'   =>  false,
                'message'   =>'Attachment not found. Click attachment link to download.'
            ], 200);
          }
    }

public function  downloadFile(Request $request){
    $file =  base_path('Uploads').'/'.$request->filename;
	$headers = [
			'Content-Type: application/pdf',
	];
	return response()->download($file,$request->filename,$headers);

  }


  public function partsInventory(){
    return view("toolsandparts.partsinventory");
}

    }

