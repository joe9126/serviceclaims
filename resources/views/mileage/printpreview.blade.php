@extends('layouts.app')
@section("title","Print Claims")
@section('content')
 
     <!-- PRINT OUT SECTION -->
     <div id="showprintout">
        <div class="row mb-3 toolbar hidden-print">
            <div class="col-sm-12  d-flex justify-content-end">
                <button class="btn btn-primary mr-3" id="printclaims"><i class="fa fa-print"></i> Print</button>
                <button class="btn btn-danger" id="exitbtn"><i class="fa fa-arrow-left"></i> Exit</button>
            </div>
          
        </div>

        <div class="row  pt-3 pb-3 mt-3 mb-3">
         <div class="col-sm-1"></div>
 
         <div class="col-sm-10  ">
            
 
          <div class="printout justify-content-center pl-3 pr-3 pt-2 page" id="printpage">
            <header>
               <div class="row  mb-2 mt-2">
                 <div class="col-sm-3">
                     <img src="{{url('/images/symphony.png')}}" data-holder-rendered="true" />
                 </div>
                 <div class="col-sm-9">
                     <p class="text-center text-uppercase text-dark fw-bold display-6">Mileage Claims</p>
                 </div>
                </div>
                 <hr class="hr pb-3 mb-3">
                </header>

                <main>
                  <div class="row">
                     <div class="col-sm-4">
                         <table class="table table-borderless table-sm" style="font-size: 12px;">
                             <tbody>
                                <thead>
                                    <th colspan="2" class="fw-bold text-left fs-6"><u>Staff Info</u></th>
                                </thead>
                                 <tr>
                                     <td class="fw-bold">Name: </td><td>{{ Auth::user()->name }} </td>
                                     <td class="fw-bold">ID:</td><td>{{ Auth::user()->idnumber }}</td>
                                 </tr>
                                 <tr>
                                    
                                     <td class="fw-bold">Email:</td><td>{{ Auth::user()->email }}</td>
                                     <td class="fw-bold">Phone:</td><td>{{ Auth::user()->phone }}</td>
                                 </tr>
                            </tbody>
                         </table>
 
                     </div>
                     <div class="col-sm-4">
                        <td class="fw-bold">Claim No: </td><td><span id="claimno"></span></td>
                    </div>
                     <div class="col-sm-4">
                         <p class="fw-bold fs-6 text-end"> Claimed on {{ \Carbon\Carbon::now()->format("d M,Y") }}</p>
                     </div>
 
                 </div>
                 <div class="row ">
                    <div class="col-sm-12">
                     <div class="table-responsive">
                        <table class="table table-bordered table-striped text-xsmall" id="claimprintouttable" style="font-size: 12px;">
                             <thead class="thead-dark">
                                  <th style="width: 5%;">No</th>
                                  <th style="width: 10%;">Job Card#</th>
                                  <th style="width: 15%">Bill Ref#</th>
                                  <th style="width: 10%">Date</th>
                                  <th style="width: 12%">Time</th>
                                  <th style="width: 22%">Client</th>
                                 <th style="width: 13%">Location</th>
                                 <th style="width: 13%">Total Amount</th>
                             </thead>
                            <tbody>
                              @if(is_array(json_decode($claimsdata)))
                              @php $grandtotal = 0;@endphp
                                @forelse($claimsdata as $key=>$claim)
                                 @php $grandtotal +=$claim->amount; @endphp
                                    <tr>
                                        <td style="text-align: center;font-size:12px;">{{ $key+1 }}</td>
                                        <td style="text-align: left;font-size:12px;">{{ $claim->jobcardno }}</td>
                                        <td style="text-align: left;font-size:12px;">{{ $claim->billingrefno }}</td>
                                        <td style="text-align: left;font-size:12px;">{{ $claim->date }}</td>
                                        <td style="text-align: justify;font-size:12px;">{{ $claim->time }}</td>
                                        <td style="text-align: justify;font-size:12px;">{{ $claim->client }}</td>
                                       <td style="text-align: justify;font-size:12px;">{{ $claim->location }}</td>
                                        <td style="text-align: justify;font-size:12px;">@money($claim->amount)</td>
                                    </tr>
                                    @empty
                                    <p>no data found</p>
                                @endforelse
                                @else
                                <p>data not found</p>
                              
                               @endif
                            </tbody>
                           <tfoot>
                            <tr>
                                <td colspan='7' class="text-right fw-bold">Grand Total </td>
                                <td class="fw-bold" ">@money($grandtotal)</td>
                            </tr>
                            <tr>
                                <td colspan="7" class="text-right" >Less Advance </td><td>KES. </td>
                            </tr>
                            <tr>
                                <td colspan="7" class="text-right fw-bold">Net To Pay </td><td>KES. </td>
                            </tr>
                           </tfoot>
                         </table> 
                     </div>
                 </div>
             </div>
 
             <div class="row mt-3">
                 <div class="col-sm-12">
                     <table class="table table-borderless table-sm">
                         <thead class="thead-dark" style="background:rgba(65, 70, 67, 0.4); color:aliceblue;">
                             <th colspan="4" class="text-center"><span class="fw-bold fs-3">Approvals</span></th>
                         </thead>
                         <tbody>
                             <tr>
                                 <td class="fw-bold fs-6">Staff Signature</td>
                                 <td>____________________________________</td>
                                 <td class="fw-bold">Date</td>
                                 <td>____________________________________</td>
                             </tr>
                             <tr>
                                 <td class="fw-bold">Manager's Signature</td>
                                 <td>____________________________________</td>
                                 <td class="fw-bold">Date</td>
                                 <td>____________________________________</td>
                             </tr>
                             <tr>
                                 <td class="fw-bold">Finance Dept.</td>
                                 <td>____________________________________</td>
                                 <td class="fw-bold">Date</td>
                                 <td>____________________________________</td>
                             </tr>
                         </tbody>
                     </table>
                 </div>
              </div>
            </main>
             </div>
          </div> 
         </div>
         <div class="col-sm-1"></div>
       </div> <!-- end of print preview -->
@endsection