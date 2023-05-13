@extends('layouts.app')
@section('title','Parts Inventory')
@section('content')

<div class="container-fluid overflow-hidden">
    <div class="main-body">
        <div class="row top-row bg-primary mb-2">
            <p class="text-center text-uppercase text-light fw-bold fs-1" id="pagetitle">Tools & Parts Inventory</p>
        </div>
         <div id="inventorymngmnt">
          <div class="row gutters-sm justify-content-center" >
            <div class="col-sm-5">
                 
                 <div class="card pl-5 pr-5">
                    <div class="alert alert-success text-center mt-3" role="alert" id="alertmessage2">
                        <span class="message fw-bold"></span>
                    </div>
                    <div class="card-body">
                        <h2 class="card-title text-center fw-bold fs-3"><i class="fa-solid fa-list text-primary"></i> Part Register</h2><hr class="hr">
                        <form method="post" class="mt-3" data-parsley-validate="" action="#" id="partregisterform">
                            @csrf
                            <div class="form-group row mb-3">
                              <label for="partno" class="col-sm-3 col-form-label">Part Number <span class="text-danger">*</span></label>
                              <div class="col-sm-8 align-items-left">
                                <input type="text" class="form-control" name="partno" id="partno" placeholder="e.g 653957-001" required
                                  data-parsley-trigger="keyup" data-parsley-required-message="Part number is required">
                              </div>
                            </div>

                            <div class="form-group row mb-3 ">
                              <label for="description" class="col-sm-3 col-form-label text-left">Description <span class="text-danger">*</span></label>
                              <div class="col-sm-8 ">
                                <input type="text" class="form-control" id="description" name="description" placeholder="e.g. HP 600GB 10k SFF SAS HDD " required
                                data-parsley-trigger="keyup" data-parsley-required-message="Description is required">
                              </div>
                            </div>

                            <div class="form-group row mb-3 ">
                                <label for="status" class="col-sm-8 col-form-label text-left">
                                    Status <span class="text-danger">*</span>
                                    <small class="text-primary">Lock an item from being issued out of the system</small>
                                </label>
                                
                                <div class="form-check ml-3">
                                    <input class="form-check-input" type="radio" name="lockstatus" id="locked" value="1">
                                    <label class="form-check-label" for="locked">Locked </label>
                                </div>
                                  <div class="form-check ml-3">
                                    <input class="form-check-input" type="radio" name="lockstatus" id="unlocked" checked value="0">
                                    <label class="form-check-label" for="unlocked">Unlocked</label>
                                  </div>
                            </div>

                            <div class="form-group row">
                              <div class="col-sm-6">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
                              </div>
                            </div>
                          </form>
                        
                    </div>
                </div>
       
                 <div class="card mt-3 pt-2">
                        <div class="card-body">
                              <p>
                                <button class="btn btn-dark text-dark action" type="button" id="issueparts" >Issue Parts</button>
                                <button class="btn btn-dark text-dark action" type="button" id="viewstock" >View Inventory</button>
                                <button class="btn btn-dark text-dark action" type="button" id="trackparts" >Track Parts </button>
                               
                              </p>
                        </div>

                    </div>
              
             
             </div>

             
             <div class="col-sm-7"> <!-- add stock section-->
                <div class="card pl-5 pr-5">
                    <div class="alert alert-success text-center mt-3" role="alert" id="alertmessage">
                        <span class="message fw-bold"></span>
                    </div>
                    <div class="card-body">
                        <h2 class="card-title text-center fw-bold fs-3">
                            <i class="fa fa-boxes text-primary"></i> Add Stock
                        </h2><hr class="hr">
                        <form method="POST" class="mt-3" data-parsley-validate="" action="#" id="partstockform">
                            @csrf
                            <div class="form-group row mb-3">
                                <label for="client" class="col-sm-2 col-form-label">Client <span class="text-danger">*</span></label>
                                <div class="col-sm-6 d-flex flex-row-reverse">
                                    <input type="text" class="form-control clientname" name="clientname" id="clientname" placeholder="Search client" required
                                      data-parsley-trigger="keyup" data-parsley-required-message="Client is required" autocomplete="off">
                                </div> 
                               <div class="col-sm-2" style="display:none"><span id="clientid">some text</span></div>
                               <small class="text-primary">search client who owns the part then press enter.</small>
                            </div>
                           
                              </div>

                              <div class="form-group row mb-3">
                                <label for="stockpartno" class="col-sm-2 col-form-label">Part # <span class="text-danger">*</span></label>
                                <div class="col-sm-5 align-items-left">
                                  <input type="text" class="form-control partnosearch" name="stockpartno" id="stockpartno" placeholder="Type to search" required
                                    data-parsley-trigger="keyup" data-parsley-required-message="Part number is required" autocomplete="off">
                                </div>

                                <label for="partqty" class="col-sm-2 col-form-label">Quantity <span class="text-danger">*</span></label>
                                <div class="col-sm-3">
                                    <input type="number" class="form-control" name="partqty" id="partqty" placeholder="" required
                                    data-parsley-trigger="keyup" data-parsley-type="number" data-parsley-required-message="Quantity is required">
                                  </div>
                              </div>
  
                              <div class="form-group row mb-3 ">
                                <label for="stockdescription" class="col-sm-2 col-form-label text-left">Description <span class="text-danger">*</span></label>
                                <div class="col-sm-10 ">
                                  <input type="text" class="form-control" id="stockdescription" placeholder="e.g. HP 600GB 10k SFF SAS HDD " readonly required
                                  data-parsley-trigger="keyup" data-parsley-required-message="Description is required">
                                </div>
                              </div>

                              <div class="form-group row mb-3">
                               
                                <label for="datereceived" class="col-sm-2 col-form-label">Received on <span class="text-danger">*</span></label>
                                <div class="col-sm-4 align-items-left">
                                  <input type="text" class="form-control datepicker" name="datereceived" id="datereceived" placeholder="" required
                                    data-parsley-trigger="keyup" data-parsley-type="date" data-parsley-required-message="Date received is required">
                                </div>
                                <label for="receivedby" class="col-sm-2 col-form-label">Received By <span class="text-danger">*</span></label>
                                <div class="col-sm-4 align-items-left">
                                  <input type="text" class="form-control " name="receivedby" id="receivedby" value="{{auth()->user()->idnumber}}" readonly >
                                </div>
                              </div>

                              <div class="form-group row mb-3">
                                <label for="reference" class="col-sm-2 col-form-label">Ref No. <span class="text-danger">*</span></label>
                                <div class="col-sm-4 align-items-left">
                                  <input type="text" class="form-control" name="refno" id="refno" placeholder="Reference number" required
                                    data-parsley-trigger="keyup" data-parsley-required-message="Reference number is required">
                                </div>

                                <label for="ticketno" class="col-sm-2 col-form-label">Ticket No. <span class="text-danger">*</span></label>
                                <div class="col-sm-4 align-items-left">
                                  <input type="text" class="form-control" name="ticketno" id="ticketno" placeholder="Ticket number" required
                                    data-parsley-trigger="keyup" data-parsley-required-message="Ticket number is required">
                                </div>
                              </div>

                              <div class="row mb-3 justify-content-center">
                                <div class="col-sm-4  d-flex justify-content-center">
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"> Save</i></button>
                                </div>
                                <div class="col-sm-4 d-flex justify-content-center">
                                    <button type="button" class="btn btn-danger bg-danger"><i class="fa fa-trash"> Delete</i></button>
                                </div>
                               
                              </div>
                        </form>
                    </div>
                </div><!-- end add stock section-->
             </div>
            </div>

             <div class="row mt-3" id="collapseWidth">  <!-- Parts Inventory SECTION-->
                <div class="col-sm-12" >
                  <div class="card card-body" id="wrapper">
                    <div id="viewstocklist" class="pl-10 pr-10">
                        <div class="row">
                            <div class="col-sm-6">
                                <p class="display-6">Parts Inventory</p>
                            </div>
                            <div class="col-sm-6 d-flex justify-content-end">
                                <button class="btn text-danger direct"><i class="fa fa-arrow-left"></i> Go Back</button>
                            </div>
                        </div>
                        <hr class="hr">
                        <div class="row justify-content-center mt-3">
                            <div class="table-responsive">
                                <table class="table table-sm table-hover table-bordered table-striped mb-3" id="stocklisttable">
                                    <thead class="thead-dark">
                                        <th >No</th>
                                        <th >Part #</th>
                                        <th >Description</th>
                                        <th >Qty</th>
                                        <th >Owned By</th>
                                        <th >Last Used</th>
                                        <th >Ticket</th>
                                    </thead>
                                    <tbody>
                                        @empty(!$stock) 
                                            @forelse($stock as $key=>$item)
                                            <tr id="{{ $item->partno }}">
                                                <td style="width:2%;" class="text-center" >{{$key+1}}</td>
                                                <td style="width:10%;">{{$item->partno}}</td>
                                                <td style="width:30%;">{{$item->description}}</td>
                                                <td style="width:5%;" class="text-center">{{$item->quantity}}</td>
                                                <td style="width:15%;">{{$item->clientname}}</td>
                                                 <td style="width:10%;">
                                                  @if($item->lastused!="Never used")
                                                   {{\Carbon\Carbon::parse($item->lastused)->format('d M,Y')}}
                                                   @else
                                                   {{$item->lastused}}
                                                  @endif
                                                </td>
                                                <td style="width:8%;">{{$item->ticketno}}</td>
                                            </tr>
                                            @empty
                                            @endforelse
                                        @endempty
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- ISSUE PARTS SECTION-->
                    <div id="issuepartssection" class="pl-10 pr-10">
                        <div class="row">
                            <div class="col-sm-4">
                                <p class="display-6">Issue Parts</p>
                            </div>
                            <div class="col-sm-6">
                              <div class="alert alert-success text-center mt-3" role="alert" id="alertmessage3">
                                <span class="message fw-bold"></span>
                              </div>
                            </div>
                            <div class="col-sm-2 d-flex justify-content-end">
                                <button class="btn text-danger direct"><i class="fa fa-arrow-left"></i> Go Back</button>
                            </div>
                        </div><hr class="hr">

                        <div class="row">
                            <div class="col-sm-5">
                                <form method="POST" class="mt-3" data-parsley-validate="" action="#" id="issuepartform">
                                    @csrf
                                    <div class="form-group row mb-3">
                                        
                                        <label for="client" class=" col-form-label">
                                            Client <span class="text-danger">*</span>
                                            <small class="text-primary">Enter the name of the client for which the ticket is carried out. </small>
                                        </label>
                                        <div class="col-sm-10 ">
                                            <input type="text" class="form-control clientname" name="ticketclientname" id="ticketclientname" placeholder="Search client" required
                                              data-parsley-trigger="keyup" data-parsley-required-message="Client is required" autocomplete="off">
                                        </div> 
                                       
                                      
                                    </div>
                                    <div class="form-group row mb-3">
                                        <label for="assignedticketno" class="col-sm-3  col-form-label">
                                            Ticket No <span class="text-danger">*</span>
                                            <!--<small class="text-primary">Enter the ticket number for this call. </small>-->
                                        </label>
                                        <div class="col-sm-7 ">
                                            <input type="text" class="form-control" name="assignedticketno" id="assignedticketno" placeholder="Tickent no." required
                                              data-parsley-trigger="keyup" data-parsley-required-message="Ticket number is required" autocomplete="off">
                                        </div> 
                                    </div>
                                    <div class="form-group row mb-3">
                                        <label for="issuedto" class="col-sm-3 col-form-label">
                                            Assigned To<span class="text-danger">*</span>
                                           
                                        </label>
                                        <div class="col-sm-7">
                                            <select class="form-control" name="issuedto" id="issuedto" required data-parsley-required-message="Assignee is required">
                                                <option selected value="" class="fw-bold text-center">Choose Assignee</option>
                                            </select>
                                            
                                        </div> 
                                    </div>
                                    <div class="form-group row mb-3">
                                        <label for="assignedon" class="col-sm-3 col-form-label">
                                            Assigned On<span class="text-danger">*</span>                                           
                                        </label>
                                        <div class="col-sm-7 align-items-left">
                                            <input type="text" class="form-control datepicker" name="assignedon" id="assignedon" placeholder="" required
                                              data-parsley-trigger="keyup" data-parsley-required-message="Date assigned is required">
                                        </div>
                                    </div>
                                    <div class="row justify-content-center mt-3 mb-3">
                                      <div class="col-sm-4">
                                          <button type="submit" class="btn btn-block bg-primary text-light" id="issuepartsbtn">Issue</button>
                                      </div>
                                      <div class="col-sm-4">
                                        <button type="button" class="btn btn-block bg-danger text-light">Cancel</button>
                                    </div>
                                    </div>
                                </form>
                            </div>

                            <div class="col-sm-7" style="border-left: 1px solid #959599;">
                              <p ><span class="fs-3">Issue List<span class="text-danger">*</span> </span><small class="text-primary">Select a part from the list below to add it here.</small></p>
                               <!-- <input type="text" class="form-control partnosearch" name="ticketpartno" id="ticketpartno" placeholder="Type to search" required
                                  autocomplete="off">-->
                                <div class="table-responsive mt-3">
                                    <table class="table table-sm table-hover table-stripped table-bordered" id="issuedpartstable">
                                        <thead class="thead-dark">
                                            <th> </th>
                                            <th>No </th>
                                            <th>Stock ID</th>
                                            <th> Part No</th>
                                            <th> Description</th>
                                            <th> Quantity</th>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                       
                        <div class="row mt-3 justify-content-center">
                          <div class="col-sm-12">
                            <p ><span class="fs-3">Search Part Number </span><small class="text-primary">Search part number, click it to add to issue list.</small></p>
                            <hr class="hr mt-2">
                          </div>
                        </div>
                        <div class="row mt-3 justify-content-center">
                            <div class="col-sm-12">
                             
                                <div class="table-responsive" id="issupartsdiv">
                                  <table class="table table-sm table-bordered table-hover table-striped" id="issuepartsinventorytable">
                                    <thead class="thead-dark">
                                      <th>No</th>
                                      <th>Stock ID</th>
                                      <th>Part #</th>
                                      <th>Description</th>
                                      <th>Quantity</th>
                                      <th>Reference</th>
                                      <th>Ticket #</th>
                                      <th>Owner</th>
                                      <th>Added On</th>
                                      <th>Added By</th>
                                    </thead>
                                    <tbody>
                                      @empty(!$inventory) 
                                          @forelse($inventory as $key=>$item)
                                          <tr id="{{ $item->partno }}">
                                              <td style="width:2%;" class="text-center" >{{$key+1}}</td>
                                              <td style="width:5%;" class="text-center">{{$item->stockid}}</td>
                                              <td style="width:10%;">{{$item->partno}}</td>
                                              <td style="width:20%;">{{$item->description}}</td>
                                              <td style="width:6%;" class="text-center">{{$item->quantity}}</td>
                                              <td style="width:6%;">{{$item->refno}}</td>
                                              <td style="width:6%;">{{$item->ticketno}}</td>
                                              <td style="width:15%;">{{$item->clientname}}</td>
                                              <td style="width:8%;">
                                               {{\Carbon\Carbon::parse($item->receivedon)->format('d M,Y')}}
                                              </td>
                                              <td style="width:10%;">{{$item->receivedby}}</td>
                                          </tr>
                                          @empty
                                          @endforelse
                                      @endempty
                                  </tbody>
                                  </table>
                                </div>
                            </div>
                        </div>
                    </div><!-- close issueparts section-->
                    
                    <div id="trackpartsection" class="pl-10 pr-10"><!-- Track Parts section-->

                      <div class="row">
                        <div class="col-sm-8">  <p class="display-6">Track Parts</p></div>
                         
                          <div class="col-sm-4 d-flex justify-content-end">
                            <button class="btn text-danger direct"><i class="fa fa-arrow-left"></i> Go Back</button>
                          </div>
                      </div> <hr class="hr mt-2">

                      <div class="row mt-3 justify-content-center">
                        <div class="col-sm-12">
                          
                        </div>
                      </div>
                      <div class="row mt-3 justify-content-center">
                          <div class="col-sm-12">
                           
                              <div class="table-responsive" id="partshistory">
                                <table class="table table-sm table-bordered table-hover table-striped" id="partshistorytable">
                                  <thead class="thead-dark">
                                    <th>No</th>
                                    <th>Part #</th>
                                    <th>Description</th>
                                    <th>Quantity</th>
                                    <th>Issued To</th>
                                    <th>Issued On</th>
                                    <th>Issued By</th>
                                    <th>Used At</th>
                                    <th>Ticket #</th>
                                  </thead>
                                  <tbody>
                                    @empty(!$partshistory) 
                                        @forelse($partshistory as $key=>$item)
                                        <tr id="{{ $item->partno }}">
                                            <td style="width:2%;" class="text-center" >{{$key+1}}</td> 
                                            <td style="width:10%;">{{$item->partno}}</td>
                                            <td style="width:20%;">{{$item->description}}</td>
                                            <td style="width:5%;" class="text-center">{{$item->quantity}}</td>
                                            <td style="width:6%;">{{$item->issuedto}}</td>
                                            <td style="width:8%;">
                                              {{\Carbon\Carbon::parse($item->issuedon)->format('d M,Y')}}
                                            </td>
                                            <td style="width:6%;">{{$item->issuedby}}</td>
                                            <td style="width:15%;">{{$item->client}}</td>
                                            <td style="width:10%;">{{$item->ticketno}}</td>
                                        </tr>
                                        @empty
                                        @endforelse
                                    @endempty
                                </tbody>
                                </table>
                              </div>
                          </div>
                      </div>

                    </div><!-- close track parts section-->
                  </div>
                </div>
              </div>

           </div><!-- close main-body-->
         </div><!--close container-fluid-->


@endsection