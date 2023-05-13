@extends('layouts.app')
@section('title','Mileage Claims')
@section('content')

<div class="container pb-5">
    <div class="row top-row bg-primary mb-2">
        <p class="text-center text-uppercase text-light fw-bold fs-1">MILEAGE Claims</p>

    </div>

  <div class="row "id="claimslist">
    <!--<div class="col-sm-1"></div>-->
   <div class="col-sm-12">
     <div class=" bg-light rounded border pt-5 pb-5 ">

        <div class="row justify-content-sm-center"  >
            <div class="col-sm-12 pl-10 pr-10">
                @empty(!$ticketclaims)
                <div class="alert alert-info text-left " role="alert" id="alertmessage" style="display:block;">
                    <span class="fw-bold">Click an unclaimed service below to update it. Click <a href="{{ route('claims.print') }}">
                      <span class="text-primary fw-bold">here</span></a> to print your claims. </span>
                </div>
                @endempty
             <div class=" justify-content-center">

                <div class="table-responsive  overflow-hidden pt-2" >
                    <table class="table table-responsive table-striped table-bordered table-hover overflow-hidden mb-10" id="projectslist_table">
                        @empty(!$ticketclaims)
                        <thead class="thead-dark">
                            <th style="width: 3%; font-size:13px;" class="text-center">No. </th>
                            <th style="width:9%; font-size:13px;">Ticket #</th>
                            <th style="width:9%; font-size:13px;">JC #</th>
                            <th style="width:15%; font-size:13px;">Client </th>
                            <th style="width:20%; font-size:13px;">Task </th>
                            <th style="width:12%; font-size:13px;">Site </th>
                            <th style="width:8%; font-size:11px;">Serviced on </th>
                            <th style="width:8%;font-size:13px;">Time </th>
                            <th style="width:8%; font-size:13px;">Amount </th>
                            <th class="align-items-center" style="width:10%; font-size:13px;">Status </th>
                        </thead>
                        @endempty
                        <tbody>

                              @forelse ($ticketclaims as $key=> $ticketclaim)
                               <tr class="pl-10 pt-2 pb-10 claiminfo"  id={{$ticketclaim->ticketno}}>
                                 <td class="text-center claiminfo" >{{$key+1}}</td>
                                 <td style="font-size:13px;"> {{$ticketclaim->ticketno}}</td>
                                 <td style="font-size:13px;"> {{$ticketclaim->jobcardno}}</td>
                                 <td style="font-size:13px;"> {{$ticketclaim->clientname}}</td>
                                 <td style="font-size:13px;"> {{$ticketclaim->faultreported}}</td>
                                 <td style="font-size:13px;"> {{$ticketclaim->city}}</td>
                                 <td style="font-size:13px;">
                                    {{\Carbon\Carbon::parse($ticketclaim->start_time)->format('d M,Y')}}
                                 </td>
                                 <td style="font-size:13px;">
                                  {{ Carbon\Carbon::parse($ticketclaim->start_time)->format('H:i') }} to
                                  {{ Carbon\Carbon::parse($ticketclaim->end_time)->format('H:i') }}

                              </td>
                                 <td style="font-size:13px;">
                                    @money($ticketclaim->claimamount)
                                 </td>
                                 <td class="text-center" style="font-size:13px;">
                                    <span class="display">
                                        @if($ticketclaim->claimstatus=="Unclaimed")
                                        <a href="#"><div class="d-inline p-2 bg-danger text-white rounded">
                                            {{$ticketclaim->claimstatus}}
                                        </div>
                                        @elseif($ticketclaim->claimstatus=="Claimed")
                                        <a href="#"><div class="d-inline p-2 bg-success text-white rounded">
                                            {{$ticketclaim->claimstatus}}
                                        </div>
                                         @else
                                        <a href="#"><div class="d-inline p-2 bg-danger text-white rounded">
                                            Unclaimed
                                        </div>
                                        @endif
                                    </a>
                                    </span>
                                 </td>
                               </tr>
                            @empty
                                     <div class="alert alert-info pl-5 pr-5 text-center" role="alert">
                                        No service ticket to claim was found.
                                        Request admin to create a ticket.
                                         <p class="">Go to<a  href="{{ route('dashboard') }}">
                                            <span class="text-primary fw-bold">dashboard</span></a></p>
                                      </div>

                            @endforelse
                        </tbody>
                    </table>

                    <div class="row pl-10 pr-10 pb-10">
                        <div class="col-sm-12">

                        </div>
                    </div>
                </div> <!-- end of table-responsive div> -->

            </div>
            </div>
        </div>
     </div>
   </div>

 </div>

 <div class="row justify-content-center" id="claimupdate">
    <div class="col-sm-12">


        <div class="row bg-light rounded border pt-3 pb-5 justify-content-center">
            <div class="card ml-10 mr-10 pl-10 pr-10 pt-2 pb-2 col-sm-8">
                <div class="alert alert-success text-center" role="alert" id="alertmessage2">
                    <span class="message fw-bold"></span>
                </div>

                <div class="card-body row">
                    <div class="col-sm-8">
                        <h4 class="card-title fw-bold">
                            <span id="cardtitle" class="text-primary">Task Description</span>
                        </h4>
                    </div>
                    <div class="col-sm-2">
                        <h2 class="card-title">
                            <span id="cardtitle-claimamount" class="text-primary fw-bold">Amount</span>
                        </h2>
                    </div>
                    <div class="col-sm-2">
                      <h2 class="card-title">
                          <span id="cardtitle-claimstatus" class="text-primary fw-bold">Status</span>
                      </h2>
                  </div>
                <hr class="hr mb-3">

                 <form method="POST" action="{{ route('claims.update') }}" class="form-inline" id="claimupdateform"
                    data-parsley-validate="">
                        @csrf
                    <div class="form-group row mb-3">
                        <label for="ticketno" class="col-sm-2 col-form-label">Ticket No </label>
                        <div class="col-sm-4">
                           <input type="text" id="ticketno" class="form-control" readonly placeholder="Ticket No." name="ticketno" required >
                       </div>

                        <label for="claimno" class="col-sm-2 col-form-label">Job Card #</label>
                        <div class="col-sm-4">
                          <input type="text" id="jobcardno"class="form-control" readonly placeholder="Job Card No" name="jobcardno" required
                            data-parsley-required-message="Update the ticket to get job card no.">
                        </div>
                      </div>

                      <div class="form-group row">
                        <!--<label for="travelmode" class="col-sm-4 col-form-label">Travel Mode <span class="text-danger">*</span></label>-->

                            <div class="input-group mt-3 col-sm-8">
                                <div class="input-group-prepend">
                                  <label class="input-group-text" for="inputGroupSelect01">Travel Mode *</label>
                                </div>
                                <select class="custom-select" id="travelmode" name="travelmode" required="" data-parsley-required-message="You must select at least one option.">
                                  <option selected="selected" value="">Choose Mode...</option>
                                  <option value="Private">Private</option>
                                  <option value="Public">Public</option>
                                  <option value="Company Provided">Company Provided</option>
                                </select>
                              </div>

                    </div>

                    <div class="row mb-3 mt-3">
                      <div class="col-sm-6 zone">
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <label class="input-group-text" for="zone">Travel Zone *</label>
                          </div>
                          <select class="custom-select" id="zone" name="zone">
                            <option selected="selected" value="Nairobi">Nairobi</option>
                            <option value="Others">Others</option>
                          </select>
                        </div>
                      </div>
                      
                       <label for="rate" class="col-sm-2 col-form-label zone">Zone Rate</label>
                      <div class="col-sm-4">
                        <input type="text" id="rate"class="form-control zone" readonly placeholder="Zone Rate" value="KES. 40 per km" name="rate" readonly>

                      </div>
                    </div>

                     <div class="form-group row mb-3">

                          <label for="kmtravel" class="col-sm-2 col-form-label kmtravel calc">Travel (km)</label>
                          <div class="col-sm-4 kmtravel" >
                            <input type="number" id="kmtravel"class="form-control kmtravel" value="0"
                            data-parsley-trigger="keyup" data-parsley-type='number'required="" name="kmtravel">
                        </div>

                        <label for="kmclaim" class="col-sm-2 col-form-label kmtravel">Km Claim </label>
                        <div class="col-sm-4">
                          <input type="text" id="kmclaim" readonly class="form-control calc kmtravel" value="0"  name="kmclaim">
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="busfare" class="col-sm-2 col-form-label busfare">Fare *</label>
                        <div class="col-sm-4">
                          <input type="number" id="busfare"class="form-control busfare calc"  value="0"
                            data-parsley-trigger="keyup" data-parsley-type='number'required="" name="busfare">
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="companyprovided" class="col-sm-2 col-form-label companyprovided">Claim</label>
                        <div class="col-sm-4">
                          <input type="number" id="companyprovided"class="form-control companyprovided"
                             data-parsley-type='number' value="0.00"name="companyprovided" readonly>
                        </div>
                      </div>

                      <div class="form-group row mb-3 mt-3">
                        <label for="lunch" class="col-sm-2 col-form-label">Lunch</label>
                         <div class="col-sm-4">
                            <input type="number" id="lunch"class="form-control calc" value="0.00"
                                    data-parsley-trigger="keyup" data-parsley-type='number'required="" name="lunch">
                          </div>

                         <label for="dinner" class="col-sm-2 col-form-label">Dinner </label>
                         <div class="col-sm-4">
                           <input type="number" id="dinner"class="form-control calc" value="0.00"
                              data-parsley-trigger="keyup" data-parsley-type='number'required="" name="dinner">
                         </div>


                     </div>

                     <div class="form-group row mb-3">
                        <label for="petties" class="col-sm-2 col-form-label">Petties </label>
                        <div class="col-sm-4">
                          <input type="number" id="petties"class="form-control calc" value="0.00"
                              data-parsley-trigger="keyup" data-parsley-type='number'required="" name="petties">
                        </div>

                        <label for="accommodation" class="col-sm-3 col-form-label">Accommodation</label>
                        <div class="col-sm-3">
                          <input type="number" id="accommodation"class="form-control calc" value="0.00"
                             data-parsley-trigger="keyup" data-parsley-type='number'required="" name="accommodation">
                        </div>


                    </div>

                    <div class="form-group row mb-3">

                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-4">
                            <button type="submit" id="claimsubmit" class="btn btn-block btn-primmary text-sucess" >
                                <i class="fa fa-refresh" aria-hidden="true"></i> Update Claim
                            </button>
                        </div>
                        <div class="col-sm-4">
                            <button type="button" class="btn btn-block btn-danger text-danger resetform" >
                                <i class="fa-solid fa-eraser"></i> Clear
                            </button>
                        </div>
                        <div class="col-sm-2"></div>
                    </div>
                </form>

               </div>
               <div class="card-footer row">
                <div class="col-sm-4">
                  <button class="btn btn-sm bg-info rounded grow text-light" type="button" id="showlist" >
                    <i class="fa-solid fa-arrow-left"></i> Go Back
                </button>
                </div>
                <div class="col-sm-8">
                  <div class="alert alert-info text-center" role="alert" id="alertmessage" style="display:block;">
                    Click <a href="{{ route('claims.print') }}">
                      <span class="text-primary fw-bold">here</span></a> to print your claims. </span>
                </div>
                </div>
               </div>
             </div>

        </div>
 </div>
</div>
@endsection
