var demo1=document.getElementById('demo1');

function showPhoneNo(phoneNo) {
    demo1.textContent = phoneNo;
}

// Update message chat box
//var successCounts=parseInt($('#lastCount').val());
var currentData="";
setInterval(() => {
    //Capture the necessary variables msgSenderUser,msgID,msgRecieverUser,msgContent
    var msgID=$('#msgID').val();
    var countSuccess=parseInt($('#lastCount').val());
    var pageLoadWrapperMsgs=$('.mainchat');
    console.log(countSuccess)
    //do ajax request
    $.ajax({
        url: '../handlers/allUserOffice.php',
        type: 'GET',
        data: "msgID="+msgID+"&pointer="+countSuccess,
        cache: false,
            contentType: false,
            processData: false,
        beforeSend:function(){
          //pageLoadWrapper.html("<div class='sys-loading'> </div>")
              },
        success: function (data) {
            data=data.trim();
            if (data=="error"){
                pageLoadWrapperMsgs.append(data);
            }else{
                if (data!=currentData){
                    pageLoadWrapperMsgs.append(data);
                //pageLoadWrapperMsgs = pageLoadWrapperMsgs.scrollHeight;
                pageLoadWrapperMsgs[0].scrollTop = pageLoadWrapperMsgs[0].scrollHeight;
                }
                currentData=data;
                
            }
            
        }
      });
}, 1000);

function scrollToBottom(theClass){
    div_height = $("."+theClass).height();
    div_offset = $("."+theClass).offset().top;
    window_height = $(window).height();
    $('html,body').animate({
      scrollTop: div_offset-window_height+div_height
    },'slow');
  }

$("#sendMessageToUser").on("click", function (e) {
    //Capture the necessary variables msgSenderUser,msgID,msgRecieverUser,msgContent
    var msgID=$('#msgID').val();
    var msgSenderUser=$('#msgSenderUser').val();
    var msgAdID=$('#msgAdID').val();
    var msgRecieverUser=$('#msgRecieverUser').val();
    var msgContent=$('#msgContent').val();
    var countSuccess=parseInt($('#lastCount').val())
    if (msgContent!=""){
        //do ajax request
    $.ajax({
        url: '../handlers/allUserOffice.php',
        type: 'GET',
        data: "msgID="+msgID+"&sendmsg=true&receiver="+msgRecieverUser+"&sender="+msgSenderUser+"&msgAdID="+msgAdID+"&msgContent="+msgContent,
        cache: false,
            contentType: false,
            processData: false,
        beforeSend:function(){
          //pageLoadWrapper.html("<div class='sys-loading'> </div>")
          $("#sendMessageToUser").html('<div class="spinner-grow bg-primary" role="status"></div>')
              },
        success: function (data) {
            data=data.trim();
            if (data=="error"){
                
            }else{
                //pageLoadWrapperMsgs.append(data);
                //countSuccess=countSuccess+1;
                //$('#lastCount').val(data);
                $('#msgContent').val(''); 
                $("#sendMessageToUser").html('<i class="fa fa-paper-plane fa-2x messagetext"></i>')
            }
        }
      });
    }
    
})