var phone0 = document.getElementById("phone0")

function myPhone(){
    var y = document.getElementById("number0")
    y.style.display = "none"
    phone0.style.display = "block"
}
function myText(){
    var y = document.getElementById("number0")
    var z = document.getElementById("phone1")
    var x = document.getElementById("phone2")
    z.style.display = "block"
    x.style.display = "block"
    y.style.display = "none"
}
function myCall(){
    var y = document.getElementById("call0")
    phone0.style.display = "none"
    y.style.display = "block"
}
function myInfo(){
    var y = document.getElementById("info0")
    var z = document.getElementById("info1")
    phone0.style.display = "none" 
    y.style.display = "block" 
    z.style.display = "block"
}
function myFile(){
    document.getElementById("click0").click();
}
function myDate(){
    var x = document.getElementById("date0").type = "date"
}
function myEnable(){
    document.getElementById("enable0").disabled = false;
}