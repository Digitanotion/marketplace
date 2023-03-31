var demo1=document.getElementById('demo1');

function showPhoneNo(phoneNo) {
    demo1.textContent = phoneNo;
}

// Update message chat box
setInterval(() => {
    //Capture the necessary variables msgSenderUser,msgID,msgRecieverUser,msgContent
    var msgID=$('#msgID').val();
    var countSuccess=parseInt($('#lastCount').val())+1;
    var pageLoadWrapperMsgs=$('.mainchat');
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
                
            }else{
                pageLoadWrapperMsgs.append(data);
                //pageLoadWrapperMsgs = pageLoadWrapperMsgs.scrollHeight;
                pageLoadWrapperMsgs[0].scrollTop = pageLoadWrapperMsgs[0].scrollHeight;
                $('#lastCount').val(countSuccess);
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
              },
        success: function (data) {
            data=data.trim();
            if (data=="error"){
                
            }else{
                pageLoadWrapperMsgs.append(data);
                $('#lastCount').val(countSuccess);
            }
        }
      });
})