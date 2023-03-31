<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin login page</title>
    <link rel="stylesheet" href="./assets/css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="./assets/css/login.css">
    <link rel="stylesheet" href="./assets/css/registration.css">








    <style>
        .overlay{
            display: none;
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 999;
            background: rgba(255,255,255,0.8) url("./assets/images/loader3.gif") center no-repeat;
        }
    
        .over{
            display: none;
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 999;
    
    
            font-family: Arial, Helvetica, sans-serif;
            font-size: 150px;
            color: black;
            animation: fadeIn linear 7s;
            -webkit-animation: fadeIn linear 7s;
            -moz-animation: fadeIn linear 7s;
            -o-animation: fadeIn linear 7s;
            -ms-animation: fadeIn linear 7s;
        }
    
    
             /* @keyframes fadeIn {
             0% {opacity:0;}
             100% {opacity:1;}
             }
    
             @-moz-keyframes fadeIn {
             0% {opacity:0;}
             100% {opacity:1;}
             }
    
             @-webkit-keyframes fadeIn {
             0% {opacity:0;}
             100% {opacity:1;}
             }
    
             @-o-keyframes fadeIn {
             0% {opacity:0;}
             100% {opacity:1;}
             }
    
             @-ms-keyframes fadeIn {
             0% {opacity:0;}
             100% {opacity:1;}
             } */
    
    
        body{
            text-align: center;
        }
        /* Turn off scrollbar when body element has the loading class */
        /* body.loading{
            overflow: hidden;   
        } */
        /* Make spinner image visible when body element has the loading class */
        body.loading .overlay{
            display: block;
        }
    </style>
    


</head>
<body>




    <div style="position:absolute; z-index:999; bottom: 0; left:2%;" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
         
          <strong class="mr-auto">Notification</strong>
          <!-- <small>11 mins ago</small> -->
         
        </div>
        <div class="toast-body">
          <span id="msg"></span>
        </div>
      </div>



     <div class="overlay"></div>

    <div class="container-fluid">
        <div class="row">
                <!--Gaiijin column-->
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 gaiijincolumn">
                    <!--Elipse circle images-->
                <div class="gaiijincolumn__elipse">
                    <img src="assets/images/elipse.png" alt="elipse" class="img-fluid elipse_class">
                    <img src="assets/images/elipse(1).png" alt="elipse" class="img-fluid elipse_class--1">
                    <img src="assets/images/elipse-2.png" alt="elipse" class="img-fluid elipse_class--2">
                </div>
              
                    <!--Gaiijin image-->
                <img src="assets/images/Untitled_design__4_-removebg-preview 1.png" alt="Logo" class="img-fluid gaiijincolumn__logopic">
                
               <div class="gaiijincolumn__text">
                    <h3>I see you're the first <br> time here.</h3>
                    
                </div>
            </div>

                <!--Account details column-->
        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
            <form id="formLog"  method="POST"  class="input__field top--space mb-3">
                <div class="form-control accountdetails">
                    <h4 class="accountdetails__headtext mb-5">Admin login</h4>

                    <div class="row text-left">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-left">
                           
                                <label for="emailField"><h6>E-mail</h6></label>
                                <input name="emailField" type="E-mail" class="form-control" id="emailField" 
                                    placeholder="E-mail" required>
                    
                        </div>
                        
                    </div>

                    <div class="row mt-3">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            
                                <label for="passwordField"><h6>Password</h6></label>
                                <input name="passwordField" type="password" class="form-control" id="passwordField" 
                                    placeholder="**********" required>
                       
                        </div>
                    </div>


                 
                             
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                           <button type="submit" name="submit" class="btn-primary btn--accountdetails">Login</button> 
                        </div>
                    </div>

                </form>

                    
    
            </div>

             
                <!-- <p class="signin p-tag mt-4">Already have an account? <a href="signin.html">Sign in</a></p> -->
            </div>
        </div>
    </div>
    



    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="./assets/js/form.js"></script>
    <script src="./assets/js/bootstrap.min.js"></script>
</body>
</html>