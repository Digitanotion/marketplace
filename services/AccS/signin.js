$(document).ready(function () {
    $("#formID").submit(function (event) {

      var formData = {
        email: $("#emailField").val(),
        password: $("#passwordField").val(),
       
      };
  
      $.ajax({
        type: "POST",
        url: "../services/AccS/signin.php",
        data: formData,
        dataType: "json",
        encode: true,
        enctype : true,
      }).done(function (data) {

            alert(data);
            console.log(data);

        }).fail(function (data) {
       
      });

      event.preventDefault();
    });
  });