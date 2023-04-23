$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});




$(document).ready(function(){
    $("#projectslist_table").on("click","tbody tr",function(){

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
    console.log("claim no: "+claimno);
    if(claimno !=="NA"){
        $(".message").text("This ticket is already claimed. Contact admin now.");
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
       //console.log(claimlist);
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
  });



//display service ticket table

$("#exitupdate").on("click", function(event){
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
       var cardtitle = "Ticket # "+data[0].ticketno+" | "+data[0].clientname+" | "+moment(data[0].ticketdate).format("ddd d MMM, YYYY");
       $("#cardtitle").text(cardtitle);
       $("#jobcardno").val(data[0].jobcardno);
       $("#site").val(data[0].site);
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
                console.log(filename);

            if(filename!=null && fileInput !=='' && fileInput){
                formdata.append('attachment', attachment);
               formdata.append('filename',filename);
               console.log("file attached");
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
