var actvs = document.getElementById("actvs")
var decls = document.getElementById("decls")
var revws = document.getElementById("revws")
var advts1 = document.getElementById("adverts1")
var advts2 = document.getElementById("adverts2")
var advts3 = document.getElementById("adverts3")
var feeds0 = document.getElementById("feedsicon0")
var feeds1 = document.getElementById("feedsicon1")
var lnked = document.getElementById("copd0")
var key01 = document.getElementById("key01");
var limitation = document.getElementById("limitation");
var premiums = document.getElementById("premiums");
var sales01 = document.getElementById("sales01");
var header01 = document.getElementById("header01");
var buy01 = document.getElementById("buy01");
var premium01 = document.getElementById("premium01");
$('#promoteAd_btn').attr('disabled',true);
$("[name|='mallAdPromoID']").on('click', function () {
  $('#promoteAd_btn').removeAttr('disabled',true);
  promoAdSelected=$(this).attr('promocost');
  promoAdOldSelected=$(this).attr('promooldcost');
  promoTypeSelected=$(this).attr('promotype');
  $("."+promoTypeSelected).html(new Intl.NumberFormat().format(promoAdSelected))
  $(".old_"+promoTypeSelected).html(new Intl.NumberFormat().format(promoAdOldSelected))
})
$('#promoteAd_btn').on('click', function() {
  $(this).html('<i class="fa fa-circle-o-notch fa-spin"></i> Proceed to Pay');
  $(this).attr('disable', true)
})



myActv()
function sendAdIDToAdPromoPage(promo_ad_id) {
  promoteAd_AdCustID=$('#promoteAd_AdCustID').val();
  $('#promoteAd_AdCustID').val(promoteAd_AdCustID+"_"+promo_ad_id)
}
function sendAdIDToAdPromoPageDel(promo_ad_id) {
  $('#adIDForDelete').val(promo_ad_id)
}
function myRev1(){
    //advts1.src = "./assets/images/bg-02.png";
   /*  advts2.innerHTML = "Gaijin checks each advert to make sure everything is okay";
    advts3.innerHTML = "Your new ads will be displayed here while we check them." ; */
    revws.style.backgroundColor = "lightblue";
    decls.style.backgroundColor = "";
    actvs.style.backgroundColor = "white";
    feeds0.style.backgroundColor = "";
    // SEND REQUEST
    var filterFormData=new FormData();
    var pageLoadWrapper=$(".ha-profile-url-data__body");
    categID=$("#ha-categID").val();
    userID=$("#ha-userID").val();
    $.ajax({
      url: '../handlers/allUserOffice.php',
      type: 'GET',
      data: "p_adcategory="+categID+"&p_viewbyuser="+userID+"&p_source=viewUsrReviewAds",
      cache: false,
          contentType: false,
          processData: false,
      beforeSend:function(){
        pageLoadWrapper.html("<div class='sys-loading'> </div>")
            },
      success: function (data) {
        
        pageLoadWrapper.html(data)
      }
    });
}
function myDecl(){
    /* //advts1.src = "./assets/images/bg-04.png";
    advts2.innerHTML = "we will inform you if there is any issue with your advert.";
    advts3.innerHTML = "Declined adverts will be displayed here for you to fix them" ; */
    decls.style.backgroundColor = "lightblue";
    actvs.style.backgroundColor = "white";
    revws.style.backgroundColor = "";
    feeds0.style.backgroundColor = "";
    // SEND REQUEST
    var filterFormData=new FormData();
    var pageLoadWrapper=$(".ha-profile-url-data__body");
    categID=$("#ha-categID").val();
    userID=$("#ha-userID").val();
    $.ajax({
      url: '../handlers/allUserOffice.php',
      type: 'GET',
      data: "p_adcategory="+categID+"&p_viewbyuser="+userID+"&p_source=viewUsrDeclinedAds",
      cache: false,
          contentType: false,
          processData: false,
      beforeSend:function(){
        pageLoadWrapper.html("<div class='sys-loading'> </div>")
            },
      success: function (data) {
        
        pageLoadWrapper.html(data)
      }
    });
}
function myActv(){
    //advts1.src = "./assets/images/notfound3.svg";
    /* advts2.innerHTML = "No active Ad found";
    advts3.innerHTML = "No content availiable" ; */
    actvs.style.backgroundColor = "lightblue";
    revws.style.backgroundColor = "";
    decls.style.backgroundColor = ""; 
    feeds0.style.backgroundColor = "";
    // SEND REQUEST
    var filterFormData=new FormData();
    var pageLoadWrapper=$(".ha-profile-url-data__body");
    categID=$("#ha-categID").val();
    userID=$("#ha-userID").val();
    $.ajax({
      url: '../handlers/allUserOffice.php',
      type: 'GET',
      data: "p_adcategory="+categID+"&p_viewbyuser="+userID+"&p_source=viewUsrActiveAds",
      cache: false,
          contentType: false,
          processData: false,
      beforeSend:function(){
        pageLoadWrapper.html("<div class='sys-loading'> </div>")
            },
      success: function (data) {
        
        pageLoadWrapper.html(data)
      }
    });
}
function myLnked(){
    var inputc = document.body.appendChild(document.createElement("input"));
    inputc.value = window.location.href;
    inputc.select();
    document.execCommand('copy');
    inputc.parentNode.removeChild(inputc);
    lnked.style.border = "1px solid blue";
    lnked.style.backgroundColor = "whitesmoke";
    lnked.style.color = "blue";
    lnked.innerHTML = "Link was copied";
     
}
function feeds(){
    var x = document.getElementById("prof1")
    //document.getElementById("fedes").href = "Feedback.html";
    x.innerHTML = "Feedback";
    advts1.src = "./assets/images/bg-01.png";
    advts2.innerHTML = "There is no feedback yet";
    advts3.innerHTML = "Copy the link and send them";
    lnked.style.display = "inline-block";
    feeds0.style.backgroundColor = "lightblue";
    feeds1.style.backgroundColor ="white"
    } 
    function myExpiredvd(){
        //advts1.src = "./assets/images/bg-19.png";
        //lnked.style.display = "none";
        feeds0.style.backgroundColor = "lightblue";
        decls.style.backgroundColor = "";
    actvs.style.backgroundColor = "";
    revws.style.backgroundColor = "";
    // SEND REQUEST
    var filterFormData=new FormData();
    var pageLoadWrapper=$(".ha-profile-url-data__body");
    categID=$("#ha-categID").val();
    userID=$("#ha-userID").val();
    $.ajax({
      url: '../handlers/allUserOffice.php',
      type: 'GET',
      data: "p_adcategory="+categID+"&p_viewbyuser="+userID+"&p_source=viewUsrExpireddAds",
      cache: false,
          contentType: false,
          processData: false,
      beforeSend:function(){
        pageLoadWrapper.html("<div class='sys-loading'> </div>")
            },
      success: function (data) {
        
        pageLoadWrapper.html(data)
      }
    });
    }
function myRecvd(){
    /* advts1.src = "./assets/images/bg-01.png";
    advts3.innerHTML = "Copy the link and send them";
    lnked.style.display = "inline-block"; */
    feeds0.style.backgroundColor = "lightblue";
    feeds1.style.backgroundColor = "white";
}
function mySents(){
    advts1.src = "./assets/images/bg-19.png";
    advts3.innerHTML = "";
    lnked.style.display = "none";
    feeds1.style.backgroundColor = "lightblue";
    feeds0.style.backgroundColor = "white";
}
function myAdvts0(){
    var x = document.getElementById("prof1")
    x.innerHTML = "Profile";
    advts1.src = "./assets/images/notfound3.svg";
    advts2.innerHTML = "No active Ad found";
    advts3.innerHTML = "No content availiable" ;
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
function change02(){
  key01.style.display = "none";
}
function limit01(){
  header01.innerText = "What does ad limit mean?";
  limitation.style.display = "block";
  premiums.style.display = "none";
  sales01.style.display = "none";
  buy01.style.display = "none";
}
function premium(){
  header01.innerText = "What are Promoted Ads?"
  premiums.style.display = "block";
  limitation.style.display = "none";
  sales01.style.display = "none";
  buy01.style.display = "none";
  premium01.style.color = "green";
}
function sell01(){
  header01.innerText = "How can I sell on Gaijinmall?"
  sales01.style.display = "block";
  limitation.style.display = "none";
  premiums.style.display = "none";
  buy01.style.display = "none";
}
function buy(){
  header01.innerText = "How to Buy on Gaijinmall?";
  buy01.style.display = "block";
  limitation.style.display = "none";
  premiums.style.display = "none";
  sales01.style.display = "none";
}
function secondSection(){
  var x = document.getElementById("section1")
  var y = document.getElementById("section2")
  x.style.display = "none"
  y.style.display = "inline-block"
}