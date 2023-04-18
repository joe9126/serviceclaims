$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});



$(function () {
    $('[data-toggle="tooltip"]').tooltip()
  })

$(document).ready(function(){
    $("#projectslist_table tbody>tr>td").click(function(){

        var ticketno = $(this).closest('tr').attr('id');
           // console.log("ticket no is: "+ticketno);
        $("#claimslist").fadeOut();
        $("#claimslist").css("display", "none");
        //$("#claimupdate").css("display", "block");
        $("#claimupdate").slideDown("slow");
      //  $("#claimupdate").css({display:'flex',justify:'center'});
        getClaimdetails(ticketno);
    });
});

// GO BACK BUTTON
$("#showlist").click(function(){
    $("#claimupdate").css("display", "none").fadeOut(2000);
    $("#claimslist").fadeIn(2000);

    document.location.reload();
});


function  getClaimdetails(ticketno){
    $("#claimupdateform").trigger("reset");
    $.ajax({
        type: "get",
        url: "ticketclaims/showclaim",
        data:{"ticketno":ticketno},
        dataType:"json",
        contentType:"false",
        processData:"false",
        success: function(response) {
          //console.log("Data successfully sent!");
        console.log(response);

        var servicedate = moment(response[0].ticketdate).format("Do MMM, YYYY");
          var cardtitle = response[0].clientname+", "+response[0].location+" On "+servicedate;
          $("#cardtitle").text(cardtitle);

          $("#cardtitle-claimamount").text("| Kes. "+$.number(response[0].claimamount,2));

          $("#claimno").val(response[0].claimno); $("#ticketno").val(response[0].ticketno);
            $("#busfare").val(response[0].psvfare);  $("#lunch").val(response[0].lunch);
            $("#dinner").val(response[0].dinner);  $("#accommodation").val(response[0].accommodation);
            $("#petties").val(response[0].petties);  $("#kmtravel").val(response[0].km);
            $("#kmclaim").val($.number(response[0].kmclaim,2));
        },
        error: function(error) {
          console.log("Error sending data.");
          console.log(error);
        }
      });

}
$(document).ready(function(){
    $(".calc").keyup(function(){
       // $('.calc').number( true );
        var claimtotal = parseFloat($("#busfare").val())
                        +parseFloat($("#lunch").val())
                        +parseFloat($("#dinner").val())
                        +parseFloat($("#accommodation").val())
                        +parseFloat($("#petties").val())
                        +parseFloat( $("#kmclaim").val());
           $("#cardtitle-claimamount").text("| Kes. "+$.number(claimtotal,2));

       });

       $("#kmtravel").keyup(function(){
        var distance = parseFloat($("#kmtravel").val());
        
        var distclaim = 0; var rateperkmfirst20km = 40; var rateperkmforextrakm = 20;
        if(distance>20){
            distance = distance - 20;
            distclaim = (distance * rateperkmforextrakm) + (rateperkmfirst20km * 20);
            $("#kmclaim").val(distclaim);
        }else{
            distclaim = rateperkmfirst20km * distance;
            $("#kmclaim").val(distclaim);
        }
        var claimtotal = parseFloat($("#busfare").val())
        +parseFloat($("#lunch").val())
        +parseFloat($("#dinner").val())
        +parseFloat($("#accommodation").val())
        +parseFloat($("#petties").val())
        +parseFloat( $("#kmclaim").val());
      $("#cardtitle-claimamount").text("| Kes. "+$.number(claimtotal,2));
       });
           
      
});

// travel mode selection
$("#travelmode").on("change",function(){
    if($(this).val()=="Private"){
        $(".kmtravel").css("display","block");
        
        $(".busfare").css("display","none"); 
        $(".companyprovided").css("display","none");
      
    }else if($(this).val()=="Public"){
        $(".companyprovided").css("display","none");
        $(".kmtravel").css("display","none");
        $(".busfare").css("display","block");
    }else{
        $(".companyprovided").css("display","block");
        $(".kmtravel").css("display","none");
        $(".busfare").css("display","none");
    }
});


// claim update form submission
$("#claimupdateform").on("submit",function(event){
    event.preventDefault();
    
    var claimno = $("#claimno").val();
    if(claimno !="NA"){
        $(".message").text("This ticket is already claimed. Contact admin.");
        $("#alertmessage2").removeClass("alert-success");
        $("#alertmessage2").addClass("alert-danger");
        $("#alertmessage2").css("display","block").fadeOut(3500);;
    }
   else{ 
    $("#claimupdateform").parsley();
    if($("#claimupdateform").parsley().isValid()){
    //alert("form submited");
    var claimtotal = parseFloat($("#busfare").val())
    +parseFloat($("#lunch").val())
    +parseFloat($("#dinner").val())
    +parseFloat($("#accommodation").val())
    +parseFloat($("#petties").val())
    +parseFloat( $("#kmclaim").val());

if( window.FormData !== undefined ) {
    var formData = new FormData(this);
    formData.append('claimtotal',claimtotal);
   // console.log(formData);
   var originalState = $("#claimsubmit").clone();

    $.ajax({
        url:"claimupdate",
        method:"post",
        data:formData,
        processData:false,
        contentType:false,
        beforeSend:function(){
            var spinner = '<div class="spinner-border text-light fs-5" role="status"><span class="visually-hidden">Loading...</span></div>';
            $("#claimsubmit").html("Updating "+spinner);
                },
        success:function(response){
            $("#claimsubmit").replaceWith(originalState);
            if(response.success ==true){
                $(".message").text(response.message);
                $("#alertmessage2").removeClass("alert-danger");
                $("#alertmessage2").addClass("alert-success");
                $("#alertmessage2").css("display","block").fadeOut(3000);;

                $('#claimupdateform')[0].reset();
                $('#claimupdateform').parsley().reset();


            }else{
                $(".message").text(response.message);
                $("#alertmessage2").removeClass("alert-success");
                $("#alertmessage2").addClass("alert-danger");
                $("#alertmessage2").css("display","block").fadeOut(3000);;
            }
        },
        error: function(error) {
            $(".message").text("Claim was not updated");
            $("#alertmessage2").removeClass("alert-success");
            $("#alertmessage2").addClass("alert-danger");
            $("#alertmessage2").css("display","block").fadeOut(3000);;
            console.log("Error sending data.");
            console.log(error);
          }

    });
}
else{

}
    }
}
});


// remove claim item from the printclaimtable

$("#removeclaimbtn").click(function(){
    var checked = $('#claimprinttable').find(':checked').length;
    if(!checked){
        $(".message").text("Select at least one claim.");
        $("#alertmessage").removeClass("alert-success");
        $("#alertmessage").addClass("alert-danger");
        $("#alertmessage").css("display","block").fadeOut(3000);
    }else{
    $("#claimprinttable tbody tr").find('input[name="flexCheckDefault"]').each(function(){
        if($(this).is(":checked")){
            $(this).parents("tr").remove();
        }
    });

    var rows = $("#claimprinttable >tbody >tr").length;
    var totalclaim = 0; var amount =0;
    for(var t=1;t<=rows;t++){
        amount = $("#claimprinttable tbody tr:nth-child("+t+")").find("td:nth-child(11)").text().replace("KES. ","");
        amount = parseFloat(amount.replace(",",""));
        totalclaim +=amount;
    }
    $("#totalamounttxt").text("KES. "+$.number(totalclaim,2));

    }
});

//delete a claim from the db
$("#deleteclaimbtn").click(function(){
   // alert("btn clicked");
    var checked = $('#claimprinttable').find(':checked').length;
    if(!checked){
        $(".message").text("Select at least one claim.");
        $("#alertmessage").removeClass("alert-success");
        $("#alertmessage").addClass("alert-danger");
        $("#alertmessage").css("display","block").fadeOut(3000);
    }
    else{
       /* $("#claimprinttable").find('tr').each(function(){
            //alert("checked");
            var row = $(this);
            if(row.find('input[type="checkbox"]').is('checked')){
                alert("checked");
            }
        });*/

        $("#claimprinttable >tbody>tr").find('input[name="flexCheckDefault"]:checked').each(function(){

            var ticketno = {"ticketno":$(this).parents("tr").attr("id")};

           var delay = 2000;
            $.ajax({
                url:"/delete",
                method:"post",
                dataType:"json",
                data:ticketno,
                success:function(response){
                    //console.log(response);
                    if(response.success ==true){
                        $(".message").text(response.message);
                        $("#alertmessage").removeClass("alert-danger");
                        $("#alertmessage").addClass("alert-success");
                        $("#alertmessage").css("display","block").fadeOut(3000);
                        setTimeout(function(){
                            $('input[type="checkbox"]').removeAttr('checked');
                            document.location.reload();
                       }, delay);

                    }else{
                        $(".message").text(response.message);
                        $("#alertmessage").removeClass("alert-success");
                        $("#alertmessage").addClass("alert-danger");
                        $("#alertmessage").css("display","block").fadeOut(3000);
                    }
                },
                error: function(error) {
                    console.log("Error sending data.");
                    console.log(error);
                  }

            });


      });
    }
});

// CLAIM PRINT PREVIEW BUTTON
$("#printclaimpreviewbtn").click(function(event){
    event.preventDefault();
    var unformattedamount = $("#totalamounttxt").text().replace("KES. ","");
    var claimamount = parseFloat(unformattedamount.replace(",",""));
    var rows = $("#claimprinttable >tbody >tr").length;

    if(claimamount==null || claimamount==0){
        $(".message").text("Claim amount should be greater than 0.");
        $(".spinner-border").removeClass("text-success");
        $(".spinner-border").addClass("text-danger");
        $("#alertmessage").removeClass("alert-success");
        $("#alertmessage").addClass("alert-danger");
        $("#alertmessage").css("display","block").fadeOut(5000);
    }
    else if(rows>5){
        $(".message").text("You can print a maximum of 5 service ticket claims.");
        $(".spinner-border").removeClass("text-success");
        $(".spinner-border").addClass("text-danger");
        $("#alertmessage").removeClass("alert-success");
        $("#alertmessage").addClass("alert-danger");
        $("#alertmessage").css("display","block").fadeOut(4000);
    }
    else{

       var ticketno, jobcardno, billingrefno, client, task, location,date,time,amount, formattedamount=null; 
       var claimdata = {};var claimlist =[];var rows = $("#claimprinttable >tbody >tr").length;
       
       var formdata = new FormData();

       for(var t=1;t<=rows;t++){
          ticketno = $("#claimprinttable tbody tr:nth-child("+t+")").find("td:nth-child(3)").text();
          date = $("#claimprinttable tbody tr:nth-child("+t+")").find("td:nth-child(4)").text();
          time = $("#claimprinttable tbody tr:nth-child("+t+")").find("td:nth-child(5)").text();
          jobcardno = $("#claimprinttable tbody tr:nth-child("+t+")").find("td:nth-child(6)").text();
          billingrefno = $("#claimprinttable tbody tr:nth-child("+t+")").find("td:nth-child(7)").text();
         
          client = $("#claimprinttable tbody tr:nth-child("+t+")").find("td:nth-child(8)").text();
          task = $("#claimprinttable tbody tr:nth-child("+t+")").find("td:nth-child(9)").text();
          location = $("#claimprinttable tbody tr:nth-child("+t+")").find("td:nth-child(10)").text();
          
          amount = $("#claimprinttable tbody tr:nth-child("+t+")").find("td:nth-child(11)").text().replace("KES. ","");
          amount = parseFloat(amount.replace(",",""));
          claimdata = {
            'ticketno':ticketno,
            'jobcardno':jobcardno,
            'billingrefno':billingrefno,
            'client':client,
            'task':task,
            'location':location,
            'date':date,
            'time':time,
            'amount':amount
        }
          claimlist.push(claimdata);
         }

     
       formdata.append('claimsdata',JSON.stringify(claimlist));
       console.log(claimlist);
       $.ajax({
            url:"/printpreview",
            method:"post",
            data:formdata,
            processData:false,
            contentType:false,
            success:function(response){
                if(response.success ==true){
                    $(".message").text(response.message);
                    $("#alertmessage").removeClass("alert-danger");
                    $("#alertmessage").addClass("alert-success");
                    $("#alertmessage").css("display","block").fadeOut(5000);

                    setTimeout(function(){
                      window.open("/printpreview", "Print Claims", "width=1200,height=900,scrollbars=yes");
                          
                   }, 2000);
                }
    
            }
        });
       
    }
});

$(document).ready(function(){
    var pathname = window.location.pathname;
    if(pathname=="/printpreview"){
        var d = new Date();
        var month = d.getMonth()+1;
        var day = d.getDate();
        var output = d.getFullYear() + '' +
    ((''+month).length<2 ? '0' : '') + month + '' +
    ((''+day).length<2 ? '0' : '') + day;
        var claimno = Math.floor(Math.random()*10000)+1;
        $("#claimno").text(output+"/"+claimno);
    }
});

// EXIT CLAIM PRINT PREVIEW BUTTON
$("#exitbtn").click(function(){
    $("#showprintout").css("display", "none").fadeOut(2000);
       $("#showclaimlist").fadeIn(2000);
       $("#claimupdateform")[0].reset();
});

// CLAIM PRINT BUTTON
$("#printclaims").click(function(){
    var claimno = $("#claimno").text();

    $.ajax({
        url:"/resetprintclaims",
        data:{"claimno":claimno},
        dataType:"json",
        method:"get",
        success:function(response){
            if(response.success ==true){
              //  console.log(response.message);
              var css = '@page { size: landscape; }',
                 head = document.head || document.getElementsByTagName('head')[0],
                style = document.createElement('style');
                style.type = 'text/css';
                style.media = 'print';

                if (style.styleSheet){
                    style.styleSheet.cssText = css;
                  } else {
                    style.appendChild(document.createTextNode(css));
                  }

              head.appendChild(style);
              window.print();
              document.location.reload();
              // printJS('printpage', 'html');
            }
        },
        error:function(error){
            console.log("Error sending data.");
            console.log(error);
        }
    });


});

/*  SEARCH SERVICE TICKETS TABLE */
$(document).ready(function(){

    //$('#serviceticketstable table tbody tr').hide();
/*
    $.ajax({
        url:"servicetickets",
        method:"get",
        dataType:"json",
        success:function(data){
            $("#csrtable").DataTable({
                processing: true,
                dom:'Bfrtip',
                buttons:[
                         {
                            extend: 'excelHtml5',
                            title: 'Product Register'
                        },
                            {
                             extend: 'csvHtml5',
                            title: 'Product Register'
                            },
                            {
                             extend: 'copyHtml5',
                             title: 'Product Register'
                            },
                         {
                             extend: 'pdfHtml5',
                             title: 'Product Register'
                            }
                   // 'copy','csv','excel','pdf','print'
                        ],
                        data: data,
                        createdRow: function(row,data,index){},
                        columns:[
                            {mRender:function(data,type,row){
                                var td =
                                '<div class="row pt-2">'+data.ticketno+'</div>                                ';
                            }}
                        ]
            });
        }
    });
*/

});



$(document).ready(function(){
    var pathname = window.location.pathname;
    if(pathname=="/servicetickets"){
   
      $("#serviceticketstable").DataTable();
    }

    if(pathname =="/dashboard"){
        $.ajax({
            url:"dashboardinfo",
            method:"get",
            dataType:"json",
            success:function(data){
                console.log(data);
                $.each(data, function(index,val){
                    console.log(index);
                  //  console.log("claimanount "+val.totalclaim);
                    $("#claimtotal").text("KES. "+$.    number(data[0].totalclaim));
                    $("#pendingtickets").text($.number(data[1].pendingtickets,0)+" Tickets");
                });
            }
        });
    }
});
  

$("#datepicker").datepicker({ 
    autoclose: true, 
    todayHighlight: true,
}).datepicker('update', new Date());

$('#start_time').datetimepicker({
    format: 'hh:mm:ss a',
    locale: 'ru'
});

$("#projectslist_table").DataTable();
$("#claimprinttable").DataTable();

 /*

function getServicetickets(){
    $.ajax({
        url:"gettickets",
        method:"get",
        dataType:"json",
        success:function(data){
            console.log(data);
          var i=1;
           $("#serviceticketstable").DataTable({
               processing: true,
                dom:'Bfrtip',
                buttons:[
                         {
                            extend: 'excelHtml5',
                            title: 'Product Register'
                        },
                            {
                             extend: 'csvHtml5',
                            title: 'Product Register'
                            },
                            {
                             extend: 'copyHtml5',
                             title: 'Product Register'
                            },
                         {
                             extend: 'pdfHtml5',
                             title: 'Product Register'
                            }
                   // 'copy','csv','excel','pdf','print'
                 ],
                data: data,
                  createdRow: function(row,data,index){
                   
                     $(row).attr('id',data.id).find('td').eq(1).attr('class','td-no');
                     $(row).attr('id',data.id).find('td').eq(2).attr('class','td-ticketno');
                     $(row).attr('id',data.id).find('td').eq(3).attr('class','td-ticketdate');
                     $(row).attr('id',data.id).find('td').eq(4).attr('class','td-client');
                     $(row).attr('id',data.id).find('td').eq(5).attr('class','td-location');
                     $(row).attr('id',data.id).find('td').eq(6).attr('class','td-fault');
                     $(row).attr('id',data.id).find('td').eq(7).attr('class','td-urgency');                  
                     $(row).attr('id',data.id).find('td').eq(10).attr('class','td-dayspending');
                     $(row).attr('id',data.id).find('td').eq(9).attr('class','td-status');
                              
                    
                 },
                  columns:[
                     
                      {mRender:function(){
                             return i++;
                         }},
                        {data:"ticketno"},
                     
                       {mRender:function(data,type,row){
                          return moment(row.ticketdate).format("ddd Do MMM,YYYY");
                       }},
                       {data:"clientname"},
                       {data:"location"},
                       {data:"faultreported"},
                       {data:"urgency"},
                     
                         {mRender:function(data,type,row){
                          var diff; 
                          var today = new Date(); var days=""; var daycount="Day";
                           var ticketdate = row.ticketdate;
                           var millisecBtn = today.getTime() - new Date(ticketdate).getTime();
                           var millisecondsPerDay = 1000 * 60 * 60 * 24;

                              if(row.status !=="Closed"){
                                  diff = parseInt(millisecBtn/millisecondsPerDay);
                                  if(diff>1){daycount= "Days"}
                                  days = diff+" "+daycount;
                              }else{
                                days ="N/A";
                              }
                              return days;
                         }},
                         {mRender:function(data,type,row){
                            var status ="";
                                  if(row.status=="Closed"){
                                      status = '<i class="fa fa-circle text-success text-center" aria-hidden="true" style="font-size: 14px;">&nbsp;<span style="font-size:12px;"></span></i>';
                                    
                                  }else  if(row.status=="Awaiting Parts"){
                                      status = '<i class="fa fa-circle text-warning text-center" aria-hidden="true" style="font-size: 14px;">&nbsp;<span style="font-size:12px;"></span></i>';
                                   } 
                                   else if(row.status=="No Access"){
                                      status = '<i class="fa fa-circle text-secondary text-center" aria-hidden="true" style="font-size: 14px;">&nbsp;<span style="font-size:12px;"></span></i>';
                                   }
                                   else{
                                      status = '<i class="fa fa-circle text-danger text-center" aria-hidden="true" style="font-size: 14px;">&nbsp;<span style="font-size:12px;"></span></i>';
                                  }
                                  return status;
                             }},
                      
                                          
                    
                 ],
                   pageLength:10,
                   bLengthChange:false,
                   bAutoWidth:false,
                   autowidth:false,
                   bDestroy: true,
           });
           $('.dataTables_length').addClass('bs-select');
        }
    });
}

*/