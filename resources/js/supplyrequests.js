var itemslist=[]; var j=1;

$("input[type=text]").val('');




function keyPress (e) {

    if(e.key === "Escape") {

         $("#inventorysearch").val('');

    }

}



$("#Cancelproductbtn").on("click",function(event){

  event.preventDefault();

    $(".overlay").css("display","none");

  $(".newproductcontainer").css("display","none");

    $('#newcsrForm')[0].reset();

    $('#newcsrForm').parsley().reset();

});



/* AUTOSEARCH */



$(document).ready(function(){

   var currentpath = window.location.pathname;

   // alert("current path: "+currentpath);

   if(currentpath === '/supplyrequests'){

       inventoryRefresh();
       console.log("path found");

    }



});



function inventoryRefresh(){

       $.ajax({

      url:'supplylist',

      type:'get',

      dataType:'json',

      success:function(data){
       // console.log(data);
         $.each(data,function(index,value){

                  itemslist.push(value.id);

                  itemslist.push(value.name);

                //  console.log(itemslist);

                });

      }

    });

}





$("#sendto").on("change",function(){

  var recipientname = $("#sendto option:selected").text();

  var recipientemail = $("#sendto option:selected").val();

  //alert("selected: "+recipientname);

  var row = "<tr><td>1</td><td>"+recipientname+"</td><td>"+recipientemail+"</td></tr>";

  $("#recipientslist tbody").append(row);

});



 $(function(){



     $("#inventorysearch").autocomplete({

         source:function(request,response){

             var results = $.ui.autocomplete.filter(itemslist,request.term);

             response(results.slice(0,10));

            // console.log("z-index: " + $(".ui-autocomplete").css("z-index"));

         }

     });

 });



   $('#inventorysearch').on('keypress', function (e){

     if(e.which === 13){

            //Disable textbox to prevent multiple submit

            $(this).attr("disabled", "disabled");

            var productname = $(this).val();

            var i=0;



            $.ajax({

              url:'searchproduct',

              type:'get',

              async:false,

              dataType:'json',

              data:{'searchphrase':productname},

              success:function(data){

                  var rows = $("table#supplylisttable >tbody >tr").length;

                  var  itemtotal=0;

                  //alert(" partno 1: "+$("#supplylisttable tbody tr:nth-child(1)").find("td:nth-child(3)").text());



                    for(var t=1;t<=rows;t++){

                          var itemlistpartno = $("#supplylisttable tbody tr:nth-child("+t+")").find("td:nth-child(4)").text();

                         // alert("counter:"+t+"itemlistpart: "+itemlistpartno+" dbitempartno:"+data[0].partno);



                          if(itemlistpartno === $.trim(data[0].partno)||itemlistpartno===""){

                            //alert("match found at row: "+t);

                             $("#supplylisttable tbody tr:nth-child("+t+")").find("td:nth-child(3)").text(data[0].id);

                             $("#supplylisttable tbody tr:nth-child("+t+")").find("td:nth-child(4)").text(data[0].partno);

                             $("#supplylisttable tbody tr:nth-child("+t+")").find("td:nth-child(5)").text(data[0].name);



                            var itemqty =$("#supplylisttable tbody tr:nth-child("+t+")").find("td:nth-child(6)").text();



                              if(itemqty===""||itemqty===null){itemqty=parseInt("0");}

                            $("#supplylisttable tbody tr:nth-child("+t+")").find("td:nth-child(6)").text(parseInt(itemqty)+1);



                            // alert(data[0].unitbp);

                            $("#supplylisttable tbody tr:nth-child("+t+")").find("td:nth-child(7)").text($.number(data[0].unitbp,2));

                             $("#supplylisttable tbody tr:nth-child("+t+")").find("td:nth-child(8)").text("0.00");



                            itemtotal = $.number(parseFloat($.trim(data[0].unitcost))*parseFloat(itemqty)*1.16,2);



                             $("#supplylisttable tbody tr:nth-child("+t+")").find("td:nth-child(9)").text(data[0].unitbp);

                            $("#supplylisttable tbody tr:nth-child("+t+")").find("td:nth-child(11)").text(itemtotal);

                            $('#inventorysearch').val('');

                             $('#inventorysearch').focus();



                            return true;

                            }



                          }



                            itemtotal = $.number(parseFloat($.trim(data[0].unitcost))*parseFloat(1.16),2);

                           var itemdata = "<tr><td class='td-remove'>"+

                            "<button class='del-item'><i class='fa fa-times' aria-hidden='true'></i></button></td>"+

                            "<td>"+(rows+1)+"</td>"+

                            "<td class='td-itemid' contentEditable='true' onclick='$(this).focus();'>"+data[0].id+"</td>"+

                            "<td class='td-partno' contentEditable='true' onclick='$(this).focus();'>"+data[0].partno+"</td>"+

                            "<td class='td-itemname' contentEditable='true' onclick='$(this).focus();'>"+data[0].name+"</td>"+

                            "<td class='td-qty' contentEditable='true' onclick='$(this).focus();'>1</td>"+

                            "<td class='td-unitcost' contentEditable='true' onclick='$(this).focus();'>"+data[0].unitbp+"</td>"+

                            "<td class='td-markup' contentEditable='true' onclick='$(this).focus();'>0.00</td>"+

                            "<td class='td-unitsp' contentEditable='true' onclick='$(this).focus();'>"+data[0].unitbp+"</td>"+

                            "<td class='td-vatrate' contentEditable='true' onclick='$(this).focus();'>16.00</td>"+

                            "<td class='td-itemtotal' contentEditable='true' onclick='$(this).focus();'>"+ itemtotal+"</td></tr>";



                            $("table#supplylisttable >tbody:last-child").append(itemdata);

                            $('#inventorysearch').val('');

                            $('#inventorysearch').focus();

                            // alert($itemdata);





              },compelete:function(data){ }



        });



            //Enable the textbox again if needed.

            $(this).removeAttr("disabled");

          }

        });



$(document).ready(function(){

  if(window.location.pathname=="/supplyrequests"){

    //getCSRs();

    getUsers();

    //showClients();

  }

});

$("#currency").on("change",function(){
  var selectedcurrency = $("#currency").find(":selected").val();
  console.log(selectedcurrency);
  $("#csrcurrency").innerHTML = '';

   $("#csrcurrency").text(selectedcurrency);
});



$("#addproducts").on('click', function(){

  //$(".overlay").css("display","block");

  $(".newproductcontainer").css("display","block");

    $('#newcsrForm')[0].reset();

    $('#newcsrForm').parsley().reset();

    $("#supplylisttable tbody").empty();

    $("#grandtotal").val("0.00");

     //var random = Math.floor(Math.random()*10000)+1;



     $("table#supplylisttable tbody tr:nth-child(1)").find("td:nth-child(3)").text(nextItemid());

      $("table#supplylisttable tbody tr:nth-child(1)").find("td:nth-child(4)").text(getRandom());



     addNewrow();

     countCSR();

     showClients();

     getUsers();

});





function nextItemid(){

  var nextId ="1";

  $.ajax({

      url:"nextItemid",

      method:"get",

      dataType:"text",

      async:false,

      success:function(data){

        nextId = data;

      }

  });

  return nextId;

}



function getRandom(){

  var x = Math.floor((Math.random() * 1000000) + 1);

  return x;

}



function deleterow(row){
   var rows = $("table#supplylisttable >tbody >tr").length;

    var itemid = parseFloat(row.closest('tr').find('td.itemid').text());

    var itemdescrip = parseFloat(row.closest('tr').find('td.description').text());

    //Confirm.render("Delete "+itemdescrip+"?","itemdelete",itemid);

    var csrno = $("#csrno").val();

      var subtotal = parseFloat($.number(row.closest('tr').find('td:nth-child(11)').text(),2));

     //console.log("item total is "+subtotal);

    var grandtotal = parseFloat($("#csrvalue").val());

    grandtotal = grandtotal - subtotal;



    $("#csrvalue").val($.number(grandtotal,2));

    $("#grandtotal").val($.number(grandtotal,2));

    $.ajax({

        url:"supplyrequests/deleteitem",

        type:"post",

        dataType:"text",

        async:false,

        data:{itemid:itemid,grandtotal:grandtotal,csrno:csrno},

        success:function(status){

            if(status>0){

              row.closest('tr').remove();

            for(var i=1;i<=rows;i++){

              $("#supplylisttable tbody tr:nth-child("+i+") td:nth-child(2)").html(i);

            }

            $("#alertmessage").show();

            $(".message").text("Item deleted successfully!").fadeOut(4000);



          }else{

            row.closest('tr').remove();  // Alert.render("Item not deleted!","Alert",failiconUrl);

          }

        }



    });

}



function addNewrow(){

     var rows = $("table#supplylisttable >tbody >tr").length;

       // alert("items table rows "+rows);

      var extrarow = "<tr>"+

                        "<td><a class='deleterow' onclick='deleterow($(this))'><i class='fa fa-times' aria-hidden='true'></i></a></td>"+

                        "<td class='no'>"+(rows+1)+"</td>"+

                        "<td class='itemid' contenteditable='true' onclick='$(this).focus();'>"+(rows+1)+"</td>"+

                        "<td class='partno' contenteditable='true'onclick='$(this).focus();'>"+getRandom()+"</td>"+

                       " <td class='descr' contenteditable='true' onclick='$(this).focus();'></td>"+

                        "<td class='qty' contenteditable='true' onclick='$(this).focus();'></td>"+

                        "<td class='unitBP'contenteditable='true' onclick='$(this).focus();'></td>"+

                        "<td class='margin'contenteditable='true' onclick='$(this).focus();'></td>"+

                       "<td class='unitSPcell'contenteditable='false'></td>"+

                       "<td class='vat' contenteditable='true' onclick='$(this).focus();'>16.00</td>"+

                      "<td class='itemtotal' contenteditable='false'>0.00</td></tr>";

     if(rows===0){

           $("#supplylisttable tbody").append(extrarow);

     }

     else{

       var partno = $("#supplylisttable tbody tr:nth-child("+rows+") td:nth-child(4)").text();

       var descript = $("#supplylisttable tbody tr:nth-child("+rows+") td:nth-child(5)").text();

       var qty = $("#supplylisttable tbody tr:nth-child("+rows+") td:nth-child(6)").text();

       var unitprice = $("#supplylisttable tbody tr:nth-child("+rows+") td:nth-child(7)").text();

       var vat = $("#supplylisttable tbody tr:nth-child("+rows+") td:nth-child(9)").text();

      if(partno.trim()===""){

         $("#alertmessage").css("display","block").fadeOut(2500);

          $(".message").text("Please enter item part number");

      }

      else if(descript.trim()===""||descript.trim()===0){

          $("#alertmessage").css("display","block").fadeOut(2500);

        $(".message").text("Please enter item description");

      }

       else if(qty.trim()===""){

          $("#alertmessage").css("display","block").fadeOut(2500);

         $(".message").text("Please enter item quantity");

      }

        else if(unitprice.trim()===""){

           $("#alertmessage").css("display","block").fadeOut(2500);

           $(".message").text("Please enter item unit price");

        }

         else if(vat.trim()===""){

           $("#alertmessage").css("display","block").fadeOut(2500);

           $(".message").text("Please enter item VAT rate");

        }

      else{

          $("#supplylisttable tbody").append(extrarow);

         // alert("conditions met");

          $("tr:nth-child("+(rows+1)+") td:nth-child(4)").focus();

          }

  }



}





$(document).ready(function(){
    $("#supplylisttable").keyup(function(){
           // alert("clicked");
            var rows = $("table#supplylisttable >tbody >tr").length;

            var subtotal; var grandtotal=0;

             for(var i=1;i<=rows;i++){

                 var itemname = $("#supplylisttable tr:nth-child("+i+") td:nth-child(5)").html();

                 var itemid = $("#supplylisttable tr:nth-child("+i+") td:nth-child(3)").html();

                 //console.log("item id is "+itemid);

                 var qty =  parseFloat($("#supplylisttable tr:nth-child("+i+") td:nth-child(6)").html());

                var unitbuyprice =  parseFloat($("#supplylisttable tr:nth-child("+i+") td:nth-child(7)").html().replace(",",""));

                  var marginrate =  parseFloat($("#supplylisttable tr:nth-child("+i+") td:nth-child(8)").html());
                  console.log("unit bp: "+unitbuyprice+" "+$("#supplylisttable tr:nth-child("+i+") td:nth-child(7)").html());

                 //  var formatprice = numeral(unitprice).format('0,0.0');

                //$("tr:nth-child("+i+") td:nth-child(7)").text(formatprice);

                var vat = parseFloat($("#supplylisttable tr:nth-child("+i+") td:nth-child(10)").html());

                 var unitsaleprice =0;



       if(!isNaN(qty) && !isNaN(unitbuyprice)&&!isNaN(marginrate)){

            unitsaleprice = ((marginrate+100)/100)*unitbuyprice;

            $("#supplylisttable tr:nth-child("+i+") td:nth-child(9)").text(unitsaleprice.toFixed(2));



        //  var formatsubtotal =  numeral(subtotal).format('0,0.0');

          //  $("tr:nth-child("+i+") td:nth-child(8)").text(formatsubtotal);

              if(!isNaN(vat)){

                var itemtotal = ((100+vat)/100)*(unitsaleprice)*qty;

                $("#supplylisttable tr:nth-child("+i+") td:nth-child(11)").text($.number(itemtotal,2));



           grandtotal = grandtotal+itemtotal;
           var selectedcurrency = $("#currency option:selected").text();
                $("#csrcurrency").text(selectedcurrency);
          $("#grandtotal").text($.number(grandtotal,0));

           $("#csrvalue").val($.number(grandtotal,2));

         }

         }



   }

    });



    $("#supplylisttable").keypress(function(e){

    if(e.keyCode===13){

      e.preventDefault();

            addNewrow();

        }

       });

});



function showClients(){

  $.ajax({

    url:"showclients",

    method:"get",

    dataType:"json",

    success:function(data){

       $("#clientid").empty().append('<option selected="selected" value="">Select Client</option>');

       $("#customername").empty().append('<option selected="selected" value="">Select Client</option>');

          $("#customer").empty().append('<option selected="selected" value="">Select Client</option>');

     $.each(data,function(key,value){

        var option  = new Option(value.clientname, value.id);

         $(option).html(value.clientname);



         $("#clientid").append(option);



          $("#customer").append(option);

          var choice = "<option value='"+value.id+"'>"+value.clientname+"</option>";

            $("#customername").append(choice);

            $(".clientdropdown").append(choice);

     });



    }

  });

};


/**
 * change display when new csr btn is clicked
 */
$("#newcsrbtn").on("click",function(){
  $("#csrpanel").children().hide();
  $("#csrinfosection").hide();

  $("#createcsrsection").show();
  countCSR(); //display the csr number
});

function countCSR(){

  $.ajax({

        async:false,

        url:"countcsr",

         type:"get",

        dataType:"json",

        success:function(data){

          var date = new Date();

           // //console.log(data[0]);

           var csrNum = data.csrno;

           //alert("csr no:"+csrNum);

           var csrnumber= /[^/]*$/.exec(csrNum)[0];

           if(csrnumber<1 || csrnumber==null){

            csrnumber=1;

          }

          else{

            csrnumber = parseFloat(csrnumber)+1;

          }



                var csrdisplaynum =null;

               if(csrnumber>100){

                csrdisplaynum = "TS"+date.getFullYear()+"/"+csrnumber;

               }

               else if(csrnumber>=10 && csrnumber<100){

                  csrdisplaynum = "TS"+date.getFullYear()+"/0"+csrnumber;



               }else  if(csrnumber<10){

                  csrdisplaynum = "TS"+date.getFullYear()+"/00"+csrnumber;

                 }

                   $("#csrno").val(csrdisplaynum);

             }

    });

}





function getUsers(){

  $.ajax({

    async:false,

    url:'getusers',

    type:"get",

    dataType:"json",

    success:function(data){

     // console.log(data);

         $("#salespersons1").empty().append('<option selected="selected" value="">Select Salesperson</option>');;

           $("#sendto").empty().append('<option selected="selected" value="">Select Recipient</option>');;

        $.each(data,function(key,value){

         // console.log("user name:"+value.id);

        var option  = new Option(value.name, value.id);

         $(option).html(value.name);

          $("#sendto").append("<option value='"+value.email+"'>"+value.name+"</option>");

         $("#technician").append(option);

          $("#salespersons1").append("<option value='"+value.id+"'>"+value.name+"</option>");

     });

    }

  });

}



//   *********************************************** POST CSR DATA *********************************************************************************


/**
 * CREATE NEW CSR
 */
$(document).on("submit","#newcsrForm",function(event){
    event.preventDefault();
    $("#newcsrForm").parsley();

   if($("#newcsrForm").parsley().isValid()){
                    //   var itemslist = $("#supplylisttable tbody tr").length;
                    var csrcheck = function(){
                        var temp = 0;
                        $.ajax({url:"checkcsr",method:"get",data:{csrno:$("#csrno").val()},async:false,success(data){temp = data;}});

                        return temp;
                    }();



                        if($("#csrvalue").val()<1){

                          $("#alertmessage").css("display","block").fadeOut(5000);
                          $("#alertmessage").removeClass("alert-success");
                          $("#alertmessage").addClass("alert-danger");
                          $(".message").text("Add at least one item!");
                          $("#alertmessage")[0].scrollIntoView();
                        }

                        else if(csrcheck<1){

                             if($("#costingsheet").get(0).files.length===0){

                               $(".message").text("Attach the costing sheet!");
                               $("#alertmessage").css("display","block").fadeOut(5000);
                               $("#alertmessage").removeClass("alert-success");
                              $("#alertmessage").addClass("alert-danger");
                              $("#alertmessage")[0].scrollIntoView();
                              }

                              else if($("#purchaseorder").get(0).files.length===0){

                                $(".message").text("Attach the purchase order!");
                                $("#alertmessage").removeClass("alert-success");
                                $("#alertmessage").addClass("alert-danger");
                                $("#alertmessage").css("display","block").fadeOut(5000);
                                $("#alertmessage")[0].scrollIntoView();
                              }

                              else if($("#quotation").get(0).files.length===0){
                                $("#alertmessage")[0].scrollIntoView();
                                $(".message").text("Attach the quotation!");
                                $("#alertmessage").removeClass("alert-success");
                                $("#alertmessage").addClass("alert-danger");
                                  $("#alertmessage").css("display","block").fadeOut(5000);

                              }else{

                                postCSRData();

                              }
                        }

                       else{
                         postCSRData();
                            }

                        }
            });



function postCSRData(){

    var formdata = new FormData();
        formdata.append('clientid', $("#clientid option:selected").val());
        formdata.append('csrno', $("#csrno").val());
        formdata.append('description', $("#description").val());
        var selectedcsrdate = new Date($("#csrdate").val().replace(/(\d{2})-(\d{2})-(\d{4})/, "$2/$1/$3"));
        formdata.append('csrdate', moment(selectedcsrdate).format("YYYY-MM-DD"));
        formdata.append('ponumber', $("#ponumber").val());
        var selectedpodate = new Date($("#podate").val().replace(/(\d{2})-(\d{2})-(\d{4})/, "$2/$1/$3"));
        formdata.append('podate',  moment(selectedpodate).format("YYYY-MM-DD"));
        formdata.append('currency', $("#currency option:selected").val());
        formdata.append('csrvalue', $("#csrvalue").val().replace(",",""));
        formdata.append('soldby', $("#salespersons1 option:selected").val());
        formdata.append('status', $("#status option:selected").val());

        /**
         * collect csr supplied items
         */

        var csritemstatus=0;
        var csrvalue=0; var itemtotal;
        var rows = $("#supplylisttable tbody tr").length;
        var csrnum =$("#csrno").val();
        var csritemstatus=0; var csritemsmsg="";

         var sucess_state=0;
        let csritems = [];

        for(var i=1;i<=rows;i++){
            itemtotal = parseFloat($("tr:nth-child("+i+") td:nth-child(11)").text().replace(",",""));
             csrvalue = csrvalue+itemtotal;
               var itemid =  $("#supplylisttable tr:nth-child("+i+") td:nth-child(3)").text();
               var  partnum = $("#supplylisttable tr:nth-child("+i+") td:nth-child(4)").text();
                var descript = $("#supplylisttable tr:nth-child("+i+") td:nth-child(5)").text();
                var qty =  $("#supplylisttable tr:nth-child("+i+") td:nth-child(6)").text();
                var unitBP =  $("#supplylisttable tr:nth-child("+i+") td:nth-child(7)").text();
                var markup  =  $("#supplylisttable tr:nth-child("+i+") td:nth-child(8)").text();
                var unitSP  =  $("#supplylisttable tr:nth-child("+i+") td:nth-child(9)").text();
                var vat =  $("#supplylisttable tr:nth-child("+i+") td:nth-child(10)").text();

              var items ={
                   csrno:csrnum,itemid:itemid,
                   partno:partnum,qty:qty,
                   unitbp:unitBP,markup:markup,
                   unitsp:unitSP, vatrate:vat,
                   itemtotal:itemtotal,name:descript
                 };

               csritems.push(items);
            }
            formdata.append('csritems',JSON.stringify(csritems));

    /**
     * collect the attachments
     *
     * */
     var status=0; let csrattachments = [];
    var rows2 = $("#attachmentstable tbody tr").length;
    var clientname = $("#clientid option:selected").text();
    formdata.append('clientname',clientname);

  for(var i=1;i<=rows2;i++){
      var filename = $("#attachmentstable tr:nth-child("+i+") td:nth-child(2) input[type='text']").val();
      var attachment = $("#attachmentstable tr:nth-child("+i+") td:nth-child(3) input[type='file']")[0].files[0];
      var fileInput = $.trim($("#attachmentstable tr:nth-child("+i+") td:nth-child(3) input[type='file']").val());
      if(filename!=null && fileInput !=='' && fileInput){
         formdata.append('Attachment['+i+']', attachment);
      }

    }

    /**
     * get the recipient's to receive notification
     */
    var mail_list = [];
    var notification = $("#sendto option:selected").val();
    formdata.append('notification',notification);

    for(var j=1;j<=$("#recipientslist tbody tr").length;j++){
            var list ={
                  "name":$("#recipientslist tr:nth-child("+j+") td:nth-child(2)").text(),
                "email":$("#recipientslist tr:nth-child("+j+") td:nth-child(3)").text()
              };
//console.log(list);
              mail_list.push(list);
      }
   formdata.append('mailinglist',JSON.stringify(mail_list));
//console.log(formdata);

   /**
    * POST CSR DATA
    */
  var originalSavebtn = $("#savecsrBtn").clone();
        $.ajax({

                 url:"newcsr",
                 method:"post",
                 data:formdata,
                 dataType:"json",
                 processData:false,
                 contentType:false,
                 beforeSend:function() {
                  var spinner = '<div class="spinner-border text-success spinner-border-sm" role="status"><span class="visually-hidden"> Loading...</span></div>';
                  $("#alertmessage").removeClass("alert-success");
                  $("#alertmessage").addClass("alert-info");
                 $(".message").html(spinner+" Processing...");
                 $("#alertmessage").css("display","block");
                 $("#alertmessage")[0].scrollIntoView();
                        },

                         success:function(data){
                           var btntext = "<i class='fa fa-save'></i> Save";
                          $('#saveitemBtn').val(btntext);
                          $("#alertmessage")[0].scrollIntoView();
                              if(data.response>0){
                                  $("#alertmessage").removeClass("alert-info");
                                  $("#alertmessage").addClass("alert-success");
                                 $(".message").text(data.msg);
                                 $("#alertmessage").css("display","block").fadeOut(5000);


                                 $("#purchaseorder").val(null); $("#pofilename").val(null);
                                 $("#quotation").val(null);  $("#quotefilename").val(null);
                                $("#costingsheet").val(null);  $("#costingfilename").val(null);
                                 $("#others").val(null);  $("#othersfilename").val(null);
                                   $('#newcsrForm')[0].reset(); $('#newcsrForm').parsley().reset();

                               $('#supplylisttable tbody').empty();

                              $('#saveitemBtn').attr('disabled', false);
                             addNewrow();
                             setTimeout(function(){

                               }, 6000);

                                 }else{

                                  $("#alertmessage")[0].scrollIntoView();
                                  $(".message").text(data.msg);
                                  $("#alertmessage").removeClass("alert-success");
                                  $("#alertmessage").addClass("alert-danger");
                                    $("#alertmessage").css("display","block").fadeOut(3000);

                                 }

                              }

                          });

}







/**
 * get csr on table row click
 */

$(document).on("click","table#csrtable >tbody >tr",function(e){

   //  $(".overlay").css("display","block");

     $(".newproductcontainer").css("display","block");

     $(".newproductcontainer").show().focus();

      const csrno = $(this).closest('tr').find('td:nth-child(3)').text();
      console.log("csr no: "+csrno);
     $.ajax({

        url:"filter",

        method:"get",

        timeout:3000,

        data:{"csrno":csrno},

        dataType:"json",
        beforeSend:function(){
          var spinner = '<div class="spinner-border spinner-border-sm text-success" role="status"><span class="visually-hidden">Loading...</span></div>';
          $(".message").html(spinner+" Retrieving CSR...");
          $("#alertmessage2").removeClass("alert-danger");
          $("#alertmessage2").addClass("alert-success");
          $("#alertmessage2").css("display","block");
        },
        success:function(data){
          $("#alertmessage2").css("display","none");
         //console.log(data);
         $("#csrpanel").children().hide();
         $("#csrinfosection").hide();
        // $("#createcsrsection").show();
         $("#createcsrsection").animate( { "opacity": "show", top:"100"} , 500 );

            $("#csrno").val(data[0].csrno);

            $("#clientid option:selected").text(data[0].clientname);

             $("#clientid option:selected").val(data[0].id);

             $("#description").val(data[0].description);

              $("#csrdate").val(data[0].csrdate);

              $("#podate").val(data[0].podate);

               $("#ponumber").val(data[0].ponumber);

               $("#csrvalue").val($.number(data[0].csrvalue,2));
               $("#grandtotal").text($.number(data[0].csrvalue,2));
               $("#csrcurrency").text(data[0].currency);

                $("#currency option:selected").val(data[0].currency);

                 $("#salespersons1 option:selected").text(data[0].saleperson);
                 $("#salespersons1 option:selected").val(data[0].salepersonid);

                $("#status option:selected").val(data[0].status);

                $("#grandtotal").val($.number(data[0].csrvalue,2));

        },

        complete: function(){

           // $("body").removeClass("loading");

        }

     });



     $.ajax({

        url:"getcsritems",

        method:"get",

        data:{"csrno":csrno},

        dataType:"json",

        success:function(data){

          var i=1;

         $("#supplylisttable tbody").empty();

          $.each(data,function(index,value){

                let itemtotal=0;

                var deleteicon = "<a class='deleterow' onclick='deleterow($(this))'><i class='fa fa-times'></></a>";

                var subtotal = parseFloat(value.unitcost)*parseFloat(value.qty);

             // console.log("vat "+(1+ parseFloat(value.vat)/100));

                var unitbp = value.unitbp;



                var tr = "<tr><td>"+deleteicon+"</td>"+

                "<td>"+i+"</td>"+

                "<td class='itemid' contenteditable='true' onclick='$(this).focus();'>"+value.id+"</td>"+

                "<td contenteditable='true' onclick='$(this).focus();'>"+value.partno+"</td>"+

                "<td class='description' contenteditable='true' onclick='$(this).focus();'>"+value.name+"</td>"+

                "<td contenteditable='true' onclick='$(this).focus();'>"+value.qty+"</td>"+

                "<td contenteditable='true' onclick='$(this).focus();'>"+value.unitbp+"</td>"+

                "<td contenteditable='true' onclick='$(this).focus();'>"+value.markup+"</td>"+

                "<td contenteditable='true' onclick='$(this).focus();'>"+value.unitsp+"</td>"+

                "<td contenteditable='true' onclick='$(this).focus();'>"+value.vatrate+"</td>"+

                "<td>"+$.number(value.itemtotal,2)+"</td></tr>";

              $("#csrtablebody").append(tr);

                         i++; });

        }

     });



     $.ajax({

        url:"getcsrattachments",

        method:"get",

        data:{"csrno":csrno},

        dataType:"json",

        success:function(data){

           var rows = $("#attachmentstable tbody tr").length;

           //console.log("data length: "+data.length);

          for(var i=0;i<data.length;i++){

            //console.log("counter: "+data[0].filename);

              $("#attachmentstable tr:nth-child("+parseFloat(i+1)+") td:nth-child(2) input[type='text']").val(data[i].filename);

            }

            //console.log(data);

          }

     });

});







$("#printcsrBtn").on("click",function(){

  var csrstatus =  function(){

    var temp = 0;

    $.ajax({

      url:"checkcsr",

      method:"get",

      data:{"csrno":$("#csrno").val(),"clientid":$("#clientid option:selected").val()},

      dataType:"text",

      async:false,

      success:function(status){

        temp = status;

      }



    });

    return temp;

  }();



    if(csrstatus>0){

            window.open('csrprinter?csrno='+$("#csrno").val(), "", "width=800,height=900,scrollbars=yes");

          }

          else{

             $("#alertmessage").removeClass("alert-success");

              $("#alertmessage").addClass("alert-danger");

              $(".message").text("CSR No "+$("#csrno").val()+" does not exist!");

              $("#alertmessage").css("display","block").fadeOut(3000);

          }



}

);



$("#printInvoice").on("click",function(){

  window.print();

});



$(".downloadbtn").on("click", function(){

  event.preventDefault();

  //alert("No Attachment found");

  var filename = $(this).closest('tr').find("input[type='text']").val();

  var csrno = $("#csrno").val();

//alert("filename is: "+filename);
window.location.href = "downloadattachment?filename="+filename+"&csrno="+csrno;
});

$(".filebrowser").on("change",function(){
  var file = $(this)[0].files[0];

  // alert("file name is "+file.name);

  var filename = file.name.split('.').shift();

$(this).closest('tr').find("input[type='text']").val(filename);
});


/**
 * DELETE FILE ATTACHEMENT
 */

$(".deletebtn").on("click", function(){
  event.preventDefault();
  var filename = $(this).closest('tr').find("input[type='text']").val();
  var csrno = $("#csrno").val();


  if(filename !==''||filename===null){

    $.ajax({

     url:"deletefile",

     method:"post",

     data:{csrno:csrno,filename:filename},

     success:function(data){

       if(data.status>0){

          $("#alertmessage").removeClass("alert-danger");

           $("#alertmessage").addClass("alert-success");

           $(".message").text(data.msg);

           $("#alertmessage").css("display","block").fadeOut(3000);

           $(this).closest('tr').find("input[type='text']").val("");

           $(this).closest('tr').find("input[type='file']").val('');

       }else{

          $("#alertmessage").removeClass("alert-success");

           $("#alertmessage").addClass("alert-danger");

           $(".message").text(data.msg);

           $("#alertmessage").css("display","block").fadeOut(3000);

       }

     }

 });

 }else{

           $("#alertmessage").removeClass("alert-success");

           $("#alertmessage").addClass("alert-danger");

           $(".message").text("File does not exist!");

           $("#alertmessage").css("display","block").fadeOut(3000);

 }
});


/**
 * DELETE CSR
 */

$("#deletecsrBtn").on("click",function(){
  $.ajax({

    url:"deletecsr",

    method:"post",

    dataType:"json",

    beforeSend:function(){

         $('#printcsrBtn').attr('disabled', 'disabled');
    },

    data:{csrno:$("#csrno").val()},

    success:function(data){
         if(data.response>0){

                   $("#alertmessage").removeClass("alert-danger");

                   $("#alertmessage").addClass("alert-success");

                  $(".message").text(data.msg);

                   $("#alertmessage").css("display","block").fadeOut(5000);

                   $('#printcsrBtn').attr('disabled', false);

                   $("#purchaseorder").val(null); $("#pofilename").val(null);

                      $("#quotation").val(null);  $("#quotefilename").val(null);

                     $("#costingsheet").val(null);  $("#costingfilename").val(null);

                    $("#others").val(null);  $("#othersfilename").val(null);
                  $('#newcsrForm')[0].reset();
                  $('#newcsrForm').parsley().reset();
                  $('#supplylisttable tbody').empty();

                  $('#saveitemBtn').attr('disabled', false);

                 addNewrow();
                 setTimeout(function(){
                   // document.location.reload();
                 }, 5000);
               }else{
                    $("#alertmessage").removeClass("alert-success");
                   $("#alertmessage").addClass("alert-danger");

                  $(".message").text(data.msg);
               }

    }

});

});

$( function() {
  $( "#accordion").accordion({
    active:false,
    collapsible:true,
    animate:200
  });
} );

/**
 * GET CSR DATA FOR BAR GRAPH DISPLAY
 */
$(document).ready(function(){
  var pathname = window.location.pathname;
  if(pathname=="/supplyrequests"){
  const ctx = document.getElementById('myChart');

  $.ajax({
    url:"csryearsales",
    method:"get",
    dataType:"json",
    contentType:false,
    processType:false,
    success:function(salesdata){
    // console.log(salesdata);
     var newcollection =[];
     var sales =salesdata.sales;


   const salesUSD =  sales.filter(item => item.currency.indexOf('USD') !== -1);
   var l = salesUSD.length;
     for(var x=0;x<l;x++){
        var s = {month:salesUSD[x].monthname, sales:{USD:salesUSD[x].monthsales}};
        newcollection.push(s);
      }
      const salesKES =  sales.filter(item => item.currency.indexOf('KES') !== -1);


     for(var x in salesKES){

          if(salesKES.monthname === newcollection.month){
            //console.log(newcollection[x].month);
              if(newcollection[x] !=null)
              {
                 newcollection[x].sales.KES = salesKES[x].monthsales;

              }
          }
          else{

            switch(salesKES[x].month){
              case 1:
                var s = {month:"January",sales:{USD:0,KES:salesKES[x].monthsales}};
                newcollection.push(s);
                break;
              case 2:
                  var s = {month:"February",sales:{USD:0,KES:salesKES[x].monthsales}};
                  newcollection.push(s);
                  break;
              case 3:
                    var s = {month:"March",sales:{USD:0,KES:salesKES[x].monthsales}};
                    newcollection.push(s);
                    break;

                case 4:
                      var s = {month:"April",sales:{USD:0,KES:salesKES[x].monthsales}};
                      newcollection.push(s);
                      break;
                case 5:
                var s = {month:"May",sales:{USD:0,KES:salesKES[x].monthsales}};
                newcollection.push(s);
                break;
            }

          }
     }
    // console.log(newcollection);

      var saleschart  = new Chart(ctx,{});

   //

       saleschart.destroy();

        saleschart = new Chart(ctx,{
          type:'bar',
          data:{
           // labels:["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],
            datasets: [

                {
                    label: 'USD Monthly Sales',
                    data: newcollection,
                    backgroundColor:'rgba(0, 102, 255)',
                    borderColor:'rgba(255, 255, 255)',
                     tension: 0.4,
                     parsing:{
                      xAxisKey: 'month',
                      yAxisKey: 'sales.USD'
                     }
                },
                {
                  label: 'KES Monthly Sales',
                  data: newcollection,
                  backgroundColor:'linear-gradient(90deg, rgba(2,0,36,1) 0%, rgba(121,28,9,1) 35%, rgba(0,212,255,1) 100%)',
                  borderColor:'rgba(255, 255, 255)',
                   tension: 0.4,
                   parsing:{
                    xAxisKey: 'month',
                    yAxisKey: 'sales.KES'
                   }
              },

              ]
            },
            options: {
              scales: {
                y: {
                  beginAtZero: false
                }
              }
            }
         });


    }
  });
}
});




