@extends('layouts.app')
@section("title","Print Claims")
@section('content')

<div class="container pb-5">
     <div class="row top-row bg-primary mb-2">
        <p class="text-center text-uppercase text-light fw-bold fs-1">Print Claims</p>
     </div>

     <div class=" bg-light rounded border pt-5 pb-5 ">
      <div id="showclaimlist">
       <div class="row grid-container ">
        <div class="col-sm-1"></div>
        <div class="col-sm-10">
            @empty(!$claims)
            <div class="alert alert-info text-left" role="alert" id="alertmessage2" style="display:block;">
                <span class="fw-bold">Select a claim to remove it from the list or delete. Click <a href="{{ route('claims.mileage') }}">
                    <span class="text-primary fw-bold">here</span></a> to make changes to the claims. </span>
            </div>
            @endempty

            <div class="alert alert-success" role="alert" id="alertmessage">
                <span class="message"></span>
                @empty($claims)
                @else
                <div class="spinner-border text-success" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                @endempty
            </div>

            <div class="row mt-3 mb-4">
                <div class="col-sm-6">
                    <h2 class=" text-primary fs-2">
                        @if($claims !=null)
                        @php $totalclaim =0 @endphp
                        @foreach ($claims as $key=>$claim)
                         @php $totalclaim += $claim->claimamount @endphp
                         @endforeach
                         Total Claim <span id="totalamounttxt"> @money( $totalclaim) </span>
                         @else
                         Total Claim KES. 0.00
                         @endif
                    </h2>
                </div>
                <div class="col-sm-2">
                    <button type="button" class="btn btn-sm btn-block btn-primary text-primary" id="printclaimpreviewbtn">
                        <i class="fa fa-eye"></i> Print Preview
                    </button>
                </div>
                <div class="col-sm-2">
                    <button type="button" id="removeclaimbtn" class="btn btn-sm btn-block btn-warning text-warning">
                        <i class="fa fa-xmark"></i> Remove
                    </button>
                </div>
                <div class="col-sm-2">
                    <button type="button" class="btn btn-sm btn-block btn-danger text-danger" id="deleteclaimbtn">
                        <i class="fa fa-trash"></i> Delete
                    </button>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-sm-12">
                    <div class="table-responsive overflow-hidden">
                        <table class="table table-hover table-bordered table-striped " id="claimprinttable">
                            @empty($claims) <!-- if data found -->
                            @else
                             <thead class="thead-dark ">
                                <th style="width: 3%" class="align-items-center"> </th>
                                <th style="width: 3%">No</th>
                                <th style="width: 8%; display:none;">Ticket#</th>
                                <th style="width: 8%">Date</th>
                                <th style="width: 10%">Time</th>
                                <th style="width: 10%">Job Card#</th>
                                <th style="width: 12%">Bill Ref#</th>
                                <th style="width: 18%">Client</th>
                                <th style="width: 20%">Task</th>
                                <th>Location</th>
                                
                                <th style="width: 10%">Amount</th>
                            </thead>
                            @endempty
                            <tbody>
                                @forelse ($claims as $key=>$claim)
                                    <tr id='{{ $claim->ticketno }}'>
                                        <td class="text-center">
                                            <input class="form-check-input" type="checkbox" value="" name="flexCheckDefault">
                                        </td>
                                        <td style="text-align: center;">{{ $key+1 }}</td>
                                        <td style="display:none;">{{ $claim->ticketno }}</td>
                                        <td>
                                            {{\Carbon\Carbon::parse($claim->ticketdate)->format('d M,Y')}}
                                        </td>
                                        <td>
                                            {{ Carbon\Carbon::parse($claim->start_time)->format('H:i') }} to
                                            {{ Carbon\Carbon::parse($claim->end_time)->format('H:i') }} 
                                          
                                        </td>
                                        <td>{{ $claim->jobcardno }}</td>
                                        <td>{{ $claim->billingrefno }}</td>
                                        <td>{{ $claim->clientname }}</td>
                                        <td>{{ $claim->faultreported }}</td>
                                        <td>{{ $claim->location }}</td>
                                       
                                        <td>@money($claim->claimamount)</td>
                                    </tr>

                                @empty
                                <div class="alert alert-info text-center" role="alert">
                                    No claim found. Click <a href="{{ route('claims.mileage') }}">
                                        <span class="text-primary fw-bold">here</span></a> to update your claims.
                                  </div>
                                @endforelse
                            </tbody>
                            <tfoot></tfoot>
                        </table>
                        <div class="row pl-10 pr-10 pb-10">
                            <div class="col-sm-12">
                              
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      <div class="col-sm-1"></div>

     </div>
     </div>



     </div>
    </div>

@endsection
