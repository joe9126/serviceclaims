@extends('layouts.app')
@section('content')

<div class="container pb-5">
    <div class="row top-row bg-primary mb-2">
        <p class="text-center text-uppercase text-light fw-bold fs-1">Service Tickets</p>
    </div>

    <div class="row ">
        <!--<div class="col-sm-1"></div>-->
        <div class="col-sm-12">
            <div class="bg-light rounded pl-5 pr-5 pt-6 pb-6  section">
                <div class="alert alert-info ml-10 mr-10" role="alert">
                   Click a ticket to update it. 
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
                            <div class="table-responsive overflow-hidden mb-3">
                                <table class="table table-hover table-bordered table-striped "
                                    data-filter-control="true" id="serviceticketstable"  data-show-search-clear-button="true">
                                    <thead class="thead-dark">
                                        <th style="width: 3%" class="text-center">No. </th>
                                        <th style="width: 8%">Ticket #</th>
                                        <th style="width: 6%">Date</th>
                                        <th style="width: 15%">Client </th>
                                        <th style="width: 10%">Location </th>
                                        <th style="width: 20%">Task </th>
                                        <th style="width: 10%">Priority </th>
                                        <th style="width: 5%">Days </th>
                                        <th style="width: 5%">Status </th>
                                    </thead>
                                   
                                    <tbody>  
                                        @forelse ($tickets as $key=>$ticket)
                                        <tr>
                                          <td class="text-center">{{$key+1}}</td>
                                          <td>{{$ticket->ticketno}}</td>
                                          <td>
                                             {{\Carbon\Carbon::parse($ticket->ticketdate)->format('d M,Y')}}
                                         </td>
                                          <td>{{$ticket->clientname}}</td>
                                          <td>{{$ticket->location}}</td>
                                          <td>{{$ticket->faultreported}}</td>
                                          <td>{{$ticket->urgency}}</td>
                                          <td>
                                             @php 
                                                 $days = round((time() - strtotime($ticket->ticketdate) ) / 3600/24);
                                             @endphp
                                             @if($days>0)
                                               <span class="text-danger"> {{ $days }} days ago</span>
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

    <div class="row d-none" id="ticketupdate">
        <div class="col-sm-12">
            <div class="bg-light rounded pl-5 pr-5 pt-6 pb-6  d-flex justify-content-center">

                <div class="card ml-10 mr-10 pl-5 pr-5 pt-2 pb-5 col-sm-8">
                    <div class="alert alert-success text-center" role="alert" id="alertmessage2">
                        <span class="message fw-bold"></span>
                    </div>

                    <div class="card-body row">
                         <h4 class="card-title fw-bold">
                                <span id="cardtitle" class="text-primary">Ticket Title</span>
                            </h4>
                            <hr class="hr mb-2 ">
                            <form method="POST" action="{{ route('ticket.update') }}" class="form-inline" id="ticketupdateform"
                            data-parsley-validate="">
                                @csrf

                                <div class="row g-3 mb-3">
                                    <label for="jobcardno" class="col-sm-2 mt-auto">Job Card #</label>
			                        <div class="col-sm-4">
				                        <input class="form-control" type="text" name="jobcardno" id="jobcardno" placeholder="Job Card Number" required data-parsley-required-message="Job Card number is required" autocomplete="off">
			                        </div>

                                    <label for="client" class="col-sm-2 mt-auto">Client</label>
                                    <div class="col-sm-4">
				                        <input class="form-control" type="text" name="client" id="client" placeholder="Client" required data-parsley-required-message="Client is required" autocomplete="off" readonly>
			                        </div>
                                </div>

                                <div class="row g-3 mb-3">
                                    <label for="servicedate" class="col-sm-2 mt-auto">Service Date</label>
                                    <div class="col-sm-4">
                                      <div id="datepicker"class="input-group date" data-date-format="dd-mm-yyyy">
                                        <input class="form-control" type="text" readonly id="servicedate"/>
                                        <span class="input-group-addon">
                                          <i class="fa fa-calendar"></i>
                                        </span>
                                     </div>
                                    </div>
                                    <label for="start_time" class="col-sm-2 mt-auto">Start</label>
                                    <div class="col-sm-4">
                                         
                                        <div class='input-group date' id='datetimepicker2'>
                                            <input class="form-control time" name="start_time" id="start_time" placeholder="00:00" required data-parsley-required-message="Start time is required" autocomplete="off">
                                             
                                            <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                         </div>
				                            
                                      
                                    </div>
                                </div>
                            </form>
                    

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
