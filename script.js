  $(".toggleForms").click(function(){
  
    $("#Login-form").toggle();
    $("#Signup-form").toggle();

  });

$("#diary").on('input propertychange', function() {
  
	$.ajax({ 
	method: "POST",
	url: "updateDatabase.php",
	data: {content: $("#diary").val()}
	})
	
  $(this).css({'color':'#31304E', 'backgroundColor':'#C4E2FC'});

});

/*
$("#diary").on('input', function() {
  });


$("#diary").bind('input propertychange', function() {

   $.ajax({
      method: "POST",
      url: "updateDatabase.php",
      data: { content: $("diary").val()}
    })
      .done(function(msg ){
      alert("Data saved: " + msg);
    });

  });


function checkPassword(str){
     //at least one number, one lowercase and one uppercase letter
     //at least six characters
    var re = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}/;
    return re.test(str);
  }  

document.getElementById('submit').onclick = function(){

   echo (checkPassword(str));

 };*/