@extends('layouts.app')
@section('title','Service Tickets')
@section('content')

<div class="container-fluid pb-5">
    <div class="row top-row bg-primary mb-2">
        <p class="text-center text-uppercase text-light fw-bold fs-1">Service Tickets</p>
    </div>

    <div class="row" id="ticketlist">
        <!--<div class="col-sm-1"></div>-->
        <div class="col-sm-12">
            <div class="bg-light rounded pl-5 pr-5 pt-6 pb-6  section">
                <div class="alert alert-info ml-5 mr-5" role="alert">
                   Click a ticket to update it. Click <a href="{{ route('claims.mileage') }}">
                    <span class="text-primary fw-bold">here</span></a> to create your claims. </span>
                  </div>
                  <div class="row">

                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-4 d-flex justify-content-end">
                                <!---<input type="text" class="form-control mb-2 mt-3 search" placeholder="Search" id="searchticket"/>-->
                             </div>
                        </div>
                        <div class="pl-5 pr-5" id="serviceticketlist">
                            @empty(!$tickets)
                            <div class="table-responsive overflow-hidden mb-3" >
                                <table class="table table-hover table-bordered table-striped"
                                    data-filter-control="true" id="serviceticketstable"  data-show-search-clear-button="true">
                                    <thead class="thead-dark" style="font-size:12px;">
                                        <th style="width: 3%" class="text-center">No. </th>
                                        <th style="width: 8%;">Ticket Date</th>
                                        <th style="width: 8%">Ticket #</th>
                                        <th style="width: 6%">JC #</th>
                                        <th style="width: 15%">Client </th>
                                        <th style="width: 10%">Location </th>
                                        <th style="width: 18%">Task </th>
                                        <th style="width: 10%">Priority </th>
                                        @if(Auth::user()->role=="Admin")
                                        <th style="width: 10%">Tech </th>
                                        @endif
                                        <th style="width: 5%">Days </th>
                                        <th style="width: 7%">Status </th>
                                    </thead>

                                    <tbody>
                                        @forelse ($tickets as $key=>$ticket)
                                        <tr id="{{ $ticket->ticketno }}">
                                          <td class="text-center">{{$key+1}}</td>
                                          <td>
                                            {{\Carbon\Carbon::parse($ticket->ticketdate)->format('d M,Y')}}
                                        </td>
                                          <td>{{$ticket->ticketno}}</td>
                                          <td>{{$ticket->jobcardno}}</td>
                                         
                                          <td>{{$ticket->clientname}}</td>
                                          <td>{{$ticket->location}}</td>
                                          <td>{{$ticket->faultreported}}</td>
                                          <td>{{$ticket->urgency}}</td>
                                          @if(Auth::user()->role=="Admin")
                                           <td style="width: 10%;">{{$ticket->name}} </td>
                                          @endif
                                          <td>
                                             @php
                                                 $days = round((time() - strtotime($ticket->ticketdate) ) / 3600/24);
                                             @endphp
                                             @if($ticket->status=="Closed")
                                               <span class="text-success"> {{ $days }} days ago</span>
                                               @elseif($days>0 && $ticket->status!="Closed")
                                               <span class="text-danger"> {{ $days }} days ago</span>
                                               @elseif($days==0 && $ticket->status!="Closed")
                                               <span class="text-danger"> Today</span>
                                               @else
                                               <span class="text-primary">Upcoming</span>
                                               @endif
                                          </td>
                                          <td class="text-center">
                                             <span class="display">
                                                 @if($ticket->status=="Closed")
                                                 <a href="#"><div class="d-inline p-2 bg-success text-white rounded">
                                                     {{$ticket->status}}
                                                 </div>
                                                 @else
                                                 <a href="#"><div class="d-inline p-2 bg-danger text-white rounded">
                                                     {{$ticket->status}}
                                                 </div>
                                                 @endif
                                             </a>
                                             </span>
                                          </td>
                                        </tr>

                                     @empty
                                     <div class="alert alert-info" role="alert">
                                         No service ticket found.
                                         Requet admin to create one for you.
                                          <p class="">Go to<a  href="{{ route('dashboard') }}">
                                             <span class="text-primary fw-bold">dashboard</span></a></p>
                                       </div>

                                     @endforelse

                                    </tbody>
                                </table>
                                <div class="row pl-10 pr-10 pb-10 pt-10">
                                    <div class="col-sm-12">

                                    </div>
                                </div>
                            </div>
                            @endempty

                        </div>
                    </div>

                  </div>
            </div>
        </div>
       <!-- <div class="col-sm-1"></div>-->
    </div>

    <div class="row " id="ticketupdate">
        <div class="col-sm-12">
            <div class="bg-light rounded pl-5 pr-5 pt-6 pb-6  d-flex justify-content-center section">


                <div class="card ml-10 mr-10 pl-5 pr-5 pt-2 pb-5 col-sm-8">
                    <div class="alert alert-success text-center mt-3" role="alert" id="alertmessage2">
                        <span class="message fw-bold"></span>
                    </div>
                     <div class="card-body row">
                          <div class="row">
                            <div class="col-sm-10">
                                <h4 class="card-title fw-bold">
                                    <span id="ticketno" class="text-primary" style="display: none;">|</span>
                                        <span id="cardtitle" class="text-primary"></span>
                                    </h4> 
                            </div>
                            <div class="col-sm-2">
                                <button class="btn btn-sm exitupdate">
                                    <i class="fa fa-arrow-left text-danger"></i> <span class="text-danger">Go Back</span>
                                </button>
                            </div>
                            </div>
                            <hr class="hr mb-2 ">
                            <form method="POST" class="form-inline" id="ticketupdateform"
                            data-parsley-validate="" name="ticketupdateform">
                                @csrf

                                <div class="row g-3 mb-3">
                                    <label for="jobcardno" class="col-sm-2 mt-4 d-flex justify-content-end">Job Card # <span class="text-danger">*</span></label>
			                        <div class="col-sm-4">
				                        <input class="form-control" type="text" name="jobcardno" id="jobcardno" placeholder="Job Card Number" required data-parsley-required-message="Job Card number is required" autocomplete="off">
			                        </div>

                                    <label for="site" class="col-sm-2 mt-4 d-flex justify-content-end">Site <span class="text-danger">*</span></label>
                                    <div class="col-sm-4">
				                        <input class="form-control" type="text" name="site" id="site" placeholder="Site" required data-parsley-required-message="Site is required" autocomplete="off">
			                        </div>
                                </div>

                                <div class="row g-3 mb-3">
                                    <label for="servicedate" class="col-sm-2 mt-4 d-flex justify-content-end">Start Time <span class="text-danger">*</span></label>
                                    <div class="col-sm-4">
                                      <div class="input-group flex-nowrap mb-3"  data-date-format="dd-mm-yyyy hh:mm" data-mdb-inline="true">
                                        <input class="form-control datepicker" type="text" name="startdatetime" id="startdatetime" autocomplete="off" aria-describedby="addon-wrapping" placeholder="dd-mm-yyyy hh:mm"
                                            required data-parsley-required-message=""/>
                                        <span class="input-group-text" id="addon-wrapping">
                                          <i class="fa fa-clock"></i>
                                        </span>
                                     </div>
                                    </div>

                                    <label for="servicedate" class="col-sm-2 mt-4 d-flex justify-content-end">End Time <span class="text-danger"> *</span></label>
                                    <div class="col-sm-4">
                                        <div class="input-group flex-nowrap mb-3 rounded"  data-date-format="dd-mm-yyyy hh:mm" data-mdb-inline="true">
                                            <input class="form-control datepicker" type="text" name="enddatetime" id="enddatetime" autocomplete="off" aria-describedby="addon-wrapping" placeholder="dd-mm-yyyy hh:mm"
                                              data-parsley-required-message="" required/>
                                            <span class="input-group-text" id="addon-wrapping">
                                              <i class="fa fa-clock"></i>
                                            </span>
                                         </div>


                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="input-group">
                                        <span class="input-group-text">Equip. Model and Serial #<span class="text-danger">*</span></span>
                                        <input type="text" aria-label="Model" class="form-control" placeholder="Equipment Model" autocomplete="off" name="equipmodel" id="equipmodel"
                                        required  data-parsley-required-message="">
                                        <input type="text" aria-label="Serial No." class="form-control" placeholder="Serial No." autocomplete="off" name="serialno" id="serialno"
                                        required  data-parsley-required-message="">
                                      </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="findings">Findings <span class="text-danger">*</span></label>
                                        <br><small class="text-primary">Describe condition of the equipment on arrival at the site</small>
                                        <div class="col-sm-12 nopadding">
                                            <div class="btn-toolbar" data-role="editor-toolbar" data-target="#findings">
                                            <textarea id="findings" name="findings" class="form-control" rows="3" required data-parsley-required-message="Finding is required"></textarea>
                                            </div>
                                       </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="action_taken">Action Taken <span class="text-danger">*</span></label>
                                        <br><small class="text-primary">Describe activities carried out during the service.</small>
                                        <div class="col-sm-12 nopadding">
                                            <div class="btn-toolbar" data-role="editor-toolbar" data-target="#action_taken">
                                            <textarea id="action_taken" name="action_taken" class="form-control" rows="4" data-parsley-required-message="Action taken is required" required></textarea>
                                            </div>
                                       </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="recommendation">Conclusion & Recommendations <span class="text-danger">*</span></label>
                                        <small class="text-primary">Provide detailed conclusion and recommendations.</small>
                                        <div class="col-sm-12 nopadding">
                                            <div class="btn-toolbar" data-role="editor-toolbar" data-target="#recommendations">
                                            <textarea id="recommendations" name="recommendations" class="form-control" rows="4" required data-parsley-required-message="Recommendation is required" required></textarea>
                                            </div>
                                       </div>
                                </div>
                                <div class="row">
                                    <label>Upload Job Card <span class="text-danger">*</span>
                                       <small class="text-primary">Click the file chooser below to browse your job card.</small>
                                   </label>
                                    <div class="mb-3 pt-4 pb-3 wrapper">
                                            <input type="file" name="jobcardupload" id="jobcardupload"
                                            accept=".xlsx,.xls,image/*,.doc, .docx,.ppt, .pptx,.txt,.pdf" />
                                     </div>
                                </div>
                                <div class="row filelist justify-content-left mb-3">
                                    <div class="col-sm-12">
                                    Attachments <small class="text-primary">Click the download icon to download the file.</small>
                                        <div class="row">
                                            <div class="col-sm-10">
                                                <a id="filename" href="#" target="_blank" class="text-center"></a>
                                            </div>
                                            <div class="col-sm-2">
                                                <button id="downloadjobcardbtn">
                                                    <span class="text-center download fs-4">
                                                        <i class="fa fa-download text-primary"></i>
                                                    </span>
                                                </button>

                                            </div>
                                        </div>



                                    <hr class="hr">
                                </div>
                                </div>
                                <div class="row mb-3 ">
                                    <label for="recommendation" class="col-sm-3">Ticket Status <span class="text-danger">*</span></label>
                                    <div class="col-sm-6">
                                        <select class="form-select" aria-label="Status" id="ticketstatus" name="ticketstatus" required data-parsley-required-message="Ticket status is required">
                                            <option value="">Select Status</option>
                                            <option value="Closed">Closed</option>
                                            <option value="Awaiting Parts">Awaiting Parts</option>
                                            <option value="Ongoing">Pending</option>
                                          </select>
                                    </div>
                                </div>
                                <div class="row mb-3 justify-center">
                                    <div class="col-sm-4 pl-3 pr-3 ">
                                        <button class="btn btn-block btn-primary" id="submitupdate" type="submit">
                                            <i class="fa fa-refresh"></i> Update Ticket
                                        </button>
                                    </div>
                                    <div class="col-sm-4 pl-3 pr-3">
                                        <button class="btn btn-block btn-danger exitupdate" id="exitupdate">
                                            <i class="fa fa-arrow-left"></i> Exit
                                        </button>
                                    </div>
                                </div>

                            </form>


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
