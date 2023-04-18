@extends('layouts.app')
@section('title','Service Ticket Claims')
@section('content')

<div class="container pb-5">
    <div class="row top-row bg-primary mb-2">
        <p class="text-center text-uppercase text-light fw-bold fs-1">SERVICE TICKET Claims</p>

    </div>

  <div class="row "id="claimslist">
    <!--<div class="col-sm-1"></div>-->
   <div class="col-sm-12">
     <div class=" bg-light rounded border pt-5 pb-5 ">

        <div class="row justify-content-sm-center"  >
            <div class="col-sm-12 pl-5 pr-5">
                @empty(!$ticketclaims)
                <div class="alert alert-info text-left " role="alert" id="alertmessage" style="display:block;">
                    <span class="fw-bold">Click an unclaimed ticket below to update it</span>
                </div>
                @endempty
             <div class=" justify-content-center">
                
                <div class="table-responsive  overflow-hidden pl-15 pr-15 pt-2" >
                    <table class="table table-responsive table-striped table-bordered table-hover overflow-hidden mb-10" id="projectslist_table">
                        @empty(!$ticketclaims)
                        <thead class="thead-dark">
                            <th style="width: 3%" class="text-center">No. </th>
                            <th style="width:9%;">Ticket #</th>
                            <th style="width:15%;">Client </th>
                            <th style="width:20%;">Task </th>
                            <th style="width:12%;">Location </th>
                            <th style="width:10%;">Service Date </th>
                            <th style="width:10%;">Time </th>
                            <th style="width:10%;">Amount </th>
                            <th class="align-items-center" style="width:10%;">Status </th>
                        </thead>
                        @endempty
                        <tbody>

                              @forelse ($ticketclaims as $key=> $ticketclaim)
                               <tr class="pl-10 pt-2 pb-10"  id={{$ticketclaim->ticketno}}>
                                 <td class="text-center" >{{$key+1}}</td>
                                 <td> {{$ticketclaim->ticketno}}</td>
                                 <td> {{$ticketclaim->clientname}}</td>
                                 <td> {{$ticketclaim->faultreported}}</td>
                                 <td> {{$ticketclaim->location}}</td>
                                 <td> 
                                    {{\Carbon\Carbon::parse($ticketclaim->ticketdate)->format('d M,Y')}}
                                 </td>
                                 <td>
                                  {{ Carbon\Carbon::parse($ticketclaim->start_time)->format('H:i') }} to
                                  {{ Carbon\Carbon::parse($ticketclaim->end_time)->format('H:i') }} 
                                
                              </td>
                                 <td>
                                    @money($ticketclaim->claimamount)
                                 </td>
                                 <td class="text-center">
                                    <span class="display">
                                        @if($ticketclaim->claimstatus=="Unclaimed")
                                        <a href="#"><div class="d-inline p-2 bg-danger text-white rounded">
                                            {{$ticketclaim->claimstatus}}
                                        </div>
                                        @else
                                        <a href="#"><div class="d-inline p-2 bg-success text-white rounded">
                                            {{$ticketclaim->claimstatus}}
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
            <div class="card ml-10 mr-10 pl-10 pr-10 pt-2 pb-5 col-sm-6">
                <div class="alert alert-success text-center" role="alert" id="alertmessage2">
                    <span class="message fw-bold"></span>
                </div>

                <div class="card-body row">
                    <div class="col-sm-8">
                        <h4 class="card-title fw-bold">
                            <span id="cardtitle" class="text-primary"></span>
                        </h4>
                    </div>
                    <div class="col-sm-4">
                        <h2 class="card-title">
                            <span id="cardtitle-claimamount" class="text-primary fw-bold"></span>
                    </div>
                </h2><hr class="hr mb-3">

                 <form method="POST" action="{{ route('claims.update') }}" class="form-inline" id="claimupdateform"
                    data-parsley-validate="">
                        @csrf
                    <div class="form-group row mb-3">
                        <label for="ticketno" class="col-sm-2 col-form-label">Ticket No </label>
                        <div class="col-sm-4">
                           <input type="text" id="ticketno" class="form-control" readonly placeholder="Ticket No." name="ticketno" required>
                       </div>

                        <label for="claimno" class="col-sm-2 col-form-label">Claim No</label>
                        <div class="col-sm-4">
                          <input type="text" id="claimno"class="form-control" readonly placeholder="Claim No" name="claimno" required>
                        </div>
                      </div>

                      <div class="form-group row mb-3">
                        <!--<label for="travelmode" class="col-sm-4 col-form-label">Travel Mode <span class="text-danger">*</span></label>-->
                       
                            <div class="input-group mt-3 col-sm-8">
                                <div class="input-group-prepend">
                                  <label class="input-group-text" for="inputGroupSelect01">Travel Mode *</label>
                                </div>
                                <select class="custom-select" id="travelmode" required="" data-parsley-required-message="You must select at least one option.">
                                  <option value="">Choose...</option>
                                  <option value="Private">Private</option>
                                  <option value="Public">Public</option>
                                  <option value="Company Provided">Company Provided</option>
                                </select>
                              </div>
                       
                    </div>

                      <div class="form-group row ">

                          <label for="kmtravel" class="col-sm-2 col-form-label kmtravel calc">Travel (km)</label>
                          <div class="col-sm-4 kmtravel" >
                            <input type="number" id="kmtravel"class="form-control kmtravel" placeholder="0"
                            data-parsley-trigger="keyup" data-parsley-type='number'required="" name="km">
                        </div>

                        <label for="kmclaim" class="col-sm-2 col-form-label kmtravel">Km Claim </label>
                        <div class="col-sm-4">
                          <input type="text" id="kmclaim" readonly class="form-control calc kmtravel" placeholder="0.00" name="kmclaim">
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="busfare" class="col-sm-2 col-form-label busfare">Fare *</label>
                        <div class="col-sm-4">
                          <input type="number" id="busfare"class="form-control calc busfare" placeholder="0.00"
                            data-parsley-trigger="keyup" data-parsley-type='number'required="" name="psvfare">
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="companyprovided" class="col-sm-2 col-form-label companyprovided">Claim</label>
                        <div class="col-sm-4">
                          <input type="number" id="companyprovided"class="form-control companyprovided" placeholder="0.00"
                             data-parsley-type='number' value="0.00"name="companyprovided" readonly>
                        </div>
                      </div>

                      <div class="form-group row mb-3 mt-3">
                        <label for="lunch" class="col-sm-2 col-form-label">Lunch</label>
                         <div class="col-sm-4">
                            <input type="number" id="lunch"class="form-control calc" placeholder="0.00"
                                    data-parsley-trigger="keyup" data-parsley-type='number'required="" name="lunch">
                          </div>

                         <label for="dinner" class="col-sm-2 col-form-label">Dinner </label>
                         <div class="col-sm-4">
                           <input type="number" id="dinner"class="form-control calc" placeholder="0.00"
                              data-parsley-trigger="keyup" data-parsley-type='number'required="" name="dinner">
                         </div>

                        
                     </div>

                     <div class="form-group row mb-3">
                        <label for="petties" class="col-sm-2 col-form-label">Petties </label>
                        <div class="col-sm-4">
                          <input type="number" id="petties"class="form-control calc" placeholder="0.00"
                              data-parsley-trigger="keyup" data-parsley-type='number'required="" name="petties">
                        </div>

                        <label for="accommodation" class="col-sm-3 col-form-label">Accommodation</label>
                        <div class="col-sm-3">
                          <input type="number" id="accommodation"class="form-control calc" placeholder="0.00"
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
                  <div class="alert alert-info text-left" role="alert" id="alertmessage" style="display:block;">
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