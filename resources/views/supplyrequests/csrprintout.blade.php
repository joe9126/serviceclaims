@extends('layouts.app')

@section('title','Supply Request')
@section('navigation')
@stop
@section('content')



<div id="invoice" class="bg-white">
    <div class="toolbar hidden-print">

        <div class="text-right">

            <button id="printInvoice" class="btn btn-info"><i class="fa fa-print"></i> Print</button>

            <button class="btn btn-info"><i class="fa fa-file-pdf-o"></i> Export as PDF</button>

        </div>

        <hr>

    </div>

    <div class="invoice overflow-auto">

        <div style="min-width: 600px">

            <header class="pl-3 pr-3 mt-5">

                <div class="row">

                    <div class="col">

                        <img src="{{url('images/symphony.png')}}" height='200' width='200' data-holder-rendered="true" />

                    </div>

                    <div class="col company-details">

                        <span class="fs-4">CUSTOMER SUPPLY REQUEST </span>

                        <div>&nbsp;</div>

                        <div>&nbsp;</div>

                        <div>&nbsp;</div>

                    </div>

                </div>

            </header>

            <main class="pl-3 pr-3">

                <div class="row contacts">

                     <div class="col invoice-to">

                        <div class="text-gray-light fw-bold">SUPPLY TO:</div>

                        @foreach($csrdata as $data)

                        @php $currency=$data->currency @endphp

                        <h2 class="to">{{$data->clientname}} </h2>

                        <div class="address">{{$data->address}}</div>

                        <div class="address">{{$data->city}}</div>

                        <div class="email"><a href="mailto:john@example.com">{{$data->email}}</a></div>

                         <div class="address"></div>

                         <div class="address">{{$data->contactperson}}|{{$data->phone}}</div>

                        @endforeach

                    </div>

                    <div class="col supply-details">

                        <div class="text-gray-light fw-bold">SUPPLY DETAILS:</div>

                        <h1 class="invoice-id">CSR #:{{$data->csrno}}</h1>

                        <div class="address"><span class="maintext">CSR Date:</span> {{$data->csrdate}}</div>

                        <div class="address"><span class="maintext">PO Number:</span> {{$data->ponumber}}</div>

                        <div class="address"><span class="maintext">PO Date: </span>{{$data->podate}}</div>

                    </div>

                </div>



                 <div class="row mt-3">

                    <div class="col-sm-12">

                        <span class="var_text2 fw-bold">ORDER DESCRIPTION:</span>

                        @foreach($csrdata as $data)

                                <span class="var_text2">{{$data->description }}</span>

                        @endforeach

                    </div>

                </div>

                <div class="divider"></div>



                <table class="table table-striped table-sm table-bordered" id="printcsritemstable">

                    <thead class="thead-dark">

                        <tr style="border:1px solid #ccc;">

                            <th>No.</th>

                            <th class="text-left">Part No</th>

                            <th class="text-right">Description</th>

                            <th class="text-right">Qty.</th>

                            <th class="text-right">Unit Cost</th>

                            <th class="text-right">Markup %</th>

                            <th class="text-right">Unit S.P</th>

                            <th class="text-right">VAT %</th>

                            <th class="text-right">Total</th>

                        </tr>

                    </thead>

                    <tbody>

                         @foreach($csritems as $data)

                          <tr>

                             <td>{{$data->id}}</td>

                             <td>{{$data->partno}}</td>

                             <td>{{$data->name}}</td>

                             <td>{{number_format($data->qty)}}</td>

                             <td>{{number_format($data->unitbp,2)}}</td>

                             <td>{{number_format($data->markup,2)}}</td>

                             <td>{{$data->currency}} {{number_format($data->unitsp,2)}}</td>

                             <td>{{number_format($data->vatrate,2)}}</td>

                             <td>{{$data->currency}} {{number_format($data->itemtotal,2)}}</td>

                        </tr>

                         @endforeach

                          @php $grandtotal=0 @endphp

                        @foreach ($csritems as $data)

                        @php $grandtotal = $data->itemtotal + $grandtotal @endphp

                        @endforeach

                        <tr>

                            <td colspan="8" style="text-align: right; font-weight: bold;"> Grand Total {{$currency}}:</td>

                            <td>{{number_format($grandtotal,2)}}</td>

                        </tr>



                    </tbody>

                    <tfoot class="thead-dark">



                    </tfoot>

                </table>



                <div class="notices">

                    <div class="fw-bold">NOTICE:</div>

                    <div class="notice">CSR Attachments can be downloaded from the portal.</div>

                </div>

            </main>

            <footer class="mt-5 pl-3">
                <small>CSR was created on a computer and is valid without the signature and seal. </small>
            </footer>

        </div>

        <!--DO NOT DELETE THIS div. IT is responsible for showing footer always at the bottom-->

        <div></div>

    </div>

</div>







<!--

<div class="container-fluid">

    <span class="h1">CUSTOMER SUPPLY REQUEST</span>

    <div class="row">

        <div class="col-lg-6">



        </div>

        <div class="col-lg-6">

            <div class="letterheadlogo"></div>

        </div>

    </div>



    <div class="row">

        <div class="col-lg-6">

            <span class="maintext">Date:</span> <span class="var_text">{{Carbon\Carbon::now()->format('d M Y')}}</span>

        </div>

    </div>

    <div class="row">

        <div class="col-lg-6">

            <span class="maintext">CSR #:</span> <span class="var_text">{{Request::get('trans_no') }}</span>

        </div>

    </div>



    <div class="row">

         <div class="col-lg-6">

            <span class="maintext"> Client & Address</span><br>

            @foreach($csrdata as $data)

                <span class="var_text">{{$data->clientname }}</span><br>

                <span class="var_text">{{$data->address}}</span><br>

                <span class="var_text">{{$data->city}}</span>

            @endforeach

        </div>

         <div class="col-lg-3">

            <span class="maintext">Order Number</span><br>

             @foreach($csrdata as $data)

                <span class="var_text">{{$data->ponumber }}</span>

            @endforeach

        </div>

        <div class="col-lg-3">

             <span class="maintext">Order Date</span><br>

            @foreach($csrdata as $data)

                <span class="var_text">{{$data->podate }}</span><br>

            @endforeach

        </div>

    </div>



    <div class="row">

        <div class="col-lg-12">

             <span class="maintext">Contact Person</span><br>

             @foreach($csrdata as $data)

                <span class="var_text">{{$data->contactperson }}| {{$data->phone }}| {{$data->email }}</span>

            @endforeach

        </div>

    </div>



    <div class="row">

        <div class="col-lg-12">

             <span class="h1">ORDER DESCRIPTION:</span>

             @foreach($csrdata as $data)

                <span class="var_text">{{$data->description }}</span>

            @endforeach

         </div>

    </div>



    <div class="row">

        <div class="col-lg-12">

            <table class="table table-striped table-bordered table-sm" id="csritemstable">

                <thead class="thead-light">

                    <tr>

                        <th>No</th>

                        <th>Part No</th>

                        <th>Description</th>

                        <th>Qty</th>

                        <th>Unit Cost</th>

                        <th>Markup %</th>

                        <th>Unit S.P</th>

                        <th>VAT %</th>

                        <th>Total</th>



                    </tr>

                </thead>

                <tbody></tbody>

                <tfoot>

                    <tr>

                        <th colspan="8">Grand Total VAT Inc.</th>

                        <th></th>

                    </tr>

                </tfoot>

            </table>

        </div>

    </div>



    <div class="row">

        <div class="col-lg-6">

            <span class="maintext"> Sales Person: </span>

            @foreach($csrdata as $data)

                <span class="var_text">{{$data->saleperson }}</span>

            @endforeach

        </div>

        <div class="col-lg-6">

             <span class="maintext"> Printed By: </span>

             <span class="var_text">{{Auth::user()->name }}</span>

        </div>

    </div>

</div>



-->

@endsection

<script type="text/javascript">

    //window.print();

</script>
