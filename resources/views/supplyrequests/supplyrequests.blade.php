@extends('layouts.app') 
@section('title','Supply Requests') 
@section('content') 
<div class="container-fluid overflow-hidden"> 
    <div class="main-body"> 
        <div class="row top-row bg-primary mb-2"> 
            <p class="text-center text-uppercase text-light fw-bold fs-1" id="pagetitle">Supply Requests</p> 
        </div> 

        <div class="row mt-3" id="csrinfosection">  
            <div class="col-sm-12"> 
                <div class="card pl-5 pr-5 pt-3 pb-3" > 

                    <div class="row justify-content-center"> 
                        <div class="col-sm-10"> 

                            <div class="" id="accordion"> 
                                <p class="fw-bold fs-4">{{now()->format('Y')}} Sales Summary</p> 
                                <canvas id="myChart"></canvas>  
                            </div> 
                        </div>  
                    </div>  
                </div> 

            </div> 

        </div> 

      <div id="csrpanel">



        <div class="row mt-3" id="csrdisplay">



            <div class="col-sm-12 ">



                <div class="card pl-5 pr-5 pt-3 pb-3">

                     <div class="row mb-3">

                            <div class="col-sm-5">

                                <p>

                                    <span class="fs-5 fw-bold">Customer Supply Requests </span>

                                    <small class="text-primary">Click an item to view,make changes or download files</small>

                                </p>

                            </div>

                            <div class="col-sm-5">

                                <div class="alert alert-success text-center" role="alert" id="alertmessage2">

                                    <span class="message fw-bold"></span>

                                  </div>

                            </div>



                            <div class="col-sm-2">



                                <button class="btn btn-small btn-dark" id="newcsrbtn"> 



                                    <i class="fa fa-plus-square" aria-hidden="true"></i>&nbsp;New CSR 



                                </button>



                            </div>



                        </div>



                    <div class="table-responsive overflow-hidden"> 

                        <table class="table table-sm table-hover table-striped table-bordered" id="csrtable"> 



                          <thead class="thead-dark"> 



                             <th >No</th> 



                             <th >CSR Date</th> 



                             <th >CSR No</th> 



                             <th >Client</th> 



                             <th >Description</th> 



                             <th >Po No</th> 



                             <th >Value VAT incl</th> 



                             <th >Sales Person</th> 



                             <th >Billing Status</th> 



                           </thead> 



                            <tbody>	 



                                @empty(!$csrs)



                                @foreach($csrs as $key=>$item)



                                <tr id="{{ $item->csrno }}">



                                    <td style="width:2%;" class="text-center" >{{$key+1}}</td>



                                    <td >



                                        {{\Carbon\Carbon::parse($item->csrdate)->format('d M,Y')}}



                                    </td>



                                    <td >{{$item->csrno}}</td>



                                    <td style="width:10%;" >{{$item->clientname}}</td>



                                    <td style="width:10%;">{{$item->description}}</td>



                                    <td >{{$item->ponumber}}</td>



                                     <td >



                                        {{$item->currency}}   {{number_format($item->csrvalue,2)}}



                                    </td>



                                    <td style="width:5%;">{{$item->saleperson}}</td>



                                    <td style="width:8%;">



                                        <span class="display">



                                            @if($item->status=="Order Placement")



                                            <a href="#">



                                                <div class="d-inline p-2 bg-danger text-white rounded">



                                                   {{$item->status}}



                                                </div>



                                            </a>



                                            @elseif($item->status=="Invoiced")



                                            <a href="#">



                                                <div class="d-inline p-2 bg-success text-white rounded">



                                                 {{$item->status}}



                                              </div>



                                            </a>



                                             @else



                                            <a href="#">



                                                <div class="d-inline p-2 bg-danger text-white rounded">



                                                  {{$item->status}}



                                                </div>



                                            </a>



                                            @endif



                                       



                                        </span>



                                       



                                    </td> 



                                </tr>



                                @endforeach



                                @endempty



                            </tbody>







                      </table>







                  </div>



                </div>



            </div>



        </div>







        <div class="row" id="createcsrsection"> <!-- create new csr panel -->



            <div class="col-sm-12">



                <div class="card pl-5 pr-5 pt-3 pb-3">



                    <div class="row">



                        <div class="col-sm-4">



                            <p class="fs-2 fw-bold">Customer Supply Request</p>



                        </div>



                        <div class="col-sm-4">



                            <div class="alert alert-success text-center mt-3" role="alert" id="alertmessage">



                                <span class="message fw-bold"></span>



                            </div>



                        </div>



                        <div class="col-sm-4 d-flex justify-content-end">



                            <button class="btn text-danger goback"><i class="fa fa-arrow-left"></i> Go Back</button>



                        </div> 



                    </div><hr class="hr mb-2">







                    <div class="row">



                    



                     <div class="col-sm-3" style="border-right: 1px solid #ccc">



                            



		   			<form id="newcsrForm"action="" method="Post">







		   			 <div class="row form-row">







		   				<div class="col-sm-3"><label class="formlabel" for="csrno">CSR No</label></div>







		   				<div class="col-sm-9">







		   					<input class="form-control" id="csrno" name="csrno" placeholder="CSR No" autocomplete="off" required data-parsley-required-message="CSR number is required!"/>







		   				</div>







		   			</div>







		   			<div class="row form-row">







		   				<div class="col-sm-3"><label class="formlabel" for="clientname">Client</label></div>







		   				<div class="col-sm-9">







		   					<select class="form-control" id="clientid" name="clientid" 



                                onmousedown="if(this.options.length>8){this.size=8;}"  onchange='this.size=0;' onblur="this.size=0;"



								required data-parsley-required-message="Client Name is required!">







		   				 	<option value="">Select Client</option>







		   				 </select>







		   				</div>







		   			</div>







		   			<div class="row form-row">







		   				<div class="col-sm-3"><label class="formlabel"for="description">Description</label></div>







		   				<div class="col-sm-9">







		   					<input class="form-control" id="description" name="description"  placeholder="description" autocomplete="off"	 required data-parsley-trigger="keyup" data-parsley-required-message="Description is required!"/>







		   				</div>







		   			</div>







		   			<div class="row form-row">







		   				<div class="col-sm-3"><label class="formlabel"for="csrdate">CSR Date</label></div>







		   				<div class="col-sm-9">







		   					<input class="form-control csrdate" type="text" id="csrdate" name="csrdate"  placeholder="mm-dd-yyyy" required  data-parsley-trigger="keyup" data-parsley-required-message="CSR Date required!"/>







		   				</div>







		   			</div>















		   			<div class="row form-row">







		   				<div class="col-sm-3"><label class="formlabel"for="ponumber">PO No</label></div>







		   				<div class="col-sm-9">







		   					<input class="form-control" id="ponumber" name="ponumber"  placeholder="PO Number" 







		   					required data-parsley-trigger="keyup" data-parsley-required-message="PO Number required" autocomplete="off"/>







		   				</div>







		   			</div>







		   			<div class="row form-row">







		   				<div class="col-sm-3"><label class="formlabel"for="podate">PO Date</label></div>







		   				<div class="col-sm-9">







		   					<input type="text" class="form-control csrdate" id="podate" name="podate"  required placeholder="mm-dd-yyyy" data-parsley-required-message="PO date is required"/>







		   				</div>







		   			</div>







		   			<div class="row form-row">







		   				<div class="col-sm-3"><label class="formlabel"for="currency">Currency</label></div>







		   				<div class="col-sm-9">







		   					<select class="form-control" id="currency" name="currency"  required data-parsley-required-message="Currency is required">







		   						<option value="">Select Currency</option> 



		   						<option value="KES">KES</option> 



		   						<option value="USD">USD</option> 



		   						<option value="EUR">EUR</option> 



		   					</select>







		   				</div>







		   			</div>















		   			<div class="row form-row">







		   				<div class="col-sm-3"><label class="formlabel"for="csrtotal">CSR Value</label></div>







		   				<div class="col-sm-9"><input type="text" class="form-control" id="csrvalue" name="csrvalue"  placeholder="0.00" readonly="" />







		   				</div>







		   			</div>















		   			<div class="row form-row">







		   				<div class="col-sm-3"><label class="formlabel"for="salespersons1">Sales Person</label></div>







		   				<div class="col-sm-9">







		   					<select class="form-control" id="salespersons1" name="salespersons1" 



                               onmousedown="if(this.options.length>8){this.size=8;}"  onchange='this.size=0;' onblur="this.size=0;"



                                required data-parsley-required-message="Sales person is required">







		   						<option value="">Select Sales Person</option>







		   					</select>







		   				</div>







		   			</div> 



		   			<div class="row form-row"> 



		   				<div class="col-sm-3"><label class="formlabel"for="status">Status</label></div> 

		   				<div class="col-sm-9"> 



		   					<select class="form-control" id="status" name="status"> 



		   						<option value="Order Placement">Select Status</option> 



		   						<option value="Order Placement">Order Placement</option>  



		   						<option value="Sign-off/Hand-over">Sign-off/Hand-over</option> 



		   						<option value="Invoiced">Invoiced</option> 



		   						<option value="Paid & Closed">Paid & Closed</option> 



		   					</select> 



		   				</div> 

		   			</div> 



                     <div class="row form-row">







                        <div class="col-sm-3"><label class="formlabel"for="sendto">Send To</label></div>







                        <div class="col-sm-9">







                        <select class="form-control" name="sendto" id="sendto"



                          onmousedown="if(this.options.length>5){this.size=5;}"  onchange='this.size=0;' onblur="this.size=0;">







                            <option value="">Select Recipient</option>







                        </select>







                    </div>







                    </div>







                     <div class="row form-row">







                         <table id="recipientslist" style="display: none;">







                             <thead><th>No</th><th>Name</th><th>Email</th></thead>







                             <tbody></tbody>







                         </table>







                     </div> 





		   			<div class="row form-row justify-content-center">







		   				<div class="col-md-10">



                            <p>



                                <button id="savecsrBtn" class="btn btn-primary btn-sm" type="submit"> 



		   						  <i class="fa fa-save" aria-hidden="true"style="font-size:12px;" ></i> Save 



		   					   </button> 



                                <button id="printcsrBtn" class="btn btn-success btn-sm bg-success" type="button"> 



		   						  <i class="fa fa-print" aria-hidden="true"style="font-size:12px;" ></i>&nbsp; Print 

		   					  </button> 



                               <button id="deletecsrBtn" class="btn btn-danger btn-sm bg-danger" type="button"> 



                                    <i class="fa fa-trash" aria-hidden="true"style="font-size:12px; color:#fff;" ></i>&nbsp; Delete 



                                </button>



                            </p> 



		   				</div>  

					</div> 

		   		   

                </form>



                        </div>



                        <div class="col-sm-9">



                            <div class="row mb-3">



                                <div class="col-sm-6" >



                                      <input type="text" class="form-control" name="inventorysearch" id="inventorysearch" placeholder="Search Item" autocomplete="off" style="background:url('{{url('images/search_icn.png')}}'); background-size: 20px 20px; background-position: 5px 5px;  text-indent: 30px; background-repeat: no-repeat;"/>



                                 </div> 



                                <div class="col-sm-6"> 



                                    <p class="fs-3">Grand Total  <span id="csrcurrency" name="csrcurrency" class="fs-3">KES </span>  <span class="fs-3" name="grandtotal" id="grandtotal" >0.00</span></p>



                                </div> 

     



                            </div>



                            <h3 class="fs-4 fw-bold">Supply Items</h3><hr class="hr">



                            <div class="row mt-2">



                                <div class="col-sm-12">



                                    <div class="table-responsive">



                                        <table id="supplylisttable" class="table table-sm table-bordered table-striped">







                                            <thead class="thead-dark"> 

                                              

                                                 <th col width="8"> 



                                                     <a class='deleterow' onclick='addrow()'> 



                                                         <i class="fa fa-plus-square" aria-hidden="true"></i> 

                                                     </a>   

                                                 </th> 



                                                 <th>No</th> 



                                                 <th>Item ID</th>  



                                                 <th>Part No</th>  



                                                 <th style="width:15%">Description</th> 

                                                 <th>Qty</th>



                                                 <th>Unit B.P</th> 

                                                 <th>Markup %</th>  

                                                 <th>Unit S.P</th>  

                                                 <th>VAT %</th> 



                                                 <th>Total</th> 

                                            </thead> 



                                            <tbody id="csrtablebody"> 

                                                <tr> 



                                                     <td> 

                                                         <a class='deleterow' onclick='deleterow($(this))'> 



                                                             <i class="fa fa-times" aria-hidden="true" ></i> 



                                                         </a> 



                                                     </td> 

                                                    <td class="no">1</td> 



                                                    <td class="itemid" contentEditable="true" onclick="$(this).focus();"></td> 

                                                    <td class="partno" contenteditable="true" onclick="$(this).focus();"></td> 

                                                    <td class="descr" contenteditable="true" onclick="$(this).focus();"></td> 



                                                     <td class="qty" contenteditable="true" onclick="$(this).focus();">0</td> 

                                                    <td class="unitBP" contenteditable="true" onclick="$(this).focus();"></td> 



                                                    <td class="margin" contenteditable="true" onclick="$(this).focus();">0.00</td>  



                                                    <td class="unitSPcell"  contenteditable="false">0.00</td> 



                                                    <td class="vat" contenteditable="true" onclick="$(this).focus();">16.00</td> 



                                                    <td class="itemtotal" contenteditable="false">0.00</td> 



                                                </tr> 



                                            </tbody> 

                                        </table>



                                    </div>



                                </div>



                            </div> <!--close csr items table row  -->







                            



                    <div class="row">



                        <div class="col-sm-6">



                    		<h3 class="fs-4 fw-bold">Attachments</h3>



                    	</div>







                    	<div class="col-sm-6">



                            <p> <small class="text-danger">* Costing sheet, Purchase order are mandatory</small></p>



                        </div>







                    </div><hr class="hr">



                    



                 <div class="row">







                    <div class="col-sm-12">







                        <table class="" id="attachmentstable"> 



                            <thead class="thead-dark">

                                    <th>#</th>



                                    <th>File Name</th>



                                    <th>Attachment</th>



                                    <th>File Download</th>

                                   <th>Delete</th>

                            </thead>



                            <tbody>

                                <tr>

                                    <td >1</td>

                                    <td><input type="text" class="form-control attachments" name="costingfilename" id="costingfilename" placeholder="Costing sheet" value="Costing_sheet" /></td>

                                    <td class="text-center"> 

                                        <input type="File" name="Attachment[]" id="costingsheet" class="form-control filebrowser" /> 

                                    </td> 



                                    <td class="text-center"> 



                                        <button class="downloadbtn" > 



                                            <i class="fa fa-download" aria-hidden="true"></i> 



                                        </button> 



                                    </td>





                                   <td class="text-center"> 



                                         <button class="deletebtn" onclick="deleteFile(this)"> 



                                               <i class="fa fa-times deletefile" aria-hidden="true"></i> 



                                       </button> 



                                   </td> 



                                </tr>



                                <tr> 

                                    <td >2</td> 

                                    <td>

                                        <input type="text" class="form-control attachments" name="quotefilename" id="quotefilename" placeholder="Quotation" value="Quotation" /></td> 



                                    <td> 



                                        <input type="File" name="Attachment[]" id="quotation" class="form-control filebrowser" /> 

                                    </td> 



                                    <td class="text-center"> 



                                        <button class="downloadbtn" > 



                                            <i class="fa fa-download" aria-hidden="true"></i> 



                                        </button> 



                                    </td> 



                                   <td class="text-center"> 



                                         <button class="deletebtn" onclick="deleteFile(this)"> 



                                               <i class="fa fa-times deletefile" aria-hidden="true"></i> 



                                       </button> 



                                   </td> 



                                </tr> 

                                <tr> 



                                    <td >3</td> 

                                    <td><input type="text" class="form-control attachments" name="pofilename" id="pofilename" placeholder="Purchase Order" value="Order" /></td>

 

                                    <td> 

                                        <input type="File" name="Attachment[]" id="purchaseorder" class="form-control filebrowser" /> 

                                    </td> 



                                    <td class="text-center"> 



                                        <button class="downloadbtn" > 



                                            <i class="fa fa-download" aria-hidden="true"></i> 



                                        </button> 



                                    </td> 



                                   <td class="text-center"> 



                                         <button class="deletebtn"> 



                                               <i class="fa fa-times deletefile" aria-hidden="true"></i> 



                                       </button> 



                                   </td> 

                                </tr> 



                                <tr> 

                                    <td >4</td>  

                                    <td><input type="text" class="form-control attachments" name="othersfilename" id="othersfilename" placeholder="Other Attachments" /></td>

 

                                    <td>  

                                        <input type="File" name="Attachment[]" id="others" class="form-control filebrowser"/> 

                                    </td> 



                                    <td class="text-center"> 



                                        <button class="downloadbtn"> 



                                            <i class="fa fa-download" aria-hidden="true"></i> 



                                        </button> 



                                    </td>







                                   <td class="text-center"> 



                                         <button class="deletebtn" onclick="deleteFile(this)"> 



                                               <i class="fa fa-times deletefile text-primary" aria-hidden="true"></i> 



                                       </button> 



                                   </td>







                                </tr>







                            </tbody>







                        </table>







                    </div>







                </div>







                        </div>



                    </div>







                </div>



                



            </div>



        </div>



    </div><!-- closes csr panel -->







        



    </div>



</div>



    



@endsection