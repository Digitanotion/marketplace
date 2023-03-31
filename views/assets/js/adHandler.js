$(document).ready(function(){

	$("select[name='category'").change(function () {
		var category = $(this).val();
		$.ajax({
				url: '../handlers/adHandler.php',
				type: 'post',
				data: "category="+category,
				success: function (data) {
					if (typeof(data) !== 'string') {

					} 
					try {
						var childCat = JSON.parse(data);

						for(key in childCat){
							if (childCat.hasOwnProperty(key)) {
								$("select[name = 'sub_category']").append(`<option value="${key}">${childCat[key]}</option>`)
							}
						}
					} catch(e) {
						$("select[name = 'sub_category']").append(`<option value = "" disabled>${data}</option>`);
					}
					
					
				}
			});
		$("select[name='sub_category'").empty().append('<option id="option">Select Sub Category</option>');
	});

	$("select[name='location_state'").change(function () {
		var location_state = $(this).val();
		//alert (location_state);
		$.ajax({
				url: '../handlers/adHandler.php',
				type: 'GET',
				data: "location_state="+location_state,
				beforeSend:function(){
					$("select[name = 'location_city']").append('<option id="option">Loading Cities..</option>');
						},
				success: function (data) {
				    //console.log(data +" Welcome");
					if (typeof(data) !== 'string') {

					} 
					try {
						
								$("select[name = 'location_city']").append(data);
					} catch(e) {
						$("select[name = 'location_city']").append(`<option value = "" disabled>${data}</option>`);
					}
					
					
				}
			});
		$("select[name = 'location_city']").empty().append('<option id="select_city">Select City</option>');
	});

	$("select[name='sub_category'").change(function () {
		var sub_category = $(this).val();
		$.ajax({
				url: '../../handlers/adHandler.php',
				type: 'post',
				data: "sub_category="+sub_category,
				success: function (data) {
					if (typeof(data) !== 'string') {

					} 
					try {
						var lastCat = JSON.parse(data);
						for(key in lastCat){
							if (lastCat.hasOwnProperty(key)) {
								$("select[name = 'sub_sub_category']").append(`<option value="${key}">${lastCat[key]}</option>`);
							}
						}
					} catch(e) {
						$("select[name = 'sub_sub_category']").append(`<option value = "" disabled>${data}</option>`);
					}					
				}
			});
		$("select[name='sub_sub_category'").empty().append('<option id="option">Other Category</option>');
	});

})