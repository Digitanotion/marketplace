$(document).ready(function () {
    
 

   
   $("#formID").submit(function (event) {
       var formData = {
         firstField : $("#firstField").val(),
         lastField : $("#lastField").val(),
         emailField: $("#emailField").val(),
         phoneField: $("#phoneField").val(),
         passwordField: $("#passwordField").val(),
        
       };

   
       $.ajax({
         type: "POST",
         url: "../services/AccS/signup.php",
         data: formData,
         dataType: "json",
         encode: true,
         enctype : true,
       }).done(function (data) {

 
        //Returns 1 on success, 0 on DB error or 102 on failure if the email and phone number has been used,
        //returns 101 on failure when the email has been used, returns 100 on failure when the phone has been used
           
             if(data == 1){
            
               document.getElementById("msg").innerText = "Registration successful";
               $('.toast').toast('show');

               setTimeout(function(){
                    window.location = "signin.html";
               }, 2000);
               
             }else if(data == 0){
                document.getElementById("msg").innerText = "Error connecting to server";
                $('.toast').toast('show');

             }else if(data == 102){
              document.getElementById("msg").innerText = "Email and phone has been used on an existing account";
              $('.toast').toast('show');

             }else if(data == 101){
              document.getElementById("msg").innerText = "Email has been used on an existing account";
              $('.toast').toast('show');

            }else if(data == 100){
              document.getElementById("msg").innerText = "Phone number has been used on an existing account";
              $('.toast').toast('show');
              
            }
  
                
         }).fail(function (data) {
                
                document.getElementById("msg").innerText = "Error connecting with the server. Please try again";
                $('.toast').toast('show');
               
       });
   
       event.preventDefault();
     });
 
 
 
 
 /////GIF LOADER /////////////////////
 
   $(document).ajaxStart(function(){
     $("body").addClass("loading"); 
   });
 
   $(document).ajaxStop(function(){
     $("body").removeClass("loading"); 
   });
 ////////GIF LOADER END///////////////////////////////
 
 
 ////////hide the notification box
     $("#code").click(function(){
       $("#notify").hide("slow", "swing");
       $("#enter").show("slow", "linear");
     });
 
 // hide end   /////////////////////
 
 
 ///////////////////TRY AGAIN  ///////////////////
 
 $("#try-again").click(function(){
 
   $("#get2").show("slow", "swing");
   $("#notify1").hide("fast", "linear");
   $("#formID").show("fast", "swing");
 });
 
 
 
 /////////TRY AGAIN END //////////////////////
 
 
 
 ////auto move to next input box
 
   $("#num1").on("keyup", function(){
     $("#num2").focus();
   });
 
   $("#num2").on("keyup", function(){
     $("#num3").focus();
   });
 
   $("#num3").on("keyup", function(){
     $("#num4").focus();
   });
 
 ///////////////////////////////////////
 
 
 
 
 //////////////////////GO BACK////////////////
 
 $("#back").click(function(){
   $("#get2").show("slow", "swing");
   $("#fail").hide("fast", "linear");
   $("#formID").show("fast", "swing");
   });
 
 
 
 ////////////////////GO BACK END /////////////
 
 
 
 
 
 
   //to validate the code entered
 
   $("#formValid").submit(function (event) {
     var formData = {
       num1: $("#num1").val(),
       num2: $("#num2").val(),
       num3: $("#num3").val(),
       num4: $("#num4").val(),
      
     };
 
     $.ajax({
       type: "POST",
       url: "../services/AccS/validate.php",
       data: formData,
       dataType: "json",
       encode: true,
       enctype : true,
     }).done(function (data) {
 
          //validate.php should return 1 on success, 0 on failure
        
 
           if(data == 1){
             $("#enter").hide("slow", "linear");
             $("#success").show("slow", "linear");
           }else if(data == 0){
             $("#fail").show("slow", "swing");
             $("#enter").hide("fast", "linear");
           }
    
              
       }).fail(function (data) {
 
         $("#enter").hide("slow", "linear");
         $("#fail").show("slow", "swing");
     });
 
     event.preventDefault();
   });






   ////////////// Handle sign in ///////////////////

 
   $("#formSign").submit(function (event) {
    var formData = {
      emailField: $("#emailField").val(),
      password: $("#password").val(),
       
    };

    $.ajax({
      type: "POST",
      url: "../services/AccS/signin.php",
      data: formData,
      dataType: "json",
      encode: true,
      enctype : true,
    }).done(function (data) {
       

      if(data == 1){
            
        document.getElementById("msg").innerText = "Login successful";
        $('.toast').toast('show');

        setTimeout(function(){
             window.location = "homepage.html";
        }, 2000);
        
      }else if(data == 0){
         document.getElementById("msg").innerText = "Invalid login details";
         $('.toast').toast('show');

      }else if(data == -1){
       document.getElementById("msg").innerText = "This account has been suspended. Contact support for assistance";
       $('.toast').toast('show');

      }
   
             
      }).fail(function (data) {

            document.getElementById("msg").innerText = "Failed to login. Try again";
            $('.toast').toast('show');
    });

    event.preventDefault();
  });
 
 
   //redirect to create new password
   $("#create").click(function(){
         window.location = "reset.php";
   });
   //////////end of redirect/////////////
 
 
  
 
   });