$(function () {
    var myCarousel = document.querySelector('#adImageCarousel')
    var carousel = new bootstrap.Carousel(myCarousel, {
    interval: 2000,
    wrap: false
    }) 
   


    //Initialize Select2 Elements
    /* $(".select2").select2({
        theme: "bootstrap-5",
    }) */
    
})


$(window).scroll(function() {
if ($(window).scrollTop() > 10) {
$('#ha-header__top ').addClass('shadow-sm');
} else {
$('#ha-header__top ').removeClass('shadow-sm');
}
});