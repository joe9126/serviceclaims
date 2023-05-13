$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

var originalState = $("#savecsrBtn").clone();

$(document).ready(function(){
    $("#projectslist_table").on("click","tbody tr",function(){

        var ticketno = $(this).closest('tr').attr('id');
        var jobcardno = $(this).children("td:nth-child(3)").text();
         console.log("ticket no is: "+jobcardno);
        $("#claimslist").fadeOut();
        $("#claimslist").css("display", "none");
        //$("#claimupdate").css("display", "block");
        $("#claimupdate").slideDown("slow");
      //  $("#claimupdate").css({display:'flex',justify:'center'});
        getClaimdetails(ticketno,jobcardno);
    });
});

// GO BACK BUTTON
$("#showlist").click(function(){
    $("#claimupdate").css("display", "none").fadeOut(2000);
    $("#claimslist").fadeIn(2000);

    document.location.reload();
});


function  getClaimdetails(ticketno, jobcardno){
  $("#claimupdateform").trigger("reset");

    $.ajax({
        type: "get",
        url: "ticketclaims/showclaim",
        data:{"ticketno":ticketno,"jobcardno":jobcardno},
        dataType:"json",
        contentType:"false",
        processData:"false",
        success: function(response) {
          //console.log("Data successfully sent!");
        console.log(response);

        var servicedate = moment(response[0].start_time).format("Do MMM, YYYY");
          var cardtitle = "Ticket "+response[0].ticketno+" "+response[0].clientname+", "+response[0].location+" On "+servicedate;
          $("#cardtitle").text(cardtitle);
            $("#jobcardno").val(response[0].jobcardno);
          $("#cardtitle-claimamount").text("| Kes. "+$.number(response[0].claimamount,2));
          var claimstatus = response[0].claimstatus
            if(claimstatus ==null){
                $("#cardtitle-claimstatus").text("Unclaimed");
            }else{
                $("#cardtitle-claimstatus").text(claimstatus);
            }
          

          $("#jobcardno").val(response[0].jobcardno); $("#ticketno").val(response[0].ticketno);
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

//claim values input
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

       //km claim calculation
 $("#kmtravel").keyup(function(){
    var distance = parseFloat($("#kmtravel").val());

        var distclaim = 0; var rateperkmfirst40km = 40; var rateperkmforextrakm = 20;
    if($("#zone").val()=="Nairobi"){
        distclaim = (distance * rateperkmfirst40km);
        $("#kmclaim").val(distclaim);
    }else{
        if(distance>40){
            var extradistance=0;
            extradistance = distance - 40;
            distclaim = (rateperkmfirst40km * 40)+(extradistance * rateperkmforextrakm);
            $("#kmclaim").val(distclaim);
        }
        else{
            distclaim = rateperkmfirst40km * distance;
            $("#kmclaim").val(distclaim);
        }
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
        $(".zone").css("display","block");
        $(".busfare").css("display","none");
        $(".companyprovided").css("display","none");

    }else if($(this).val()=="Public"){
        $(".companyprovided").css("display","none");
        $(".kmtravel").css("display","none");
        $(".zone").css("display","none");
        $(".busfare").css("display","block");
    }else{
        $(".zone").css("display","none");
        $(".companyprovided").css("display","block");
        $(".kmtravel").css("display","none");
        $(".busfare").css("display","none");
    }
});
//zone selection
$("#zone").on("change",function(){
    if($(this).val()=="Nairobi"){
        $("#rate").val("KES. 40 per km");
        $("#rate").css({"font-size":"14px"});
    }else{
        $("#rate").val("@KES. 40 for 1st 40km, @Kes.20 extra km ");
        $("#rate").css({"font-size":"13px"});
    }
});
// claim update form submission
$("#claimupdateform").on("submit",function(event){
    event.preventDefault();

    var claimstatus = $("#cardtitle-claimstatus").text();
   // console.log("claim no: "+claimno);
    if(claimstatus =="Claimed"){
        $(".message").text("This ticket is already claimed. Contact admin.");
        $("#alertmessage2").removeClass("alert-success");
        $("#alertmessage2").addClass("alert-danger");
        $("#alertmessage2").css("display","block").fadeOut(4000);;
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
    var formData = new FormData();
    formData.append('ticketno',$("#ticketno").val());
    formData.append('jobcardno',$("#jobcardno").val());
    formData.append('psvfare',$("#busfare").val());
    formData.append('accommodation',$("#accommodation").val());
    formData.append('petties',$("#petties").val());
    formData.append('dinner',$("#dinner").val());
    formData.append('lunch',$("#lunch").val());
    formData.append('kmclaim',$("#kmclaim").val());
    formData.append('km',$("#kmtravel").val());
    formData.append('claimtotal',claimtotal);
   console.log(formData);
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
                $("#claimsubmit").replaceWith(originalState);
                $(".message").text(response.message);
                $("#alertmessage2").removeClass("alert-success");
                $("#alertmessage2").addClass("alert-danger");
                $("#alertmessage2").css("display","block").fadeOut(3000);;
            }
        },
        error: function(error) {
            $(".message").text("An error occured. Claim was not updated");
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
       var claimdata = {};var claimlist =[];
       var rows = $("#claimprinttable >tbody >tr").length;
       console.log("lenght: "+rows);

       var formdata = new FormData();

       //for(var t=1;t=rows;t++)
       $("#claimprinttable").find('tbody').find('tr').each(function(){
          ticketno = $(this).find("td:nth-child(3)").text();
          date = $(this).find("td:nth-child(4)").text().replace(/\s/g, '');
          time = $(this).find("td:nth-child(5)").text().replace(/\s/g, '');
          jobcardno = $(this).find("td:nth-child(6)").text().replace(/\s/g, '');
          billingrefno = $(this).find("td:nth-child(7)").text();

          client = $(this).find("td:nth-child(8)").text();
          task = $(this).find("td:nth-child(9)").text();
          location = $(this).find("td:nth-child(10)").text();

          amount = $(this).find("td:nth-child(11)").text().replace("KES. ","");
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
       );


       formdata.append('claimsdata',JSON.stringify(claimlist));
      // console.log(claimlist);
       $.ajax({
            url:"/printpreview",
            method:"post",
            data:formdata,
            processData:false,
            contentType:false,
            success:function(response){
               // console.log(response);
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

//generate claim number
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
      // $("#claimupdateform")[0].reset();
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
              setTimeout(window.close, 0);
             // document.location.reload();
              // printJS('printpage', 'html');
            }
        },
        error:function(error){
            console.log("Error sending data.");
            console.log(error);
        }
    });
    window.onfocus=function(){ document.location.reload();}
});



//auto load service tickets dashboard info
$(document).ready(function(){
    var pathname = window.location.pathname;
    if(pathname=="/servicetickets"){
     $("#serviceticketstable").DataTable();
    }

    if(pathname =="/dashboard"){
        console.log("path: "+pathname);
        $.ajax({
            url:"dashboardinfo",
            method:"get",
            dataType:"json",
            success:function(data){
               // console.log(data);
                $.each(data, function(index,val){

                  //  console.log("claimanount "+val.totalclaim);
                  if(data[0] ==null){
                       $("#claimtotal").text("KES. 0.00");
                  }else{
                       $("#claimtotal").text("KES. "+$.    number(data[0].totalclaim));
                  }
                  if(data[1] !==null){
                    $("#unupdatedclaims").text($.number(data[1].unupdatedclaims,0)+" Tickets");
                  }
                  if(data[2] !==null){
                    $("#pendingtickets").text($.number(data[2].pendingtickets,0)+" Tickets");
                  }
                });
            }
        });
    }
});


$(function(){
    $(".datepicker").datetimepicker({
        value: '',
        rtl: false,
         format: 'd-m-Y H:i',
        formatTime: 'H:i',
       formatDate: 'd-m-Y',
       step: 30,
       monthChangeSpinner: true,
        closeOnDateSelect: false,
        closeOnTimeSelect: true,
        closeOnWithoutClick: true,
         closeOnInputClick: true,
         openOnFocus: true,
         timepicker: true,
         datepicker: true,

    });

    $(".csrdate").datetimepicker({
        value: '',
        rtl: false,
         format: 'd-m-Y',
      //  formatTime: 'H:i',
       formatDate: 'd-m-Y', 
       monthChangeSpinner: true,
        closeOnDateSelect: false,
        //closeOnTimeSelect: true,
        closeOnWithoutClick: true,
         closeOnInputClick: true,
         openOnFocus: true,
         timepicker: false,
         datepicker: true,

    });
  });

  

//display service ticket table

$(".exitupdate").on("click", function(event){
    event.preventDefault();
    $("#ticketupdate").fadeOut();
    $("#ticketupdate").css("display", "none");

   $("#ticketlist").fadeIn(2000);
   document.location.reload();

});

//serviceticket table row click

$("#serviceticketstable").on("click","tbody tr",function(){
    $("#ticketlist").fadeOut();
    $("#ticketlist").css("display", "none");
   $("#ticketupdate").fadeIn(1000);
 var ticketno = $(this).closest('tr').attr('id');
 //console.log("ticketno"+ticketno);
$.ajax({
    url:"showticket",
    type:"get",
    data:{"ticketno":ticketno},
    dataType:"json",
    beforeSend:function(){

    },
    success:function(data){
       // console.log(data);
       var ticketno = $("#ticketno").text(data[0].ticketno);
       var cardtitle = "Ticket # "+data[0].ticketno+" | "+data[0].clientname+" | "+moment(data[0].start_time).format("ddd d MMM, YYYY");
       $("#cardtitle").text(cardtitle);
       $("#jobcardno").val(data[0].jobcardno);
       $("#site").val(data[0].location);
       if(data[0].start_time !="no update"){
        $("#startdatetime").val(data[0].start_time);
       }
       if(data[0].end_time !="no update"){
        $("#enddatetime").val(data[0].end_time);
       }

       $("#equipmodel").val(data[0].model);
       $("#serialno").val(data[0].serialno);
       $("#findings").val(data[0].findings);
       $("#action_taken").val(data[0].action_taken);
       $("#recommendations").val(data[0].recommendations);
       if(data[0].attachment !="no file found"){
        $(".filelist").css("display","block");
        $("#filename").text(data[0].attachment);
        var pattern = /^((http|https|ftp):\/\/)/;
        if(pattern.test(data[0].attachment)) {
            $("#filename").attr("href", data[0].attachment);
        }
       }
       else{

       }


    },error:function(error){
        console.log("Error sending data.");
        console.log(error);
    }
});
});

//service ticket update form submit
$(document).on("submit","#ticketupdateform",function(event){
    event.preventDefault();
    $("#ticketupdateform").parsley();
    var starttime = new Date($("#startdatetime").val().replace(/(\d{2})-(\d{2})-(\d{4})/, "$2/$1/$3"));
    var endtime = new Date($("#enddatetime").val().replace(/(\d{2})-(\d{2})-(\d{4})/, "$2/$1/$3"));


        if($("#ticketupdateform").parsley().isValid()){
            if(!$('#jobcardupload').val() && !$("#filename").text()){
                $("#alertmessage2").focus();
                $(".message").text("Attach the job card.");
                $("#alertmessage2").removeClass("alert-success");
                $("#alertmessage2").addClass("alert-danger");
                $("#alertmessage2").css("display","block").fadeOut(10000);
                $("#alertmessage2")[0].scrollIntoView();
            }
            else if(moment(endtime).isBefore(moment(starttime))){
                //alert("error occured");
                $("#alertmessage2").focus();
                $(".message").text("Start time should be earier than end time.");
                $("#alertmessage2").removeClass("alert-success");
                $("#alertmessage2").addClass("alert-danger");
                $("#alertmessage2").css("display","block").fadeOut(10000);
                $("#alertmessage2")[0].scrollIntoView();
            }else{
               // alert("prepping form");
            var formdata = new FormData();

            formdata.append('ticketno', $("#ticketno").text());
            formdata.append('jobcardno', $("#jobcardno").val());
            formdata.append('city', $("#site").val());
            formdata.append('start_time', moment(starttime).format("YYYY-MM-DD HH:mm:ss"));
            formdata.append('end_time', moment(endtime).format("YYYY-MM-DD HH:mm:ss"));
            formdata.append('model', $("#equipmodel").val());
            formdata.append('serialno',  $("#serialno").val());
            formdata.append('findings', $("#findings").val());
            formdata.append('action_taken', $("#action_taken").val());
            formdata.append('recommendations', $("#recommendations").val());
            formdata.append('status', $("#ticketstatus option:selected").val());

            var attachment = $("#jobcardupload")[0].files[0];
            var fileInput = $.trim($("#jobcardupload").val());
            var filename = $('#jobcardupload').val().split('\\').pop();
            filename = $("#ticketno").text()+"- "+$("#jobcardno").val()+"-"+filename;
               // console.log(filename);

            if(filename!=null && fileInput !=='' && fileInput){
                formdata.append('attachment', attachment);
               formdata.append('filename',filename);
              // console.log("file attached");
            }else{
                filename = $("#filename").text();
                formdata.append('filename',filename);
            }
            var originalState = $("#submitupdate").clone();
           // console.log(formdata);
            $.ajax({
                url:"ticket/update",
                type:"post",
                data:formdata,
                dataType:"json",
                processData: false,
                contentType: false,
                beforeSend:function(){
                    var spinner = '<div class="spinner-border text-light fs-5" role="status"><span class="visually-hidden"> Loading...</span></div>';
                     $("#submitupdate").html("Updating "+spinner);
                },
                success:function(response){
                    $("#submitupdate").replaceWith(originalState);
                    if(response.success ==true){

                        $(".message").text(response.message);
                        $("#alertmessage2").removeClass("alert-danger");
                        $("#alertmessage2").addClass("alert-success");
                        $("#alertmessage2").css("display","block").fadeOut(5000);;
                        $(".top-row")[0].scrollIntoView();

                        $('#ticketupdateform')[0].reset();
                        $('#ticketupdateform').parsley().reset();


                    }else{

                        $("#submitupdate").replaceWith(originalState);
                        $(".message").text(response.message);
                        $("#alertmessage2").removeClass("alert-success");
                        $("#alertmessage2").addClass("alert-danger");
                        $("#alertmessage2").css("display","block").fadeOut(5000);
                        $("#alertmessage2")[0].scrollIntoView();
                    }
                },
                error:function(error){
                    $("#submitupdate").replaceWith(originalState);
                    console.log("error occured");
                }
            });

            }

    }
});

//download attachment
$("#downloadjobcardbtn").on("click",function(event){
    event.preventDefault();
    var filename = $("#filename").text();
    $.ajax({
        url:"ticket/checkfile?filename="+filename,
        type:"get",
        success:function(response){
            if(response.success ==true){
                window.location.href = "ticket/downloadjobcard?filename="+filename;
                $(".message").text(response.message);
                $("#alertmessage2").removeClass("alert-danger");
                $("#alertmessage2").addClass("alert-success");
                $("#alertmessage2").css("display","block").fadeOut(5000);;
                $(".top-row")[0].scrollIntoView();
            }
          else{
            $(".message").text(response.message);
            $("#alertmessage2").removeClass("alert-success");
            $("#alertmessage2").addClass("alert-danger");
            $("#alertmessage2").css("display","block").fadeOut(5000);;
            $(".top-row")[0].scrollIntoView();
          }
        }
    });

});

$("#projectslist_table").DataTable();
$("#claimprinttable").DataTable();
$("#stocklisttable").DataTable(
    {
        dom: 'Bfrtip',
        buttons: {
            buttons: [
                  { extend: 'copy', className: 'btn btn-primary'},
                  { extend: 'excel', className: 'btn btn-success' },
                  { extend: 'pdf', className: 'btn btn-dark'},
                  { extend: 'print', className: 'btn btn-danger'},
                     ],
             dom: {
                button: {
                className: 'btn'
                   }
             }
            }
    }
);
$("#issuepartsinventorytable").DataTable(
    {
        dom: 'Bfrtip',
        buttons: {
            buttons: [
                  { extend: 'copy', className: 'btn btn-primary'},
                  { extend: 'excel', className: 'btn btn-success' },
                  { extend: 'pdf', className: 'btn btn-dark'},
                  { extend: 'print', className: 'btn btn-danger'},
                     ],
             dom: {
                button: {
                className: 'btn'
                   }
             }
            }
    }
);

$("#partshistorytable").DataTable(
    {
        dom: 'Bfrtip',
        buttons: {
            buttons: [
                  { extend: 'copy', className: 'btn btn-primary'},
                  { extend: 'excel', className: 'btn btn-success' },
                  { extend: 'pdf', className: 'btn btn-dark'},
                  { extend: 'print', className: 'btn btn-danger'},
                     ],
             dom: {
                button: {
                className: 'btn'
                   }
             }
            }
    }
);


$("#csrtable").DataTable(
    {
        dom: 'Bfrtip',
        buttons: {
            buttons: [
                  { extend: 'copy', className: 'btn btn-primary'},
                  { extend: 'excel', className: 'btn btn-success' },
                  { extend: 'pdf', className: 'btn btn-dark'},
                  { extend: 'print', className: 'btn btn-danger'},
                 
                     ],
             dom: {
                button: {
                className: 'btn'
                   }
             }
            }
    }
);
//GET INVENTORY LIST
var itemslist=[]; var inventory = [];
$(document).ready(function(){
   $.ajax({
        url:'/partslist',
        type:'get',
        dataType:'json',
        success:function(data){
           $.each(data,function(index,val){
                    inventory.push({Key:val.partno,Value:val.description}); 
                    itemslist.push(val.partno); 
                   
                   //console.log(data);
                  });
        }
      });
});

//AUTO SEARCH PARTS 
$(function(){
    $(".partnosearch").autocomplete({
        source:function(request,response){
            var results = $.ui.autocomplete.filter(itemslist,request.term);
            response(results.slice(0,10));
           //console.log(itemslist); 
        }
    });
});

//PARTNO INPUT ON PRESS ENTER
$(function(){
    $(".partnosearch").on("keypress",function(event){
       // 
        if(event.which ===13){
            event.preventDefault();
            $.each(inventory, function() { 
                var stockpartno = this.Key;
                if(this.Key === $(".partnosearch").val()){ 
                 // console.log("part number is: "+this.Key);
                   $("#stockdescription").val(this.Value );
                } 

             
           });
        }
      
    });
});
/**
 * remove an item from the list
 */
$(document).ready(function(){
$("#issuedpartstable tbody").on("click","td",function(){
  if($(this).attr("class")=="td-remove"){
    $(this).closest("tr").remove();
   var rows = $("#issuedpartstable >tbody >tr").length;
   for(var t=1;t<=rows;t++){
    $("#issuedpartstable tbody tr:nth-child("+t+")").find("td:nth-child(2)").html(t);
   }
  }
   
});
});

/**
 * CLICK PARTS LIST TO SELECT AND ADD PART TO ISSUE LIST
 */
$("#issuepartsinventorytable").on("click","tbody tr",function(){
    
    //if(stockpartno === $("#ticketpartno").val())
    var selectedstockid = $(this).find("td:nth-child(2)").html();
    var selectedpart = $(this).find("td:nth-child(3)").html();
    var description  = $(this).find("td:nth-child(4)").html();
    var selectedpartqty = $(this).find("td:nth-child(5)").html();
    var scrollPos =  $("#alertmessage3").offset().top;
    // alert("qty "+selectedpartqty);
 
        var rows = $("#issuedpartstable >tbody >tr").length;
      //  console.log("number of rows: "+rows);
        var rowitem = "<tr>"+
        "<td class='td-remove'><button class='del-item'><i class='fa fa-times text-danger' aria-hidden='true'></i></button></td>"+
        "<td>"+(rows+1)+"</td>"+
        "<td>"+selectedstockid+"</td>"+
        "<td>"+selectedpart+"</td>"+
        "<td>"+description+"</td>"+
        "<td contenteditable='false'>1</td></tr>";
       
        if(rows==0){
            $(window).scrollTop(scrollPos);
            if(selectedpartqty<1){
                $("#alertmessage3").css("display","block").fadeOut(3000);
                $("#alertmessage3").removeClass("alert-success");
               $("#alertmessage3").addClass("alert-danger");
               $(".message").text("Stock item is out of stock.");
            }else{
                $("#issuedpartstable tbody").append(rowitem);
                // console.log("number of rows: "+rows);
                 $("#alertmessage3").css("display","block").fadeOut(3000);
                 $("#alertmessage3").removeClass("alert-danger");
                  $("#alertmessage3").addClass("alert-success");
                  $(".message").text("Part added successfully");
            }
           
            
        }
        else{
           
           // const tbl = document.querySelectorAll("#issuedpartstable"); //put id of the table
           //using ES6 here with 'map'
           for(let i = 1; i<=rows; i++) {
            
              var issuedstockid = $("#issuedpartstable tbody tr:nth-child("+i+")").find("td:nth-child(3)").html(); 
              var qty =  $("#issuedpartstable tbody tr:nth-child("+i+")").find("td:nth-child(6)").html();
             // console.log(issuedstockid+". selected stockid "+selectedstockid); 
                 if(issuedstockid === selectedstockid) {
                    //console.log(issuedstockid+". selected stockid "+selectedstockid); 
                   
                  // console.log("issue qty: "+qty);
                  
                   if(selectedpartqty<=qty){      
                    console.log("available qty: "+selectedpartqty);            
                    $(window).scrollTop(scrollPos);
                    $("#alertmessage3").css("display","block").fadeOut(3000);
                     $("#alertmessage3").removeClass("alert-success");
                    $("#alertmessage3").addClass("alert-danger");
                    $(".message").text("Stock item has only "+selectedpartqty+" pieces left.");
                   
                                      
                   }else{
                    $(window).scrollTop(scrollPos);
                      qty = parseInt(qty) + 1;
                     $("#issuedpartstable tbody tr:nth-child("+i+")").find("td:nth-child(6)").html(qty); 
                     $("#alertmessage3").css("display","block").fadeOut(3000);
                      $("#alertmessage3").removeClass("alert-danger");
                     $("#alertmessage3").addClass("alert-success");
                     $(".message").text("Part added successfully");
                     return false;
                   }
                   return true;
                  }
            
            }
          
                
                $(window).scrollTop(scrollPos);
                $("#issuedpartstable tbody").append(rowitem);
                $("#alertmessage3").removeClass("alert-danger");
                $("#alertmessage3").addClass("alert-success");
                $(".message").text("Part added successfully");
              
        }


    }
 
);

//AUTO LOAD CLIENTS
var clients = []; var clientname = [];
$(document).ready(function(){
    $.ajax({
        url:"/getclients",
        method:"get",
        dataType:"json",
        success:function(data){
           var options = null;
           
            
            $.each(data,function(index, item){
                options = "<option value='"+item.id+"' >"+item.clientname+"</option>";
                $("#clientid").append(options);
                clientname.push(item.clientname);
                clients.push({Key:item.id,Value:item.clientname}); 
               // console.log(clients);
            });
        }
    });
});

$(function(){
    $(".clientname").autocomplete({
        source:function(request,response){
            var results = $.ui.autocomplete.filter(clientname,request.term);
            response(results.slice(0,10));
           //console.log(itemslist); 
        }
    });
});

$(function(){
    $(".clientname").on("keypress",function(event){
       
       if(event.which ===13){
            event.preventDefault();
           // alert("clicked enter");
            $.each(clients, function() { 
                if(this.Value === $(".clientname").val()){ 
                   //console.log("client id is: "+this.Key);
                   $("#clientid").text(this.Key );
                } 
           });
        }
      
    });
});
/***end of client auto search **/

// register/UPDATE  part number

$(document).on("submit","#partregisterform",function(event){
    event.preventDefault();
    $("#partregisterform").parsley();

    if($("#partregisterform").parsley().isValid()){
        var formdata = new FormData();

       var status =  $('input[name="lockstatus"]:checked').val();
        formdata.append('status', status);
        formdata.append('partno',   $("#partno").val());
        formdata.append('description',   $("#description").val());

        $.ajax({
            url:"/addpartsinventory",
            method:"post",
            data:formdata,
            processData: false,
            contentType: false,
            success:function(response){
                $(".message").text(response.message);
                $("#alertmessage2").removeClass("alert-danger");
                $("#alertmessage2").addClass("alert-success");
                $("#alertmessage2").css("display","block").fadeOut(3000);
                $("#partregisterform")[0].reset();

                setTimeout(function(){ 
                    document.location.reload();
               }, 3000);
                 
            },error:function(error){
                console.log("error occured");
                $(".message").text(response.message);
                $("#alertmessage2").addClass("alert-danger");
                $("#alertmessage2").removeClass("alert-success");
                $("#alertmessage2").css("display","block").fadeOut(5000);;
            }
        });
    }
});
/**
 * ISSUE PARTS TO USER
 */
$(document).on("submit","#issuepartform",function(event){
    event.preventDefault();
    $("#issuepartform").parsley();
    if($("#issuepartform").parsley().isValid()){
        var rows = $("#issuedpartstable >tbody >tr").length;
        console.log("rows found: "+rows);
        if(rows==0||rows==null){
            $(".message").text("Add at least one part");
                $("#alertmessage3").removeClass("alert-success");
                $("#alertmessage3").addClass("alert-danger");
                $("#alertmessage3").css("display","block").fadeOut(3000);
        }
        else{
            var formData = new FormData();
            var partslist = [];
            var clientticket = $("#ticketclientname").val();
            var ticketno = $("#assignedticketno").val();
            var assignee = $("#issuedto option:selected").val();
            var issuedon = new Date($("#assignedon").val().replace(/(\d{2})-(\d{2})-(\d{4})/, "$2/$1/$3"));
            var date = moment(issuedon).format("YYYY-MM-DD HH:mm:ss");
            var issuedpartno,issuedqty, item, stockid;
            var rows = $("#issuedpartstable tbody tr").length;
           for(var i=1;i<=rows;i++){
               stockid = $("#issuedpartstable tbody tr:nth-child("+i+")").find("td:nth-child(3)").html();
                issuedpartno = $("#issuedpartstable tbody tr:nth-child("+i+")").find("td:nth-child(4)").html();
                issuedqty = $("#issuedpartstable tbody tr:nth-child("+i+")").find("td:nth-child(6)").html();
                item = {"ticketno":ticketno,"client":clientticket,"issuedto":assignee,"partno":issuedpartno,"quantity":issuedqty,"issuedon":date,"stockid":stockid};
                partslist.push(item);
              
            }
            formData.append("items",JSON.stringify(partslist));
           // console.log(formData);
           var originalState = $("#issuepartsbtn").clone();
           $.ajax({
            url:"/issueparts",
            method:"post",
            data:formData,
            processData:false,
            contentType: false,
            beforeSend:function(){
                var spinner = '<div class="spinner-border text-light fs-5" role="status"><span class="visually-hidden"> Loading...</span></div>';
                 $("#issuepartsbtn").html(spinner+"Processing..." );
            },
            dataType:"json",
            success:function(response){
                $("#issuepartsbtn").replaceWith(originalState);
                $(".message").text(response.message);
                $("#alertmessage3").removeClass("alert-danger");
                $("#alertmessage3").addClass("alert-success");
                $("#alertmessage3").css("display","block").fadeOut(3000);
                $("#issuepartform")[0].reset();
                $("#issuedpartstable tbody tr").remove();
                setTimeout(function(){ 
                    document.location.reload();
               }, 3000);
            },
            error:function(error){
                $("#issuepartsbtn").replaceWith(originalState);
            }
           });
        }
    }
});


/*add part stock*/

$(document).on("submit","#partstockform",function(event){
    event.preventDefault();
    $("#partstockform").parsley();

    if($("#partstockform").parsley().isValid()){
        var formdata = new FormData();
        formdata.append("client",$("#clientid").text());
        formdata.append("partno",$("#stockpartno").val());
        formdata.append("quantity",$("#partqty").val());
        formdata.append("refno",$("#refno").val());
        formdata.append("ticketno",$("#ticketno").val());
        var receivedon = new Date($("#datereceived").val().replace(/(\d{2})-(\d{2})-(\d{4})/, "$2/$1/$3"));
        formdata.append('receivedon', moment(receivedon).format("YYYY-MM-DD HH:mm:ss"));
       // console.log(formdata);
        $.ajax({
            url:"/addstock",
            type:"post",
            data:formdata,
            dataType:"json",
            processData:false,
            contentType:false,
            success:function(response){
                $(".message").text(response.message);
                $("#alertmessage").removeClass("alert-danger");
                $("#alertmessage").addClass("alert-success");
                $("#alertmessage").css("display","block").fadeOut(3000);
                $("#partstockform")[0].reset();
                setTimeout(function(){ 
                    document.location.reload();
               }, 3000);
            },
            error:function(error){
                $(".message").text(response.message);
                $("#alertmessage").addClass("alert-danger");
                $("#alertmessage").removeClass("alert-success");
                $("#alertmessage").css("display","block").fadeOut(3000);
               // $("#partstockform")[0].reset();
            }


        });
    }


});
//get dashboard info about supply requests
$(document).ready(function(){
    var pathname = window.location.pathname;
    if(pathname=="/dashboard"){
        ///load supplyrequests info
        $.ajax({
            url:"/getCsrinfo",
            type:"get",
            success:function(data){
              //  console.log(data);
                var dataitem;
                $.each(data,function(index,value){
                    dataitem = "<tr><td>"+value.currency+". "+$.number(value.csrvalue,2)+"</td></tr>";
                    $("#csrinfo tbody").append(dataitem);

                });
            }
        });
    }
});

//change displays for the parts inventory
$(function(){
    $(".action").on("click",function(){
          $("#wrapper").show();
        if($(this).attr("id")=="viewstock"){
            $("#collapseWidth").css({"display":"block"}); 
            $("#wrapper").children().hide();

            $("#viewstocklist").css({"display":"block"}); 
        
            $("#inventorymngmnt").css({"display":"none"});
        }
        if($(this).attr("id")=="trackparts"){
            $("#collapseWidth").css({"display":"block"}); 
            $("#wrapper").children().hide();
            
            
            $("#trackpartsection").css({"display":"block"});
          
            $("#inventorymngmnt").css({"display":"none"});
          
        }
        else if($(this).attr("id")=="issueparts"){
            $("#collapseWidth").css({"display":"block"}); 
            $("#wrapper").children().hide();
            
         
            $("#inventorymngmnt").css({"display":"none"});
            $("#issuepartssection").show();
        }
    });
});

$(".direct").on("click",function(){
   // $("#collapseWidth").css({"display":"block"}); 
    $("#wrapper").children().hide();
     $("#wrapper").css({"display":"none"}); 
  
    $("#inventorymngmnt").css({"display":"block"});
});

/**
 * search part number during assignment
 */
$(function(){
    $("#ticketpartno").on("keypress",function(event){
        if(event.which=="13"){
          
        }
    });
});

/**
 * get all users
 */
$(function(){
    var options;
    $.ajax({
        url:"/getusers",
        method:"get",
        dataType:"json",
        success:function(data){
            $.each(data,function(index,value){
                options = "<option value='"+value.id+"' >"+value.name+"</option>";
                $("#issuedto").append(options);
                $("#salespersons1").append(options);
                $("#sendto").append(options);
                
            });
        }
    });
});

//check this function in supplyrequests.js
/**
 * change display when new csr btn is clicked
 */
/*
$("#newcsrbtn").on("click",function(){
    $("#csrpanel").children().hide();
    $("#csrinfosection").hide();
    
    $("#createcsrsection").show();
    countCSR(); //display the csr number
  });
  */

$(".goback").on("click",function(){
    $("#createcsrsection").hide();
    $("#csrinfosection").show();
    $("#csrpanel").children().hide();
    $("#csrdisplay").show("#csrdisplay");
});
