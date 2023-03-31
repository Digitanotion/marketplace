var actvs = document.getElementById("actvs");
var decls = document.getElementById("decls");
var revws = document.getElementById("revws");
var advts1 = document.getElementById("adverts1");
var advts2 = document.getElementById("adverts2");
var advts3 = document.getElementById("adverts3");
var feeds0 = document.getElementById("feedsicon0");
var feeds1 = document.getElementById("feedsicon1");
var lnked = document.getElementById("copd0");
var key01 = document.getElementById("key01");
var limitation = document.getElementById("limitation");
var premiums = document.getElementById("premiums");
var sales01 = document.getElementById("sales01");
var header01 = document.getElementById("header01");
var buy01 = document.getElementById("buy01");
var premium01 = document.getElementById("premium01");
//Activate tooltips
const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
//Activate service worker
window.addEventListener("load", () => {
  if ("serviceWorker" in navigator) {
    navigator.serviceWorker.register("https://gaijinmall.com/service-worker.js");
  }
});
$("#promoteAd_btn").attr("disabled", true);
$(".make_offer_btn").on("click", function () {
  if ($(this)[0].hasAttribute("data-bs-toggle")) {
  } else {
    alert("So sorry, kindly login to make an offer. Thanks.");
  }
});
$(".sendDM").on("click", function () {
  if ($(this)[0].hasAttribute("data-bs-toggle")) {
  } else {
    alert("So sorry, kindly login to send message to the seller. Thanks.");
  }
});
$(".reportAd__btn").on("click", function () {
  if ($(this)[0].hasAttribute("data-bs-toggle")) {
  } else {
    alert("So sorry, kindly login to report this Ad. Thanks.");
  }
});
$("[name|='mallAdPromoID']").on("click", function () {
  $("#promoteAd_btn").removeAttr("disabled", true);
  promoAdSelected = $(this).attr("promocost");
  promoAdOldSelected = $(this).attr("promooldcost");
  promoTypeSelected = $(this).attr("promotype");
  $("." + promoTypeSelected).html(
    new Intl.NumberFormat().format(promoAdSelected)
  );
  $(".old_" + promoTypeSelected).html(
    new Intl.NumberFormat().format(promoAdOldSelected)
  );
});
$(".reply-comment").on("click", function () {
  userCommentID = $(this).attr("datacomment");
  userName = $(this).attr("user-firstname");
  $("#user-comment-id").val(userCommentID);
  $("#user-comment-msg").attr("placeholder", "Reply @" + userName);
  $("#user-comment-msg").focus();
  //$("."+promoTypeSelected).html(new Intl.NumberFormat().format(promoAdSelected)
});
$(".click-to-copy").on("click", function () {
  var copyText = this.previousElementSibling;
  /* Select the text field */
  copyText.select();
  copyText.setSelectionRange(0, 99999); /* For mobile devices */
  /* Copy the text inside the text field */
  navigator.clipboard.writeText(copyText.value);
  $(this).html("Copied <i class='fa fa-check'></i>");
  setTimeout(() => {
    $(this).html("Copy Link");
  }, 3000);
});

$("#promoteAd_btn").on("click", function () {
  $(this).html('<i class="fa fa-circle-o-notch fa-spin"></i> Proceed to Pay');
  $(this).attr("disable", true);
});

//MAKE AD LIST ITEM LIST CLICKED\
$(".ha-item-each__cardimg").on("click", function () {
  adID = $(this).attr("datavalue");
  adTitle = $(this).attr("datavalueTitle");
  window.location = "product.php?" + adTitle + "&adID=" + adID;
});

 var adMenuClickedVal=false;
function adMenuClicked(){
  adMenuClickedVal=true;
}
function gotoProduct(adID,adTitle){
  // var clicked=false;
  // $('.dropdown-toggle').on("click", function(e) {
  //   clicked=true
  //   alert("Clicked")
  // })
  if (!adMenuClickedVal){
    window.location = "product.php?" + adTitle + "&adID=" + adID;
  }
  adMenuClickedVal=false;
  
}
function shopToProduct(adID,adTitle){
  window.location = "../product.php?" + adTitle + "&adID=" + adID;
}


function sendAdIDToAdPromoPage(promo_ad_id) {
  promoteAd_AdCustID = $("#promoteAd_AdCustID").val();
  $("#promoteAd_AdCustID").val(promoteAd_AdCustID + "_" + promo_ad_id);
  return false;
}
function sendAdIDToAdPromoPageDel(promo_ad_id) {
  $("#adIDForDelete").val(promo_ad_id);
}
function myRev1() {
  //advts1.src = "./assets/images/bg-02.png";
  /*  advts2.innerHTML = "Gaijin checks each advert to make sure everything is okay";
    advts3.innerHTML = "Your new ads will be displayed here while we check them." ; */
  revws.style.backgroundColor = "lightblue";
  decls.style.backgroundColor = "";
  actvs.style.backgroundColor = "white";
  feeds0.style.backgroundColor = "";
  // SEND REQUEST
  var filterFormData = new FormData();
  var pageLoadWrapper = $(".ha-profile-url-data__body");
  categID = $("#ha-categID").val();
  userID = $("#ha-userID").val();
  $.ajax({
    url: "../handlers/allUserOffice.php",
    type: "GET",
    data:
      "p_adcategory=" +
      categID +
      "&p_viewbyuser=" +
      userID +
      "&p_source=viewUsrReviewAds",
    cache: false,
    contentType: false,
    processData: false,
    beforeSend: function () {
      pageLoadWrapper.html("<div class='sys-loading'> </div>");
    },
    success: function (data) {
      pageLoadWrapper.html(data);
    },
  });
}
function myDecl() {
  /* //advts1.src = "./assets/images/bg-04.png";
    advts2.innerHTML = "we will inform you if there is any issue with your advert.";
    advts3.innerHTML = "Declined adverts will be displayed here for you to fix them" ; */
  decls.style.backgroundColor = "lightblue";
  actvs.style.backgroundColor = "white";
  revws.style.backgroundColor = "";
  feeds0.style.backgroundColor = "";
  // SEND REQUEST
  var filterFormData = new FormData();
  var pageLoadWrapper = $(".ha-profile-url-data__body");
  categID = $("#ha-categID").val();
  userID = $("#ha-userID").val();
  $.ajax({
    url: "../handlers/allUserOffice.php",
    type: "GET",
    data:
      "p_adcategory=" +
      categID +
      "&p_viewbyuser=" +
      userID +
      "&p_source=viewUsrDeclinedAds",
    cache: false,
    contentType: false,
    processData: false,
    beforeSend: function () {
      pageLoadWrapper.html("<div class='sys-loading'> </div>");
    },
    success: function (data) {
      pageLoadWrapper.html(data);
    },
  });
}
function myActv() {
  //advts1.src = "./assets/images/notfound3.svg";
  /* advts2.innerHTML = "No active Ad found";
    advts3.innerHTML = "No content availiable" ; */
  actvs.style.backgroundColor = "lightblue";
  revws.style.backgroundColor = "";
  decls.style.backgroundColor = "";
  feeds0.style.backgroundColor = "";
  // SEND REQUEST
  var filterFormData = new FormData();
  var pageLoadWrapper = $(".ha-profile-url-data__body");
  categID = $("#ha-categID").val();
  userID = $("#ha-userID").val();
  $.ajax({
    url: "../handlers/allUserOffice.php",
    type: "GET",
    data:
      "p_adcategory=" +
      categID +
      "&p_viewbyuser=" +
      userID +
      "&p_source=viewUsrActiveAds",
    cache: false,
    contentType: false,
    processData: false,
    beforeSend: function () {
      pageLoadWrapper.html("<div class='sys-loading'> </div>");
    },
    success: function (data) {
      pageLoadWrapper.html(data);
    },
  });
}
function myLnked() {
  var inputc = document.body.appendChild(document.createElement("input"));
  inputc.value = window.location.href;
  inputc.select();
  document.execCommand("copy");
  inputc.parentNode.removeChild(inputc);
  lnked.style.border = "1px solid blue";
  lnked.style.backgroundColor = "whitesmoke";
  lnked.style.color = "blue";
  lnked.innerHTML = "Link was copied";
}
function feeds() {
  var x = document.getElementById("prof1");
  //document.getElementById("fedes").href = "Feedback.html";
  x.innerHTML = "Feedback";
  advts1.src = "./assets/images/bg-01.png";
  advts2.innerHTML = "There is no feedback yet";
  advts3.innerHTML = "Copy the link and send them";
  lnked.style.display = "inline-block";
  feeds0.style.backgroundColor = "lightblue";
  feeds1.style.backgroundColor = "white";
}
function myExpiredvd() {
  //advts1.src = "./assets/images/bg-19.png";
  //lnked.style.display = "none";
  feeds0.style.backgroundColor = "lightblue";
  decls.style.backgroundColor = "";
  actvs.style.backgroundColor = "";
  revws.style.backgroundColor = "";
  // SEND REQUEST
  var filterFormData = new FormData();
  var pageLoadWrapper = $(".ha-profile-url-data__body");
  categID = $("#ha-categID").val();
  userID = $("#ha-userID").val();
  $.ajax({
    url: "../handlers/allUserOffice.php",
    type: "GET",
    data:
      "p_adcategory=" +
      categID +
      "&p_viewbyuser=" +
      userID +
      "&p_source=viewUsrExpireddAds",
    cache: false,
    contentType: false,
    processData: false,
    beforeSend: function () {
      pageLoadWrapper.html("<div class='sys-loading'> </div>");
    },
    success: function (data) {
      pageLoadWrapper.html(data);
    },
  });
}
function myRecvd() {
  /* advts1.src = "./assets/images/bg-01.png";
    advts3.innerHTML = "Copy the link and send them";
    lnked.style.display = "inline-block"; */
  feeds0.style.backgroundColor = "lightblue";
  feeds1.style.backgroundColor = "white";
}
function mySents() {
  advts1.src = "./assets/images/bg-19.png";
  advts3.innerHTML = "";
  lnked.style.display = "none";
  feeds1.style.backgroundColor = "lightblue";
  feeds0.style.backgroundColor = "white";
}
function myAdvts0() {
  var x = document.getElementById("prof1");
  x.innerHTML = "Profile";
  advts1.src = "./assets/images/notfound3.svg";
  advts2.innerHTML = "No active Ad found";
  advts3.innerHTML = "No content availiable";
  actvs.style.backgroundColor = "lightblue";
  decls.style.backgroundColor = "";
  revws.style.backgroundColor = "";
}
function switch01() {
  var header01 = document.getElementById("header01");
  if (header01.innerHTML == "Enable chats") {
    header01.innerHTML = "Disable chats";
  } else {
    header01.innerHTML = "Enable chats";
  }
}

function switchFeedback(){
  var header01 = document.getElementById("header01");
  if (header01.innerHTML == "Enable feedback") {
    header01.innerHTML = "Disable feedback";
  } else {
    header01.innerHTML = "Enable feedback";
  }
}
function change02() {
  key01.style.display = "none";
}
function limit01() {
  header01.innerText = "What does ad limit mean?";
  limitation.style.display = "block";
  premiums.style.display = "none";
  sales01.style.display = "none";
  buy01.style.display = "none";
}
function premium() {
  header01.innerText = "What are Promoted Ads?";
  premiums.style.display = "block";
  limitation.style.display = "none";
  sales01.style.display = "none";
  buy01.style.display = "none";
  premium01.style.color = "green";
}
function sell01() {
  header01.innerText = "How can I sell on Gaijinmall?";
  sales01.style.display = "block";
  limitation.style.display = "none";
  premiums.style.display = "none";
  buy01.style.display = "none";
}
function buy() {
  header01.innerText = "How to Buy on Gaijinmall?";
  buy01.style.display = "block";
  limitation.style.display = "none";
  premiums.style.display = "none";
  sales01.style.display = "none";
}
function secondSection() {
  var x = document.getElementById("section1");
  var y = document.getElementById("section2");
  x.style.display = "none";
  y.style.display = "inline-block";
}

$("#location_state").change(function () {
  var location_state = $(this).val();
  $.ajax({
    url: "../handlers/adHandler.php",
    type: "post",
    data: "location_state=" + location_state,
    beforeSend: function () {
      $("select[name = 'location_city']").append(
        '<option id="option">Loading Cities..</option>'
      );
    },
    success: function (data) {
      if (typeof data !== "string") {
      }
      try {
        $("select[name = 'location_city']").append(data);
      } catch (e) {
        $("select[name = 'location_city']").append(
          `<option value = "" disabled>${data}</option>`
        );
      }
    },
  });
  $("select[name = 'location_city']")
    .empty()
    .append('<option id="select_city">Select City</option>');
});

$("#resend_verify_token").on("click", function () {
  var phoneNumber = $("#edit_phone__txt").val();
  $(this).hide();
  setTimeout(() => {
    $("#resend_verify_token").show();
  }, 60000);
  $.ajax({
    url: "../handlers/resendToken.php",
    type: "GET",
    data: "userPhone=" + phoneNumber,
    beforeSend: function () {
      $("#resend_verify_token").html("<div class='sys-loading'> </div>");
    },
    success: function (data) {
      console.log(data);
      $("#resend_verify_token").html("Resend");
      if (data == 1) {
        cuteAlert({
          type: "success",
          title: "Code Resent",
          message: "Please check your phone for a new code",
          buttonText: "Alright",
        });
      } else if (data == 500) {
        cuteAlert({
          type: "error",
          title: "Failed to resend",
          message: "We could not resend the new code",
          buttonText: "Alright",
        });
      } else {
        cuteAlert({
          type: "error",
          title: "Failed to resend",
          message: "Something went wrong",
          buttonText: "Alright",
        });
      }
    },
  });
});
$("#verify_phone_token").on("submit", function (e) {
  e.preventDefault();
  //I had an issue that the forms were submitted in geometrical progression after the next submit.
  // This solved the problem.
  e.stopImmediatePropagation();
  // show that something is loading
  //$('#edit_phone__btn').html("<img src='assets/images/gaijinLoading.svg' width='20'>");

  // Call ajax for pass data to other place
  $.ajax({
    type: "POST",
    url: "../handlers/verifyToken.php",
    data: $(this).serialize(), // getting filed value in serialize form
  })
    .done(function (data) {
      // if getting done then call.
      console.log(data);
      // show the response
      $("#edit_phone__btn").html("Verify");
      if (data == 1) {
        cuteAlert({
          type: "success",
          title: "Code Verified",
          message: "Your phone is now verified",
          buttonText: "Thank you",
        });
        setTimeout(() => {
          window.location = "./user_phone_update";
        }, 3000);
      } else if (data == 500) {
        cuteAlert({
          type: "error",
          title: "Failed to verify",
          message: "We could not verify this code",
          buttonText: "Alright",
        });
      } else {
        cuteAlert({
          type: "error",
          title: "Failed to verify",
          message: "We could not verify this code",
          buttonText: "Alright",
        });
      }
    })
    .fail(function () {
      // if fail then getting message

      // just in case posting your form failed
      //alert( "Posting failed." );
      cuteAlert({
        type: "error",
        title: "No Internet",
        message: "Please check your internet connection",
        buttonText: "Alright",
      });
    });

  // to prevent refreshing the whole page page
  $("#tokenCode").val("");
  return false;
});

$(".disableChats__Btn").on("click", function (e) {
  var checkedStatus = 0;
  var getUsrID = $(this).attr("data-userID");
  if ($(this).is(":checked")) {
    checkedStatus = 1;
  } else {
    checkedStatus = 0;
  }

  // Call ajax for pass data to other place
  $(".loading-containter").html(
    "<img src='assets/images/gaijinLoading.svg' width='25'>"
  );
  $.ajax({
    type: "GET",
    url: "../handlers/allUserOffice.php",
    data: "disableChat&by=" + getUsrID + "&status=" + checkedStatus, // getting filed value in serialize form
  })
    .done(function (data) {
      // if getting done then call.
      console.log(data);
      // show the response
      //$('#edit_phone__btn').html("Verify")
      if (data == 1) {
        if (checkedStatus == 1) {
          cuteAlert({
            type: "success",
            title: "Chat Enabled",
            message: "You will receive chats from other users anymore.",
            buttonText: "Thank you",
          });
        } else if (checkedStatus == 0) {
          cuteAlert({
            type: "success",
            title: "Chat Disabled",
            message: "You will not receive chats from other users anymore.",
            buttonText: "Thank you",
          });
        }
      } else if (data == 500) {
        cuteAlert({
          type: "error",
          title: "Failed to disable",
          message: "We could not disable chats at the moment",
          buttonText: "Alright",
        });
      } else {
        cuteAlert({
          type: "error",
          title: "Failed to disable",
          message: "We could not disable chats at the moment",
          buttonText: "Alright",
        });
      }
      $(".loading-containter").html("");
    })
    .fail(function () {
      // if fail then getting message

      // just in case posting your form failed
      //alert( "Posting failed." );
      cuteAlert({
        type: "error",
        title: "No Internet",
        message: "Please check your internet connection",
        buttonText: "Alright",
      });
      $(".loading-containter").html("");
    });
});

$(".disableFeed__Btn").on("click", function (e) {
  var checkedStatus = 0;
  var getUsrID = $(this).attr("data-userID");
  if ($(this).is(":checked")) {
    checkedStatus = 1;
  } else {
    checkedStatus = 0;
  }

  // Call ajax for pass data to other place
  $(".loading-containter").html(
    "<img src='assets/images/gaijinLoading.svg' width='25'>"
  );
  $.ajax({
    type: "GET",
    url: "../handlers/allUserOffice.php",
    data: "disableFeed&by=" + getUsrID + "&status=" + checkedStatus, // getting filed value in serialize form
  })
    .done(function (data) {
      // if getting done then call.
      console.log(data);
      // show the response
      //$('#edit_phone__btn').html("Verify")
      if (data == 1) {
        if (checkedStatus == 1) {
          cuteAlert({
            type: "success",
            title: "Feedback Enabled",
            message: "You will receive chats from other users anymore.",
            buttonText: "Thank you",
          });
        } else if (checkedStatus == 0) {
          cuteAlert({
            type: "success",
            title: "Feedback Disabled",
            message: "You will not receive chats from other users anymore.",
            buttonText: "Thank you",
          });
        }
      } else if (data == 500) {
        cuteAlert({
          type: "error",
          title: "Failed to disable",
          message: "We could not disable feedbacks at the moment",
          buttonText: "Alright",
        });
      } else {
        cuteAlert({
          type: "error",
          title: "Failed to disable",
          message: "We could not disable feedbacks at the moment",
          buttonText: "Alright",
        });
      }
      $(".loading-containter").html("");
    })
    .fail(function () {
      // if fail then getting message

      // just in case posting your form failed
      //alert( "Posting failed." );
      cuteAlert({
        type: "error",
        title: "No Internet",
        message: "Please check your internet connection",
        buttonText: "Alright",
      });
      $(".loading-containter").html("");
    });
});

function followVendor(loggedUser,VendorUser){
  followStatus=$("#followVendor__btn").attr('data-follow-status');
      if (loggedUser){
        $.ajax({
          type: "GET",
          url: "../../handlers/allUserOffice.php",
          dataType: 'JSON',
          data: "followvendor="+followStatus+"&user=" + loggedUser + "&vendor="+ VendorUser, // getting filed value in serialize form
        })
          .done(function (data) {
            // if getting done then call.
            console.log(data);
            console.log(followStatus)
            // show the response
            //$('#edit_phone__btn').html("Verify")
            if (data.status == 1) {
              if (followStatus == "unfollowed") {
                //Update follow button
                $("#followVendor__btn").html("Unfollow <i class='fa fa-thumbs-down m-0'></i>");
                $("#followVendor__btn").attr('data-follow-status', 'followed')
                $('#allVendorFollowers').html(data.message);
               }else if(followStatus=="followed"){
                 $("#followVendor__btn").html("Follow <i class='fa fa-thumbs-up m-0'></i>");
                 $("#followVendor__btn").attr('data-follow-status', 'unfollowed')
                 $('#allVendorFollowers').html(data.message);
               }
            }else if(data.status == 501){
              cuteAlert({
                type: "error",
                title: "Operation Failed",
                message: "Please sign in to follow this user",
                buttonText: "Ok",
              });
            }
                else {
                cuteAlert({
                  type: "error",
                  title: "Operation Failed",
                  message: "Sorry, something went wrong",
                  buttonText: "Ok",
                });
              }
              
          })
          .fail(function (data) {
            console.log(data);
            // if fail then getting message
      
            // just in case posting your form failed
            //alert( "Posting failed." );
            var countFollersAll=parseInt($('#allVendorFollowers').text());
            console.log(countFollersAll);
            if (followStatus == "unfollowed") {
              //Update follow button
              $("#followVendor__btn").html("Unfollow <i class='fa fa-thumbs-down m-0'></i>");
              $("#followVendor__btn").attr('data-follow-status', 'followed')
              $('#allVendorFollowers').html(countFollersAll+1);
             }else if(followStatus=="followed"){
               $("#followVendor__btn").html("Follow <i class='fa fa-thumbs-up m-0'></i>");
               $("#followVendor__btn").attr('data-follow-status', 'unfollowed')
               $('#allVendorFollowers').html(countFollersAll-1);
             }
          });
      }  
}
$('#delAccount__btn').on("click", function(e){
  cuteAlert({
    type: "question",
    title: "Delete Account?",
    message: "Are you sure you want to delete your account forever?",
    confirmText: "Yes",
  cancelText: "No"
  }).then((e)=>{
    if ( e == ("confirm")){
      var delReason=$('.delAccReason').val();
      var getUsrID = $(this).attr("data-userID");
      var delOtherReason=$('.delAccOtherReason').val();
      $(this).attr("disabled",true)
      $(this).val("Please wait...");
      $.ajax({
        type: "GET",
        url: "../handlers/allUserOffice.php",
        data: "deleteAccount&by=" + getUsrID + "&delOtherReason="+ delOtherReason+ "&delReason=" + delReason, // getting filed value in serialize form
      })
        .done(function (data) {
          // if getting done then call.
          console.log(data);
          // show the response
          //$('#edit_phone__btn').html("Verify")
          if (data == 1) {
            
              cuteAlert({
                type: "success",
                title: "Account Deleted",
                message: "Your account is deleted successful.",
                buttonText: "Thank you",
              }).then((e)=>{
                console.log(e);
                if ( e == ("ok")){
                  window.location = "?logout=1";
                }
              }); 

              
            }
              else {
              cuteAlert({
                type: "error",
                title: "Failed to delete",
                message: "Sorry, we could not delete your account at moment, try again later.",
                buttonText: "Ok",
              });
            }
            
          $('#delAccount__btn').val("Delete Forever");
          $('#delAccount__btn').removeAttr("disabled");
        })
        .fail(function () {
          // if fail then getting message
    
          // just in case posting your form failed
          //alert( "Posting failed." );
          cuteAlert({
            type: "error",
            title: "No Internet",
            message: "Please check your internet connection",
            buttonText: "Alright",
          });
          $('#delAccount__btn').val("Delete Forever");
          $('#delAccount__btn').removeAttr("disabled");
        });
     
  }
})
 
})

$('.delAccReason').on('change', function(e){
  var selected=$(this).val();
  console.log(selected)
  if (selected=="other"){
    $('.otherReasonContainer').html('<label for="delAccOtherReason" class="">Tell us the reason</label><textarea class="delAccOtherReason d-non w-100 form-control" id="delAccOtherReason" name="delAccOtherReason" placeholder="Tell us the reason" rows="5"></textarea>')
  }
  
})

//Change add icon to file icon when user selects file in user_phone_update page
function phoneUpdateSelectFile(){
  $("#usrIDFile__file").click();
}
$('#usrIDFile__file').on("change", function (e) {
  $("#addNewfileIcon__phoneupdate").removeClass("fa-plus");
  $("#addNewfileIcon__phoneupdate").addClass("fa-file");
})

//Change text to date type in phone update page
// function phoneUpdateChangeToDate(){
//   var x = document.getElementById("phoneUpdateChangeToDate__txt").type = "date"
//   $("#phoneUpdateChangeToDate__txt").click();
// }

// SET COOKIE AND GET COOKIE FOR FORMS
function setCookie(c_name, value, exdays) {
  var exdate = new Date();
  exdate.setDate(exdate.getDate() + exdays);
  var c_value = escape(value) + ((exdays == null) ? "" : "; expires=" + exdate.toUTCString());
  document.cookie = c_name + "=" + c_value;
}

function getCookie(c_name) {
  var c_value = document.cookie;
  var c_start = c_value.indexOf(" " + c_name + "=");
  if (c_start == -1) {
      c_start = c_value.indexOf(c_name + "=");
  }
  if (c_start == -1) {
      c_value = null;
  } else {
      c_start = c_value.indexOf("=", c_start) + 1;
      var c_end = c_value.indexOf(";", c_start);
      if (c_end == -1) {
          c_end = c_value.length;
      }
      c_value = unescape(c_value.substring(c_start, c_end));
  }
  return c_value;
}

function saveValue(input) {
  var name = input.attr('name');
  var value = input.val();
  setCookie(name,value);
}

function getValue(input) {
  var name = input.attr('name');
  var value = getCookie(name);
  if(value != null && value != "" ) {
      return value;
  }
}

function saveFormValue(){
  $('input[type="text"], input[type="email"],input[type="number"]').each(function(){
    var value = getValue($(this));
    $(this).val(value);
}).on('blur', function(){
    if($(this).val() != '' ) {
        saveValue($(this));
    }
});

}

