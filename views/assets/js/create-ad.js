$("#next").click(function () {
		next();
		var sub_category = $("select[name='sub_category'").val();
		var adUp;
		$(".second").addClass("categChecker");
		if ($(".second").hasClass("categChecker")) {
			if ($("#mallAdIDVal").val().length == 0){
				$.ajax({
					url: '../handlers/formHandler.php',
					type: 'post',
					data: "category="+sub_category,
					success: function (data) {
						$("#load-form-inputs").html(data);			
					}
				});
			}else{
				adIDUp=$("#mallAdIDVal").val();
				console.log(adIDUp);
				$.ajax({
					url: '../handlers/formHandler.php',
					type: 'post',
					data: "category="+sub_category+"&adUp="+adIDUp,
					success: function (data) {
						$("#load-form-inputs").html(data);			
					}
				});
			}
			
		}
});
$("#ad-info").click(function () {
	$(".second").addClass("d-none");
	$(".first").removeClass("d-none");
	$('#pills-ad-detail-tab').removeClass('active');
	$('#pills-ad-info-tab').addClass('active');
});
$("#ad-detail").click(function () {
	if ($(".second").hasClass("categChecker")) {
		$(".first").addClass("d-none");
		$(".second").removeClass("d-none");
		$('#pills-ad-info-tab').removeClass('active');
		$('#pills-ad-detail-tab').addClass('active');
	}

});

$(document).ready(function(){
	getWinLocation=window.location.href;
	if (getWinLocation.indexOf("update_Ad") !== -1) {
		
	  }
	  else{
		$("select[name = 'sub_category']").attr("disabled", true);
		$("select[name = 'sub_sub_category']").attr("disabled", true);
		$("#imageFile").attr("disabled", true);
		$("#next").attr("disabled", true);
		$("select[name = 'location_state']").attr("disabled", true);
	$("select[name = 'location_city']").attr("disabled", true);
	  }

	$(".ha-del-image__item").click(function (e) {
		//var mediaID=$(this)
		//alert("Deleted");
	})
	
})
function delImageUpload(mediaID){
	var delFormData=new FormData();
	var parentDelContainer=$("#"+mediaID)
	delFormData.append("delReq",mediaID);
	console.log(mediaID);
	parentDelContainer.addClass("d-none");
	$.ajax({
		url: '../handlers/mediaUpload.php',
		type: 'POST',
		data: delFormData,
		cache: false,
        contentType: false,
        processData: false,
		beforeSend:function(){
			
          },
		success: function (data) {
			//parentDelContainer.addClass("d-none");
			var numUploaded=parseInt($("#numUploaded").val());
			$("#numUploaded").val(numUploaded-1);
			var numUploaded2=parseInt($("#numUploaded").val());
			if (numUploaded2<2){
				getWinLocation=window.location.href;
				if (getWinLocation.indexOf("update_Ad") !== -1) {
					
				}
				else{
					$("#next").attr("disabled", true);
				}
			}
			else{
				$("#next").removeAttr("disabled", true);
			}		
		}
	}); 
	//alert(mediaID);
}
/* onchange="imagePreview(this)"
function imagePreview (image) {
    var img = image.files[0];
    var reader = new FileReader();
    reader.onloadend = function(){
        $(".imageDisplay").append(' <img src="" alt="" width="70em" height="70em">');
        $('.imageDisplay').children().last().attr('src', reader.result); 
    }
    reader.readAsDataURL(img);
} */

$("select[name = 'category']").change(function (e) {
	$("select[name = 'sub_category']").removeAttr("disabled", true);
});
$("select[name = 'sub_category']").change(function (e) {
	$("select[name = 'sub_sub_category']").removeAttr("disabled", true);
	$("select[name = 'location_state']").removeAttr("disabled", true);
});
$("select[name = 'location_state']").change(function (e) {
	$("select[name = 'location_city']").removeAttr("disabled", true);
});

$("select[name = 'location_city']").change(function (e) {
	$("#mallAdTitle").removeAttr("disabled", true);
});
$("#mallAdTitle").blur(function (e) {
	if ($(this).val().length == 0){
		$("#imageFile").attr("disabled", true);
	}else{
		$("#imageFile").removeAttr("disabled", true);
	}
});


$("#imageFile").change(function (e) {
	var formData = new FormData();
	var adID=$("#mallAdIDVal").val();
	var usrID=$("#usrIDData").val();
	var adCategory=$("#sub_category").val();
	var adTitle=$("#mallAdTitle").val();
	var numUploaded=parseInt($("#numUploaded").val());
	//var imageFile=;
	//Ad image file = adID+adCategory+adTitle 
	var adImgFile=adTitle+"_"+adID+"_"+adCategory+"_";
	var totalfiles = parseInt(document.getElementById('imageFile').files.length);
	if (totalfiles>1 || numUploaded>0){
		$("#next").removeAttr("disabled", true);
	}
	else{
		getWinLocation=window.location.href;
		if (getWinLocation.indexOf("update_Ad") !== -1) {
			
		}
		else{
			//$("#imageFile").attr("disabled", true);
			$("#next").attr("disabled", true);
		}
	}
   for (var index = 0; index < totalfiles; index++) {
	formData.append("imageFile[]", document.getElementById('imageFile').files[index]);
   }
	
	formData.append("adImgFile", adImgFile);
	formData.append("adID", adID);
	formData.append("usrID", usrID);
	console.log(formData);
	  $.ajax({
		url: '../handlers/mediaUpload.php',
		type: 'POST',
		data: formData,
		cache: false,
        contentType: false,
        processData: false,
		beforeSend:function(){
			for (var index = 0; index < totalfiles; index++) {
				$("#loadImageDisplay").append('<div class="ha-imgUpload-each loading bg-white m-1 border-danger" style=""><span class="text-danger d-flex justify-content-center align-content_center " style="margin-top:31%"></span></div>');
			   }
            
          },
		success: function (data) {
			$(".loading").hide();
			$("#numUploaded").val(numUploaded+totalfiles);
			$("#loadImageDisplay").append(data);			
		}
	});
}); 

function enabler(){
	var count = $(".imageDisplay").children().length;
	count++;
	if (count > 1) {
		$("#next").removeAttr("disabled", true);
	} else {
		$("#next").attr("disabled", true);
	}
}

function please_wait(){

	$("#next").click(function() {
		$(this).text("Please Wait  ");
		$(this).append('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span class="visually-hidden">Loading...</span>');
		// setTimeout(next, 1)
		next();
	});

	
}

function next () {
	$('.first').addClass('d-none');
	$('.second').removeClass('d-none');

	$('#pills-ad-info-tab').removeClass('active');
	$('#pills-ad-detail-tab').addClass('active');
}


          

