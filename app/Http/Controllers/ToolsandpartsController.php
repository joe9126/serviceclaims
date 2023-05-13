<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\PartsInventory;
use App\Models\PartStock;
use App\Models\AddedParts;
use App\Models\Clients;
use App\Models\User;
use App\Models\IssuedParts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator,Response,Redirect;


class ToolsandpartsController extends Controller
{
    public function partsInventory(){
        $stock = PartsInventory::select(
            "partsinventory.partno",
            "partsinventory.description",
            "partsinventory.addedby",
            "partsinventory.status",
            "clients.clientname",
            DB::raw("IFNULL(partstock.quantity,0) as quantity"),
           DB::raw("IFNULL(MAX(issuedparts.issuedon),'Never used') as lastused"),
           DB::raw("IFNULL(issuedparts.ticketno,'No Ticket') as ticketno")
        )->leftjoin("partstock","partstock.partno","=","partsinventory.partno")
        ->leftjoin("clients","partstock.client","=","clients.id")
        ->leftjoin("issuedparts","issuedparts.partno","=","partsinventory.partno")
        ->groupBy("partstock.partno")
         ->orderBy("partstock.partno","desc")//->toSql();
       ->get();
      // dd($stock);

        $inventory  = AddedParts::select(
            "addedparts.id as stockid",
            "addedparts.partno",
            "partsinventory.description",
            "addedparts.quantity",
            "addedparts.refno",
            "addedparts.ticketno",
            "addedparts.receivedon",
            "partsinventory.status",
            "clients.clientname",
            "users.name as receivedby"
        )
        
        ->join("partsinventory","partsinventory.partno","=","addedparts.partno")
        ->join("clients","clients.id","=","addedparts.client")
        ->join("users","users.id","=","addedparts.receivedby")
        ->orderBy("addedparts.receivedon","desc")->get();

        $partshistory = IssuedParts::join("partsinventory","partsinventory.partno","=","issuedparts.partno")
           ->join("users as issuedto",DB::raw("issuedto.id"),"=","issuedparts.issuedto")
           ->join("users as issuedby",DB::raw("issuedby.id"),"=","issuedparts.issuedby")
           ->select(
            "issuedparts.partno",
            "partsinventory.description",
            "issuedparts.quantity",
            "issuedparts.ticketno",
            "issuedparts.client",
            "issuedparts.issuedon",
            DB::raw("issuedto.name AS issuedto"),
            DB::raw("issuedby.name AS issuedby")
            )->orderBy("issuedparts.issuedon","desc")
            ->get();

        return view("toolsandparts.partsinventory",["stock"=> $stock,"inventory"=>$inventory,"partshistory"=>$partshistory]);
    }


    public function getInventory(){
        $inventory = PartsInventory::all();
        return response()->json($inventory);
    }

    /**
     * check part qty before adding to issue list
     */
/*public function getPartqty(Request $request){
    $response = PartStock::where("partno",$request->partno)
                    ->select("quantity")->first();
                    return $response;
}*/

    public function addInventory(Request $request){ //function to register a new part
        $response = PartsInventory::updateOrCreate(
            ["partno"=>$request->partno],
            ["partno"=>$request->partno,
            "description"=>$request->description,
            "status"=>$request->status,
            "addedby"=>Auth::user()->id
            ]);

            if($response){
                return response()->json([
                    'success'=>true,
                    'message'=>'Part number '.$request->partno.' saved successfully.'
                ],200);
            }else{
                return response()->json([
                    'success'   =>  false,
                    'message'   =>  'An error occured. Could not update part.'
                ], 200);
            }
    }
/**
 * add parts stock
 */
public function addStock(Request $request){
    $response = AddedParts::create(
        [
        "client"=>$request->client,
        "partno"=>$request->partno,
        "quantity"=>$request->quantity,
        "refno"=>$request->refno,
        "ticketno"=>$request->ticketno,
        "receivedby"=>Auth::user()->id,
        "receivedon"=>$request->receivedon,
      ]
);
       
        if($response){
            $response = PartStock::updateOrCreate(
                [
                    "partno"=>$request->partno,
                    //"client"=>$request->client,
                ],
                [
                "partno"=>$request->partno,
               // "client"=>$request->client,
                "quantity"=>DB::raw("quantity +".$request->quantity)
                ]
            );
        }

        if($response){
            return response()->json([
                'success'=>true,
                'message'=>$request->quantity.' items of part number '.$request->partno.' saved successfully.'
            ],200);
        }else{
            return response()->json([
                'success'   =>  false,
                'message'   =>  'An error occured. Could not update stock.'
            ], 200);
        }
    }

/**
 * ISSUES PARTS
 */

 public function issueParts(Request $request){
    $partlist = ($request->items);
    $items = json_decode($partlist);
   // dd(($items));
  foreach($items as $value){
   // dd("part no. is ".$value->partno);
    $response = IssuedParts::create(
           ["partno"=>$value->partno,
            "client"=>$value->client,
            "quantity"=>$value->quantity,
            "issuedto"=>$value->issuedto,
            "ticketno"=>$value->ticketno,
            "issuedby"=>Auth::user()->id,
            "issuedon"=>$value->issuedon,
            ]
     );
     if($response){
        $response =  PartStock::where("partno",$value->partno)
                                //->where("client",$value->client)
                                 ->update( 
                                        [
                                        "quantity"=>DB::raw("quantity -".$value->quantity),
                                        ]
                                    );

            $response = AddedParts::where("partno",$value->partno)
                                    ->where("id",$value->stockid)
                                ->update(
                                        [
                                        "quantity"=>DB::raw("quantity -".$value->quantity),
                                        ]);
     }
    }

    if($response){
        return response()->json([
            'success'=>true,
            'message'=>'Parts issued successfully'
        ],200);
    }else{
        return response()->json([
            'success'   =>  false,
            'message'   =>  'An error occured. Could not issue parts'
        ], 200);
    }
 }


public function getClients(){
        $clients = Clients::orderBy("clientname","asc")->get();
        return $clients;
    }

public function getUsers(){
        $users = User::select(
            "id",
            "name"
        )
        ->orderBy("name","ASC")
        ->get();
        return $users;
    }
}
