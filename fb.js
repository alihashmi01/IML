//Author: Ali Hashmi, Medill
SEARCH_SIZE=20;
SEARCH_CONTAINER=2500;
ERROR_MESG="NO RESULTS FOUND";
ZERO=0;
ENTERKEY=13;

function displayTownDropDown() 
{
	
	$.getJSON("towns.json", function(result) {
		var options = " ";
		$.each(result.Towns, function(key, val) {
			// split the function
			options += '<option value="' + val.townName + '">' + val.townName.split(',')[0]; + '</option>';
		});
		var select = $("<SELECT name='txtLocation'>");
		select.append(options);
		$("#displayTownDropDownResults").append(select);
	});
	
}

function screenHeight()
{ 
	console.log("INSIDE: screenHeight()");
	$('#main').css('height', screen.height-300);
	var scrH =$("#main").css("height");
	console.log(screen.width);
	console.log(screen.height);
	console.log("scrH="+scrH);
	pressEnter();
}

function displayChangebox()
{



	$( ".target0" ).click(function() {
    	var text = $( this ).val();
		console.log("Value of checkbox:"+text);
		if (this.checked){
		$( ".removeterms0" ).val( $( ".removeterms0" ).val() + ','+ text );
		}
		else{
		var addback = $( ".removeterms0" ).val();
		console.log("Value of addback:"+addback);
		var currstr = $( this ).val().replace(/[^A-Za-z0-9\-]/, '')
		console.log("Value of currstr:["+currstr+"]");
		var xtr= addback.replace(","+currstr,"");
		$(".removeterms0").val(xtr);
		}
		
	});
	
	$( ".target1" ).click(function() {
    	var text = $( this ).val();
		console.log("Value of checkbox:"+text);
		if (this.checked){
		$( ".removeterms1" ).val( $( ".removeterms1" ).val() + ' ,'+ text );
		}	
		else{
		var addback = $( ".removeterms1" ).val();
		console.log("Value of addback:"+addback);
		var currstr = $( this ).val().replace(/[^A-Za-z0-9\-]/, '')
		console.log("Value of currstr:["+currstr+"]");
		var xtr= addback.replace(","+currstr,"");
		$(".removeterms1").val(xtr);
		}
	});
	
	$( ".target2" ).click(function() {
    	var text = $( this ).val();
		console.log("Value of checkbox:"+text);
		console.log("test?:" +     this.checked);
		if (this.checked){
			$( ".removeterms2" ).val( $( ".removeterms2" ).val() + ', '+ text );
		}
		else{
		var addback = $( ".removeterms2" ).val();
		console.log("Value of addback:"+addback);
		var currstr = $( this ).val().replace(/[^A-Za-z0-9\-]/, '')
		console.log("Value of currstr:["+currstr+"]");
		var xtr= addback.replace(","+currstr,"");
		$(".removeterms2").val(xtr);
		}
	
	});
	
	$( ".target3" ).click(function() {
    	var text = $( this ).val();
		console.log("Value of checkbox:"+text);
		if (this.checked){
		$( ".removeterms3" ).val( $( ".removeterms3" ).val() + ' ,'+ text );
		}
		else{
		var addback = $( ".removeterms3" ).val();
		console.log("Value of addback:"+addback);
		var currstr = $( this ).val().replace(/[^A-Za-z0-9\-]/, '')
		console.log("Value of currstr:["+currstr+"]");
		var xtr= addback.replace(","+currstr,"");
		$(".removeterms3").val(xtr);
		}
	});
	
	$( ".target4" ).click(function() {
    	var text = $( this ).val();
		console.log("Value of checkbox:"+text);
		if (this.checked){
		$( ".removeterms4" ).val( $( ".removeterms4" ).val() + ' ,'+ text );
		}
		else{
		var addback = $( ".removeterms4" ).val();
		console.log("Value of addback:"+addback);
		var currstr = $( this ).val().replace(/[^A-Za-z0-9\-]/, '')
		console.log("Value of currstr:["+currstr+"]");
		var xtr= addback.replace(","+currstr,"");
		$(".removeterms4").val(xtr);
		}
		
	});
	
	
	$( ".check_box" ).click(function() {
    	var text = $( this ).val();
		console.log("checked:"+this.checked);
		if (this.checked==true){
			$('.otherbox'+ text).prop('checked', false);
		}
		else{
			$('.otherbox'+ text).prop('checked', true);
		}
	});
	
	$( ".otherbox" ).click(function() {
    	var text = $( this ).val();
		console.log("checked:"+this.checked);
		if (this.checked==true){
			$('.check_box').prop('checked', false);
		}
		else{
			$('.check_box').prop('checked', true);
		}
	});
	
	
	
	
	
		
	$( ".fav0" ).click(function() {
    	var text = $( this ).val();
		console.log("Value of checkbox:"+text);
		$( ".favtopic" ).val( $( ".topicid0" ).val() );
	
	});
	
	$( ".fav1" ).change(function() {
    	var text = $( this ).val();
		console.log("Value of checkbox:"+text);
		$( ".favtopic" ).val( $( ".topicid1" ).val() );
		$('.fav0').prop('checked', false);
		$('.fav2').prop('checked', false);
		$('.fav3').prop('checked', false);
		$('.fav4').prop('checked', false);
		
	});
	
	$( ".fav2" ).change(function() {
    	var text = $( this ).val();
		console.log("Value of checkbox:"+text);
		$( ".favtopic" ).val( $( ".topicid2" ).val() );
    	$('.fav0').prop('checked', false);
		$('.fav1').prop('checked', false);
		$('.fav3').prop('checked', false);
		$('.fav4').prop('checked', false);
	});
	
	$( ".fav3" ).change(function() {
    	var text = $( this ).val();
		console.log("Value of checkbox:"+text);
		$( ".favtopic" ).val( $( ".topicid3" ).val() );
		$('.fav0').prop('checked', false);
		$('.fav1').prop('checked', false);
		$('.fav2').prop('checked', false);
		$('.fav4').prop('checked', false);
		
	});
	
	$( ".fav4" ).change(function() {
    	var text = $( this ).val();
		console.log("Value of checkbox:"+text);
		$( ".favtopic" ).val( $( ".topicid4" ).val() );
		$('.fav0').prop('checked', false);
		$('.fav1').prop('checked', false);
		$('.fav2').prop('checked', false);
		$('.fav3').prop('checked', false);
		
	});
	
}


function pressEnter()
{
	console.log("INSIDE: pressEnter()");
	$("#textbox").keypress(function(event) {
	  if ( event.which == ENTERKEY ) {
		 search();
		 }
	  
	});

}

function mainformvalidate()
{
	var x1=document.forms["mainform"]["topic0"].value;
	var x2=document.forms["mainform"]["topic1"].value;
	var x3=document.forms["mainform"]["topic2"].value;
	var x4=document.forms["mainform"]["topic3"].value;
	var x5=document.forms["mainform"]["topic4"].value;
	var f1 = document.forms["mainform"]["favchkbox0"].checked;
	var f2 = document.forms["mainform"]["favchkbox1"].checked;
	var f3 = document.forms["mainform"]["favchkbox2"].checked;
	var f4 = document.forms["mainform"]["favchkbox3"].checked;
	var f5 = document.forms["mainform"]["favchkbox4"].checked;
	var allset = 1;
	
	if (f1==false && f2==false && f3==false && f4==false && f5==false )
	{
	$('#mesgbox').text("Please select at least one topic to train or build.");
    allset = 0;
	}
	if ( (required(x1)==0) || (required(x2)==0) ||(required(x3)==0) ||(required(x4)==0) ||(required(x5)==0))
	{
	$('#mesgbox2').text("Please fill all topics.");
     allset = 0;
	}
	
	if (allset ==0)	{		return false;	}	else 	{		return true;	}
	
}

function required(x)
{
	if (x==null || x=="" || x==0 || x=="0" )
	  {
	  return 0;
	  }
}


function responsevalidate()
{
   	var allset = 1;
	for (i = 0;i < 200;i++)
	{
     	if ($("#user_response"+i).is(":empty"))
		{
			Console.log($("#user_response"+i).is(":empty"));
			$('#mesgbox').text("Please fill your response for item:"+i);
				allset = 0;
			}
		if (allset ==0)	{return false;}	else {return true;}
	 }
}







