<?php

namespace App\Http\Controllers;
use App\Models\SupplyRequests;
use App\Models\Supplyitems;
use App\Models\CsrItems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\CSRAttachments;
use Illuminate\Support\Facades\Storage;
/*use Illuminate\Support\Facades\Mail;*/
use Mail;

class SupplyrequestsController extends Controller
{
    public $emaildata;

    public function index(){

		$csrs = SupplyRequests::from('supply_requests')
                                ->leftjoin('clients','supply_requests.clientid','=','clients.id')
	                            ->leftjoin('users as salespersons','supply_requests.soldby','=','salespersons.id')
	                            ->leftjoin('users as creators','supply_requests.createdby','=','creators.id')
	                            ->select(
                                    'clients.id',
                                    'clients.clientname',
                                    'supply_requests.csrno',
                                    'supply_requests.description',
                                    'supply_requests.csrdate',
                                    'supply_requests.ponumber',
                                    'supply_requests.podate',
                                    'supply_requests.currency',
                                    'supply_requests.csrvalue',
                                    'salespersons.name as saleperson',
                                    'supply_requests.status',
                                    'creators.name as csrcreator'
                                    )
	                            ->orderBy('supply_requests.csrno','desc')
	                            ->get();

       return view("supplyrequests.supplyrequests",["csrs"=>$csrs]);
    }

/**
 * SEARCH CSR ITEMS
 */
public function searchItems(Request $request){
        $items = CsrItems::where('csr_items.csrno','=',$request->csrno)
								->select("csr_items.id","csr_items.itemid","supplyitems_inventory.partno","supplyitems_inventory.name","csr_items.qty","csr_items.unitbp","csr_items.markup","csr_items.unitsp","csr_items.vatrate","csr_items.itemtotal")
								->leftjoin("supplyitems_inventory","csr_items.itemid","=","supplyitems_inventory.partno")
								->orderBy("supplyitems_inventory.name","asc")
								->get();
		return $items;
    }

/**
 * COUNT CSRs
 */
 public function countCSR(Request $request){
        $requests = SupplyRequests::whereYear('created_at', '=', Carbon::now()->format('Y'))->select('csrno')->latest()->first();
		 $csrno=0;
		if(!empty($requests)){
		     $csrno= $requests->csrno;
		}else{
		    $csrno=0;
		}

	return	response()->json(["csrno"=>$csrno]);
    }

/***
 * COUNT CSR
 */
public function checkCSR(Request $request){

    $requests = SupplyRequests::where('csrno','=',$request->csrno)->select('csrno')->get();
       $csrcount = $requests->count();
       return $csrcount;
}

/**
 * CREATE NEW CSR
 */

public function storeCSR(Request $request){
   // dd($request->csrattachments);
		$status = SupplyRequests::updateOrCreate(
										['csrno'=>$request->csrno],
										['clientid'=>$request->clientid,'csrno'=>$request->csrno,'description'=>$request->description,
										'csrdate'=>$request->csrdate,'ponumber'=>$request->ponumber,'podate'=>$request->podate,
										'currency'=>$request->currency,'csrvalue'=>$request->csrvalue,'status'=>$request->status,
										'soldby'=>$request->soldby]
										);

	if($status){ //if the csr was created, upload items
	 		//$response =1; $msg="";
             $csritems  = json_decode($request->input('csritems'),true);

             foreach ($csritems as $row) {
                $status = CsrItems::updateOrCreate(
                                    ['csrno'=>$row['csrno'],
                                    'itemid'=>$row['partno']],
                                    ['csrno'=>$row['csrno'],
                                    'qty'=>$row['qty'],
                                    'unitbp'=>$row['unitbp'],
                                    'markup'=>$row['markup'],
                                    'unitsp'=>$row['unitsp'],
                                    'vatrate'=>$row['vatrate'],
                                    'itemtotal'=>$row['itemtotal'],
                                    'itemid'=>$row['partno']
                                    ]
                                               );
                            }

         if($status){
             foreach ($csritems as $row) {//update supply items inventory
               $status = Supplyitems::updateOrCreate(
                               ['partno'=>$row['partno']],
                               ['partno'=>$row['partno'],'name'=>$row['name'],'unitbp'=>$row['unitbp']]);
                                    }

                //upload attachments after populating items

             if ($request->hasFile('Attachment')) {
               // $j = count($request->input('Attachment'));
               $files = $request->file('Attachment');

                // echo 'files found';

                foreach($files as $file){
                 //   $file = $request->file('Attachment[]');
                    $filename = str_replace('/','-',$request->csrno)."--".$file->getClientOriginalName().'.'.$file->getClientOriginalExtension();
                    $destinationPath = base_path('Uploads');
                     $filecheck = $destinationPath.'/'.$filename;
                     if(Storage::exists($filecheck)){
                        Storage::delete($filecheck);
                        }

                        $status = $file->move($destinationPath,$filename);
                        $request->merge(['filename'=>$filename]);

                        $status = CSRAttachments::updateOrCreate(["filename"=>$filename,'csrno'=>$request->csrno],
                        [$request->except(['attachment'])]);
                 }

                    if($status){
                         $response =1; $msg="CSR with attachments saved successfully!";
                     }	else{
                         $response =0; $msg="An error occured. Attachments not saved.";
                        }
                 }else{  // if no attachments were found
                    $response =1; $msg="Supply request ".$request->csrno." saved successfully!";
                 }

                 /**
                  * SEND EMAIL NOTIFICATION
                  */

                 if($request->notification !="" || $request->notification!=null){
                    $subject = "CSR NO ".$request->csrno." : ".$request->clientname." - ".$request->description;



                    $email_message = "<p>Dear All,";
                   // foreach($mailinglist as $list){ $email_message .= $list->name." </p>";};

                     $email_message .="<p>Please login to <a href='http://techsupport.symphonykenya.com/'>Tech Support->Supply Requests</a> to view and download attachments for the CSR.</p>";
                      $email_message .="<p>Regards,</p><p>Symphony Tech Support</p>";

                         $mail_list  = array();
                      $mail_list = $mail_list; $cc_list = 'jasewe@symphony.co.ke';
                      $data = [];
                      foreach(json_decode($request->input("mailinglist")) as $list){
                        $recipientemail = $list->email;
                        $data = array('name'=>$list->name,'email'=>$list->email);
                         array_push($mail_list,$recipientemail);

                        }
                       // dd($data);
                    // $emaildata = array("contactname"=>$request->recipient,"subject"=>$subject,"body"=> $email_message);
                  // $status = $this->sendEmailwith_attachment($emaildata,$mail_list,$cc_list);

                  /*return $this->view('layouts.email',compact( $emaildata));*/
                   $emaildata = [
                    'title'=>'Hellow Avijit',
                    'content'=>'This is a testing of mailing in Laravel using mailgun'
                  ];

                  $status =  Mail::send('layouts.email', $mail_list,
                                    function($message) use ($mail_list, $emaildata,$subject){
                                    //$message->to($mail_list, '')
                                    $message->cc($mail_list, '')
                                    ->subject($subject);
                  });

                   if($status){
                       $response =1; $msg .="Notification sent";
                   }else{
                       $response =1; $msg="Supply request ".$request->csrno." saved successfully!";
                   }
               }
            }
               else{ // if csr items were not uploaded
                         $response =0; $msg="An error occured. csr items not saved!";
                      }

	 	}else{ //if the csr was not created.

				$response =0; $msg="An error occured, try again!";
		 	}

		return response()->json(["msg"=>$msg,"response"=>$response]);
	}




public function filterCSR(Request $request){
	$client = SupplyRequests::from('supply_requests')
	->leftjoin('clients','supply_requests.clientid','=','clients.id')
	->leftjoin('users as salespersons','supply_requests.soldby','=','salespersons.id')
	->leftjoin('users as creators','supply_requests.createdby','=','creators.id')
	->select('clients.id',
              'clients.clientname',
              'supply_requests.csrno',
              'supply_requests.description',
              'supply_requests.csrdate',
              'supply_requests.ponumber',
              'supply_requests.podate',
              'supply_requests.currency',
              'supply_requests.csrvalue',
              'salespersons.name as saleperson',
              'salespersons.id as salepersonid',
              'supply_requests.status',
              'creators.name as csrcreator',
              'creators.id as loginuserid')
	->where('supply_requests.csrno','=',$request->csrno)
	->get();

	return $client;
}


public function getCsritems(Request $request){
    $items = Csritems::where('csr_items.csrno','=',$request->csrno)
                            ->select("csr_items.id","csr_items.itemid","supplyitems_inventory.partno","supplyitems_inventory.name","csr_items.qty","csr_items.unitbp","csr_items.markup","csr_items.unitsp","csr_items.vatrate","csr_items.itemtotal")
                            ->leftjoin("supplyitems_inventory","csr_items.itemid","=","supplyitems_inventory.partno")
                            ->orderBy("supplyitems_inventory.name","asc")
                            ->get();
    return $items;
}

public function getFilenames(Request $request){
	$results = CSRAttachments::where('csrno','=',$request->csrno)
				->select("filename")
				->get();
	return $results;
}

function downloadFile(Request $request){
	$file =  base_path('Uploads').'/'.$request->filename;
	$headers = [
			'Content-Type: application/pdf',
	];
	return response()->download($file,$request->filename,$headers);


}

/**
 * DELETE CSR ATTACHMENT
 */
public function deleteAttachment(Request $request){

	$status = CSRAttachments::where("csrno",'=',$request->csrno)
									->where('filename','=',$request->filename)
									->delete();
	if($status>0){
	 $destinationPath = base_path('Uploads');
		 $filecheck = $destinationPath.'/'.$request->filename;
			if(Storage::exists($filecheck)){
				Storage::delete($filecheck);
			}
	$response =1; $msg="File deleted successfully!";
	}else{
			$response =0; $msg="File not found, nothing was deleted!";
	}
	 return response()->json(["msg"=>$msg,"response"=>$response]);
}



public function dashInfo(){
        $dashinfo = SupplyRequests::select(
            "currency",
            DB::raw("SUM(csrvalue) as csrvalue")
        )
        ->whereYear("csrdate",date('Y'))
        ->groupBy("currency")
        ->get();

        return $dashinfo;
    }

    /**
     * PRINT CSR
    */
public function printCSR(Request $request){

		$csrdata =  SupplyRequests::from('supply_requests')
					->leftjoin('clients','supply_requests.clientid','=','clients.id')
					->leftjoin('users as salespersons','supply_requests.soldby','=','salespersons.id')
					->leftjoin('users as creators','supply_requests.createdby','=','creators.id')
					->select('clients.id','clients.clientname','clients.address','clients.phone','clients.city','clients.contactperson','clients.email','supply_requests.csrno','supply_requests.description','supply_requests.csrdate','supply_requests.ponumber','supply_requests.podate','supply_requests.currency','supply_requests.csrvalue','salespersons.name as saleperson','salespersons.id as salepersonid','supply_requests.status','creators.name as csrcreator','creators.id as loginuserid')
					->where('supply_requests.csrno','=',$request->csrno)
					->get();

		$csritems = Csritems::where('csr_items.csrno','=',$request->csrno)
								->select("csr_items.id","csr_items.itemid","supplyitems_inventory.partno","supplyitems_inventory.name","csr_items.qty","csr_items.unitbp","csr_items.markup","csr_items.unitsp","csr_items.vatrate","csr_items.itemtotal")
								->leftjoin("supplyitems_inventory","csr_items.itemid","=","supplyitems_inventory.partno")
								->orderBy("supplyitems_inventory.name","asc")
								->get();

						return view('supplyrequests.csrprintout',compact('csrdata','csritems'));
	}


 /**
  * DELETE CSR
   */
  public function deleteCSR(Request $request){
    $status = Csritems::where("csrno",'=',$request->csrno)->delete();
    $attachments = CSRAttachments::where("csrno",'=',$request->csrno)->select("filename")->get();
        $destinationPath = base_path('Uploads');
   foreach($attachments as $item){
       $filecheck = $destinationPath.'/'.$item->filename;
       if(Storage::exists($filecheck)){
           $status = 	Storage::delete($filecheck);
       }
   }

    if($status){
         CSRAttachments::where("csrno",'=',$request->csrno)->delete();

       $status = SupplyRequests::where("csrno",'=',$request->csrno)->delete();
    }
if($status){
    $msg = "Supply request deleted successfully!"; $response =1;
}else{
     $msg = "Supply request was not deleted!"; $response =0;
}

return response()->json(["response"=>$response,"msg"=>$msg]);
}


/***
 * GET CSR YEAR SALES */
public function csryearSales(){
    $yearsalesKES = SupplyRequests::select(
        DB::raw("MONTH(csrdate) AS month"),
        DB::raw("MONTHNAME(csrdate) AS monthname"),
        "currency",
        DB::raw("IFNULL(SUM(csrvalue),0) as monthsales"),
        )->whereYear("csrdate",date('Y'))
      // )->whereYear("csrdate",'2022')
       // ->where("currency","KES")
        ->groupBy("month")
        ->groupBy("currency")
        ->get();

      /*  $yearsalesUSD = SupplyRequests::select(
            DB::raw("MONTH(csrdate) AS month"),
            DB::raw("MONTHNAME(csrdate) AS monthname"),
            "currency",
            DB::raw("SUM(csrvalue) as monthsales"),
            )->whereYear("csrdate",date('Y'))
            ->where("currency","USD")
            ->groupBy("month")
            //->groupBy("currency")
            ->get();*/

        return response()->json(["sales"=>$yearsalesKES]);
}


}
