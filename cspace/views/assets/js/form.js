$(document).ready(function () {
    
 

   
   $("#formID").submit(function (event) {
       


       if(document.getElementById("adspriv").checked && ! document.getElementById("kycpriv").checked){

        var formData = {
          firstField : $("#firstField").val(),
          lastField : $("#lastField").val(),
          emailField: $("#emailField").val(),
          phoneField: $("#phoneField").val(),
          passwordField: $("#passwordField").val(),
          adspriv : 1,
          kycpriv : 0,
        };

       }else if(! document.getElementById("adspriv").checked &&  document.getElementById("kycpriv").checked){

        var formData = {
          firstField : $("#firstField").val(),
          lastField : $("#lastField").val(),
          emailField: $("#emailField").val(),
          phoneField: $("#phoneField").val(),
          passwordField: $("#passwordField").val(),
          adspriv : 0,
          kycpriv : 1,
        };

       }else if(! document.getElementById("adspriv").checked && ! document.getElementById("kycpriv").checked){

        var formData = {
          firstField : $("#firstField").val(),
          lastField : $("#lastField").val(),
          emailField: $("#emailField").val(),
          phoneField: $("#phoneField").val(),
          passwordField: $("#passwordField").val(),
          adspriv : 0,
          kycpriv : 0,
        };

       }else if(document.getElementById("adspriv").checked &&  document.getElementById("kycpriv").checked){

        var formData = {
          firstField : $("#firstField").val(),
          lastField : $("#lastField").val(),
          emailField: $("#emailField").val(),
          phoneField: $("#phoneField").val(),
          passwordField: $("#passwordField").val(),
          adspriv : 1,
          kycpriv : 1,
        };

       }

   
       $.ajax({
         type: "POST",
         url: "../../adminsignup.php",
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

              //  setTimeout(function(){
              //       window.location = "signin.php";
              //  }, 2000);
               
             }else if(data == 102){
              document.getElementById("msg").innerText = "Email and phone has been used on an existing account";
              $('.toast').toast('show');

             }else if(data == 101){
              document.getElementById("msg").innerText = "Email has been used on an existing account";
              $('.toast').toast('show');

            }else if(data == 100){
              document.getElementById("msg").innerText = "Phone number has been used on an existing account";
              $('.toast').toast('show');
              
            }else if(data == 500){
              document.getElementById("msg").innerText = "Registration error";
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
 

 
 ////////////// Handle sign in ///////////////////

 
   $("#formLog").submit(function (event) {
    var formData = {
      emailField: $("#emailField").val(),
      passwordField: $("#passwordField").val(),
       
    };

    $.ajax({
      type: "POST",
      url: "adminlogin.php",
      data: formData,
      dataType: "json",
      encode: true,
      enctype : true,
    }).done(function (data) {
       

      if(data == 1){
     
        window.location = "cspace.php";
      
      }else if(data == 500){
         document.getElementById("msg").innerText = "Invalid login details";
         $('.toast').toast('show');

      }
   
             
      }).fail(function (data) {

            document.getElementById("msg").innerText = "Failed to login. Try again";
            $('.toast').toast('show');
    });

    event.preventDefault();
  });
 

 
  
 
   });