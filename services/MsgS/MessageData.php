<?php 
namespace services\MsgS;

class MessageData{
    protected $msg_;

     function MessageData(){
       
     }
 function _welcome_msg(string $to_name, $message1=null, $from_name=null, $from=null,$to=null,$message2=null, $link1=null,$link2=null){
     $this->msg_='
            <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
                <html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">

                <head>
                    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                    <meta name="viewport" content="width=device-width">
                    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet"> 



                </head>

                <body style="background-color: #f3faff; margin: 0; font-family: Montserrat; font-style:normal; font-weight: lighter;">
                    <table class="body" style="width: 90%; margin: 0 auto;">
                        <tr>
                            <td class="center" align="center" valign="top">
                                <center data-parsed="">
                                    <table class="container text-center" >
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <!-- This container adds the grey gap at the top of the email -->
                                                    <table class="row grey">
                                                        <tbody>
                                                            <tr>
                                                                <th class="small-12 large-12 columns first last">
                                                                    <table>
                                                                        <tr>
                                                                            <th>
                                                                                &#xA0;
                                                                            </th>
                                                                            <th class="expander"></th>
                                                                        </tr>
                                                                    </table>
                                                                </th>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    
                                    <table class="container" >
                                        <tbody>
                                            <tr style="background-color:#f3faff;">
                                            <td><center data-parsed="">
                                                                                    <a href="https://gaijinmall.com" align="center"
                                                                                        class="text-center">
                                                                                        <img src="./../views/assets/images/logo-sm.png"
                                                                                            class="swu-logo" style="margin-bottom: 15px;">
                                                                                    </a>
                                                                                </center></td>
                                            </tr>
                                            <tr style="width: 100%; background-color: #ffffff;">
                                                
                                                <td>
                                                    <!-- This container is the main email content -->
                                                    
                                                    <table class="row">
                                                        <tbody>
                                                            <tr>
                                                                <!-- Logo -->
                                                                <th class="small-12 large-12 columns first last">
                                                                    <table>
                                                                        <tr>
                                                                            <th>
                                                                                
                                                                            </th>
                                                                            <th class="expander"></th>
                                                                        </tr>
                                                                    </table>
                                                                </th>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <table class="row masthead" style="width: 100%; ">
                                                        <tbody style="width: 100%; ">
                                                            <tr >
                                                                <!-- Masthead -->
                                                                <th class="small-12 large-12 columns first last">
                                                                    <table style="width: 100%;">
                                                                        <tr style="margin: 0 auto; " >
                                                                            <th>
                                                                                <center data-parsed="">
                                                                                    <img src="./../views/assets/images/welcome5.svg"
                                                                                        valign="bottom" style="margin-top: 15px; object-fit:contain; width: 60%;" 
                                                                                        class="text-center">
                                                                                </center>
                                                                            </th>
                                                                            <th class="expander"></th>
                                                                        </tr>
                                                                    </table>
                                                                </th>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <table class="row">
                                                        <tbody>
                                                            <tr>
                                                                <!--This container adds the gap between masthead and digest content -->
                                                                <th class="small-12 large-12 columns first last">
                                                                    <table>
                                                                        <tr>
                                                                            <th>
                                                                                &#xA0;
                                                                            </th>
                                                                            <th class="expander"></th>
                                                                        </tr>
                                                                    </table>
                                                                </th>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <table class="row" align="left" style="text-align:left;">
                                                        <tbody>
                                                            <tr>
                                                                <!-- main Email content -->
                                                                <th class="small-12 large-12 columns first last" style="padding:0px 30px 0px 30px;">
                                                                    <table>
                                                                        <tr>
                                                                            <th>
                                                                                <b>
                                                                                    <h1 style=" font-weight: bold; margin-bottom: 8px;">Welcome to Gaijinmall</h1>
                                                                                </b>
                                                                                <p style="">
                                                                                    <p style="margin-top: 20px; margin-bottom: 35px; font-weight: 900; font-size: larger;"><b>Hello '.$to_name.',</b></p>
                                                                                    <p>Thanks for choosing Gaijinmall, We’re looking forward to helping your business scale. In order to get the most out of your Gaijinmall account, there are a few things you should know:</p>
                                                                                </p>
                                                                                <p >
                                                                                    <p style="margin-top: 20px; margin-bottom: 20px; font-size: larger;"><b>Getting Started</b></p>
                                                                                    <p>Everything you need can be conveniently found in your Gaijinmall Dashboard. From there, you can manage your profile, store, change your settings, promote your ad, etc.</p>
                                                                                </p>
                                                                                <br>
                                                                                <div class="button">
                                                                                <!-- <center style="color:red;font-family:sans-serif;font-size:16px;font-weight:bold;">Click le Button</center> */ -->
                                                                                <center>
                                                                                    <a href="'.$link1.'"
                                                                                        style="background-color:rgb(13, 110, 253); margin-top: 10px; padding:8px;border:0px solid #f7931d;border-radius:3px;color:#ffffff;display:inline-block;font-family:sans-serif;font-size:16px;font-weight:bold;line-height:35px;text-align:center;text-decoration:none;width:150px;-webkit-text-size-adjust:none;mso-hide:all;">My Dashboard</a>    
                                                                                </center>
                                                                                </div>
                                                                            </th>
                                                                            <th class="expander"></th>
                                                                        </tr>
                                                                    </table>
                                                                </th>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <table class="row">
                                                        <tbody>
                                                            <tr>
                                                                <!-- This container adds whitespace gap at the bottom of main content  -->
                                                                <th class="small-12 large-12 columns first last">
                                                                    <table>
                                                                        <tr>
                                                                            <th>
                                                                                &#xA0;
                                                                            </th>
                                                                            <th class="expander"></th>
                                                                        </tr>
                                                                    </table>
                                                                </th>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table> <!-- end main email content -->

                                    <table class="container text-center" align="center" style="text-align:center; width:100%;">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <!-- footer -->
                                                    <table class="row grey" style="width:100%;">
                                                        <tbody style="width:100%;">
                                                            <tr >
                                                                <th class="small-12 large-12 columns first last" >
                                                                    <table style="text-align: center; width:100%;">
                                                                        <tr>
                                                                            <th>
                                                                                <p class="text-center footercopy" style="font-size:smaller;">&#xA9; Copyright 2016
                                                                                    Gaijinmall. All Rights Reserved.</p>
                                                                            </th>
                                                                            <th class="expander"></th>
                                                                        </tr>
                                                                    </table>
                                                                </th>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>


                                </center>
                            </td>
                        </tr>
                    </table>
                </body>

                </html>
     ';

     return $this->msg_;
 }

 function _ad_msg($toName,$adTitle,$imgSrc,$mainMsg,$msgLink){
    $this->msg_='
                <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
            <html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">

            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                <meta name="viewport" content="width=device-width">
                <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet"> 



            </head>

            <body style="background-color: #f3faff; margin: 0; font-family: Montserrat; font-style:normal; font-weight: lighter;">
                <table class="body" style="width: 80%; margin: 0 auto;">
                    <tr>
                        <td class="center" align="center" valign="top">
                            <center data-parsed="">
                                <table class="container text-center" >
                                    <tbody>
                                        <tr>
                                            <td>
                                                <!-- This container adds the grey gap at the top of the email -->
                                                <table class="row grey">
                                                    <tbody>
                                                        <tr>
                                                            <th class="small-12 large-12 columns first last">
                                                                <table>
                                                                    <tr>
                                                                        <th>
                                                                            &#xA0;
                                                                        </th>
                                                                        <th class="expander"></th>
                                                                    </tr>
                                                                </table>
                                                            </th>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                
                                <table class="container" >
                                    <tbody>
                                        <tr style="background-color:#f3faff;">
                                        <td><center data-parsed="">
                                                                                <a href="https://gaijinmall.com" align="center"
                                                                                    class="text-center">
                                                                                    <img src="https://gaijinmall.com/views/assets/images/logo-sm.png"
                                                                                        class="swu-logo" style="margin-bottom: 15px;">
                                                                                </a>
                                                                            </center></td>
                                        </tr>
                                        <tr style="width: 100%; background-color: #ffffff;">
                                            
                                            <td>
                                                <!-- This container is the main email content -->
                                                
                                                <table class="row">
                                                    <tbody>
                                                        <tr>
                                                            <!-- Logo -->
                                                            <th class="small-12 large-12 columns first last">
                                                                <table>
                                                                    <tr>
                                                                        <th>
                                                                            
                                                                        </th>
                                                                        <th class="expander"></th>
                                                                    </tr>
                                                                </table>
                                                            </th>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <table class="row masthea" style="width: 100%; ">
                                                    <tbody style="width: 100%; ">
                                                        <tr >
                                                            <!-- Masthead -->
                                                            <th class="small-12 large-12 columns first last">
                                                                <table style="width: 100%;">
                                                                    <tr style="margin: 0 auto; " >
                                                                        <th>
                                                                            <center data-parsed="">
                                                                                <img src="https://gaijinmall.com/views/assets/images/welcome_verify.png"
                                                                                    valign="bottom" style="margin-top: 15px; object-fit:contain; width: 50%;" 
                                                                                    class="text-center">
                                                                            </center>
                                                                        </th>
                                                                        <th class="expander"></th>
                                                                    </tr>
                                                                </table>
                                                            </th>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <table class="row">
                                                    <tbody>
                                                        <tr>
                                                            <!--This container adds the gap between masthead and digest content -->
                                                            <th class="small-12 large-12 columns first last">
                                                                <table>
                                                                    <tr>
                                                                        <th>
                                                                            &#xA0;
                                                                        </th>
                                                                        <th class="expander"></th>
                                                                    </tr>
                                                                </table>
                                                            </th>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <table class="row" align="center" style="text-align:center;">
                                                    <tbody>
                                                        <tr>
                                                            <!-- main Email content -->
                                                            <th class="small-12 large-12 columns first last" style="padding:0px 30px 0px 30px;">
                                                                <table style="text-align:center;">
                                                                    <tr>
                                                                    <th class="expander"></th>
                                                                        <th>
                                                                            <b>
                                                                                <h3 style=" font-weight: bold; font-size:150%; margin-bottom: 30px;">Hello '.$toName.', </h3>
                                                                            </b>
                                                                            <p style="text-align: center; font-size:120%; ">'.$mainMsg.'</p>
                                                                        <table width="300" style="text-align: center;">
                                                                            <tr> 
                                                                            <div style="text-align: center;" align="center"><img src="'.$imgSrc.'" width="120"></div>
                                                                            <tr>
                                                                        </table>
                                                                            <br>
                                                                            <div class="button">
                                                                            <!-- <center style="color:red;font-family:sans-serif;font-size:16px;font-weight:bold;">Click le Button</center> */ -->
                                                                            <center>
                                                                                <a href="'.$msgLink.'"
                                                                                    style="background-color:rgb(13, 110, 253); margin-top: 10px; padding:8px;border:0px solid #f7931d;border-radius:3px;color:#ffffff;display:inline-block;font-family:sans-serif;font-size:16px;font-weight:bold;line-height:35px;text-align:center;text-decoration:none;width:150px;-webkit-text-size-adjust:none;mso-hide:all;">See Ad</a>    
                                                                            </center>
                                                                            </div>
                                                                        </th>
                                                                        <th class="expander"></th>
                                                                    </tr>
                                                                </table>
                                                            </th>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <table class="row">
                                                    <tbody>
                                                        <tr>
                                                            <!-- This container adds whitespace gap at the bottom of main content  -->
                                                            <th class="small-12 large-12 columns first last">
                                                                <table>
                                                                    <tr>
                                                                        <th>
                                                                            &#xA0;
                                                                        </th>
                                                                        <th class="expander"></th>
                                                                    </tr>
                                                                </table>
                                                            </th>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table> <!-- end main email content -->

                                <table class="container text-center" align="center" style="text-align:center; width:100%;">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <!-- footer -->
                                                <table class="row grey" style="width:100%;">
                                                    <tbody style="width:100%;">
                                                        <tr >
                                                            <th class="small-12 large-12 columns first last" >
                                                                <table style="text-align: center; width:100%;">
                                                                    <tr>
                                                                        <th>
                                                                            <p class="text-center footercopy" style="font-size:smaller;">&#xA9; Copyright 2016
                                                                                Gaijinmall. All Rights Reserved.</p>
                                                                        </th>
                                                                        <th class="expander"></th>
                                                                    </tr>
                                                                </table>
                                                            </th>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>


                            </center>
                        </td>
                    </tr>
                </table>
            </body>

            </html>
                ';

    return $this->msg_;
}

 function _verify_email(string $to_name, $message1=null, $from_name=null, $from=null,$to=null,$message2=null, $link1=null,$link2=null){
    $this->msg_='
                <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
            <html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">

            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                <meta name="viewport" content="width=device-width">
                <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet"> 



            </head>

            <body style="background-color: #f3faff; margin: 0; font-family: Montserrat; font-style:normal; font-weight: lighter;">
                <table class="body" style="width: 80%; margin: 0 auto;">
                    <tr>
                        <td class="center" align="center" valign="top">
                            <center data-parsed="">
                                <table class="container text-center" >
                                    <tbody>
                                        <tr>
                                            <td>
                                                <!-- This container adds the grey gap at the top of the email -->
                                                <table class="row grey">
                                                    <tbody>
                                                        <tr>
                                                            <th class="small-12 large-12 columns first last">
                                                                <table>
                                                                    <tr>
                                                                        <th>
                                                                            &#xA0;
                                                                        </th>
                                                                        <th class="expander"></th>
                                                                    </tr>
                                                                </table>
                                                            </th>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                
                                <table class="container" >
                                    <tbody>
                                        <tr style="background-color:#f3faff;">
                                        <td><center data-parsed="">
                                                                                <a href="https://gaijinmall.com" align="center"
                                                                                    class="text-center">
                                                                                    <img src="https://gaijinmall.com/views/assets/images/logo-sm.png"
                                                                                        class="swu-logo" style="margin-bottom: 15px;">
                                                                                </a>
                                                                            </center></td>
                                        </tr>
                                        <tr style="width: 100%; background-color: #ffffff;">
                                            
                                            <td>
                                                <!-- This container is the main email content -->
                                                
                                                <table class="row">
                                                    <tbody>
                                                        <tr>
                                                            <!-- Logo -->
                                                            <th class="small-12 large-12 columns first last">
                                                                <table>
                                                                    <tr>
                                                                        <th>
                                                                            
                                                                        </th>
                                                                        <th class="expander"></th>
                                                                    </tr>
                                                                </table>
                                                            </th>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <table class="row masthea" style="width: 100%; ">
                                                    <tbody style="width: 100%; ">
                                                        <tr >
                                                            <!-- Masthead -->
                                                            <th class="small-12 large-12 columns first last">
                                                                <table style="width: 100%;">
                                                                    <tr style="margin: 0 auto; " >
                                                                        <th>
                                                                            <center data-parsed="">
                                                                                <img src="https://gaijinmall.com/views/assets/images/welcome_verify.png"
                                                                                    valign="bottom" style="margin-top: 15px; object-fit:contain; width: 50%;" 
                                                                                    class="text-center">
                                                                            </center>
                                                                        </th>
                                                                        <th class="expander"></th>
                                                                    </tr>
                                                                </table>
                                                            </th>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <table class="row">
                                                    <tbody>
                                                        <tr>
                                                            <!--This container adds the gap between masthead and digest content -->
                                                            <th class="small-12 large-12 columns first last">
                                                                <table>
                                                                    <tr>
                                                                        <th>
                                                                            &#xA0;
                                                                        </th>
                                                                        <th class="expander"></th>
                                                                    </tr>
                                                                </table>
                                                            </th>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <table class="row" align="center" style="text-align:center;">
                                                    <tbody>
                                                        <tr>
                                                            <!-- main Email content -->
                                                            <th class="small-12 large-12 columns first last" style="padding:0px 30px 0px 30px;">
                                                                <table>
                                                                    <tr>
                                                                        <th>
                                                                            <b>
                                                                                <h3 style=" font-weight: bold; font-size:150%; margin-bottom: 30px;">Verify your account with the code below</h3>
                                                                            </b>
                                                                            <p style="text-align: center; font-size:300%; ">'.$message1.'</p>
                                                                            <br>
                                                                            <div class="button">
                                                                            <!-- <center style="color:red;font-family:sans-serif;font-size:16px;font-weight:bold;">Click le Button</center> */ -->
                                                                            <center>
                                                                                <a href="https://gaijinmall.com/?verify_token='.$link1.'"
                                                                                    style="background-color:rgb(13, 110, 253); margin-top: 10px; padding:8px;border:0px solid #f7931d;border-radius:3px;color:#ffffff;display:inline-block;font-family:sans-serif;font-size:16px;font-weight:bold;line-height:35px;text-align:center;text-decoration:none;width:150px;-webkit-text-size-adjust:none;mso-hide:all;">Verify Now</a>    
                                                                            </center>
                                                                            </div>
                                                                        </th>
                                                                        <th class="expander"></th>
                                                                    </tr>
                                                                </table>
                                                            </th>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <table class="row">
                                                    <tbody>
                                                        <tr>
                                                            <!-- This container adds whitespace gap at the bottom of main content  -->
                                                            <th class="small-12 large-12 columns first last">
                                                                <table>
                                                                    <tr>
                                                                        <th>
                                                                            &#xA0;
                                                                        </th>
                                                                        <th class="expander"></th>
                                                                    </tr>
                                                                </table>
                                                            </th>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table> <!-- end main email content -->

                                <table class="container text-center" align="center" style="text-align:center; width:100%;">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <!-- footer -->
                                                <table class="row grey" style="width:100%;">
                                                    <tbody style="width:100%;">
                                                        <tr >
                                                            <th class="small-12 large-12 columns first last" >
                                                                <table style="text-align: center; width:100%;">
                                                                    <tr>
                                                                        <th>
                                                                            <p class="text-center footercopy" style="font-size:smaller;">&#xA9; Copyright 2016
                                                                                Gaijinmall. All Rights Reserved.</p>
                                                                        </th>
                                                                        <th class="expander"></th>
                                                                    </tr>
                                                                </table>
                                                            </th>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>


                            </center>
                        </td>
                    </tr>
                </table>
            </body>

            </html>
                ';

    return $this->msg_;
}
function _forget_password($message1=null, $selector,$token){
    $this->msg_='
                <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
            <html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">

            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                <meta name="viewport" content="width=device-width">
                <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet"> 



            </head>

            <body style="background-color: #f3faff; margin: 0; font-family: Montserrat; font-style:normal; font-weight: lighter;">
                <table class="body" style="width: 80%; margin: 0 auto;">
                    <tr>
                        <td class="center" align="center" valign="top">
                            <center data-parsed="">
                                <table class="container text-center" >
                                    <tbody>
                                        <tr>
                                            <td>
                                                <!-- This container adds the grey gap at the top of the email -->
                                                <table class="row grey">
                                                    <tbody>
                                                        <tr>
                                                            <th class="small-12 large-12 columns first last">
                                                                <table>
                                                                    <tr>
                                                                        <th>
                                                                            &#xA0;
                                                                        </th>
                                                                        <th class="expander"></th>
                                                                    </tr>
                                                                </table>
                                                            </th>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                
                                <table class="container" >
                                    <tbody>
                                        <tr style="background-color:#f3faff;">
                                        <td><center data-parsed="">
                                                                                <a href="https://gaijinmall.com" align="center"
                                                                                    class="text-center">
                                                                                    <img src="https://gaijinmall.com/views/assets/images/logo-sm.png"
                                                                                        class="swu-logo" style="margin-bottom: 15px;">
                                                                                </a>
                                                                            </center></td>
                                        </tr>
                                        <tr style="width: 100%; background-color: #ffffff;">
                                            
                                            <td>
                                                <!-- This container is the main email content -->
                                                
                                                <table class="row">
                                                    <tbody>
                                                        <tr>
                                                            <!-- Logo -->
                                                            <th class="small-12 large-12 columns first last">
                                                                <table>
                                                                    <tr>
                                                                        <th>
                                                                            
                                                                        </th>
                                                                        <th class="expander"></th>
                                                                    </tr>
                                                                </table>
                                                            </th>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <table class="row masthea" style="width: 100%; ">
                                                    <tbody style="width: 100%; ">
                                                        <tr >
                                                            <!-- Masthead -->
                                                            <th class="small-12 large-12 columns first last">
                                                                <table style="width: 100%;">
                                                                    <tr style="margin: 0 auto; " >
                                                                        <th>
                                                                            <center data-parsed="">
                                                                                <img src="https://gaijinmall.com/views/assets/images/welcome_verify.png"
                                                                                    valign="bottom" style="margin-top: 15px; object-fit:contain; width: 50%;" 
                                                                                    class="text-center">
                                                                            </center>
                                                                        </th>
                                                                        <th class="expander"></th>
                                                                    </tr>
                                                                </table>
                                                            </th>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <table class="row">
                                                    <tbody>
                                                        <tr>
                                                            <!--This container adds the gap between masthead and digest content -->
                                                            <th class="small-12 large-12 columns first last">
                                                                <table>
                                                                    <tr>
                                                                        <th>
                                                                            &#xA0;
                                                                        </th>
                                                                        <th class="expander"></th>
                                                                    </tr>
                                                                </table>
                                                            </th>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <table class="row" align="center" style="text-align:center;">
                                                    <tbody>
                                                        <tr>
                                                            <!-- main Email content -->
                                                            <th class="small-12 large-12 columns first last" style="padding:0px 30px 0px 30px;">
                                                                <table>
                                                                    <tr>
                                                                        <th>
                                                                            <b>
                                                                                <h3 style=" font-weight: bold; font-size:150%; margin-bottom: 30px;">Your password reset link is ready</h3>
                                                                            </b>
                                                                            <p style="text-align: center; font-size:300%; ">'.$message1.'</p>
                                                                            <br>
                                                                            <div class="button">
                                                                            <!-- <center style="color:red;font-family:sans-serif;font-size:16px;font-weight:bold;">Click le Button</center> */ -->
                                                                            <center>
                                                                                <a href="https://gaijinmall.com/verify_reset.php?sel='.$selector.'&mid='.md5(mt_rand(1,100000000)).'&other='.$token.'"
                                                                                    style="background-color:rgb(13, 110, 253); margin-top: 10px; padding:8px;border:0px solid #f7931d;border-radius:3px;color:#ffffff;display:inline-block;font-family:sans-serif;font-size:16px;font-weight:bold;line-height:35px;text-align:center;text-decoration:none;width:200px;-webkit-text-size-adjust:none;mso-hide:all;">New Password</a>    
                                                                            </center>
                                                                            </div>
                                                                        </th>
                                                                        <th class="expander"></th>
                                                                    </tr>
                                                                </table>
                                                            </th>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <table class="row">
                                                    <tbody>
                                                        <tr>
                                                            <!-- This container adds whitespace gap at the bottom of main content  -->
                                                            <th class="small-12 large-12 columns first last">
                                                                <table>
                                                                    <tr>
                                                                        <th>
                                                                            &#xA0;
                                                                        </th>
                                                                        <th class="expander"></th>
                                                                    </tr>
                                                                </table>
                                                            </th>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table> <!-- end main email content -->

                                <table class="container text-center" align="center" style="text-align:center; width:100%;">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <!-- footer -->
                                                <table class="row grey" style="width:100%;">
                                                    <tbody style="width:100%;">
                                                        <tr >
                                                            <th class="small-12 large-12 columns first last" >
                                                                <table style="text-align: center; width:100%;">
                                                                    <tr>
                                                                        <th>
                                                                            <p class="text-center footercopy" style="font-size:smaller;">&#xA9; Copyright 2016
                                                                                Gaijinmall. All Rights Reserved.</p>
                                                                        </th>
                                                                        <th class="expander"></th>
                                                                    </tr>
                                                                </table>
                                                            </th>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>


                            </center>
                        </td>
                    </tr>
                </table>
            </body>

            </html>
                ';

    return $this->msg_;
}
}

/* $testData=new MessageData();
echo $testData->_welcome_msg("John"); */
?>
