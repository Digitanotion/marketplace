function myFunc1() {
    var y = document.getElementById('demo112');
    var x = document.getElementById("demo1001");
    var z = document.getElementById("demo0");
    if (y.style.color == "blue") {
        y.style.color = "black";
        y.style.fontWeight = "normal"
        x.style.backgroundColor = "rgb(72, 72, 250)"
        x.innerText = "Saved"
        z.innerText = "Saved"
    } else {
        y.style.color = "blue";
        y.style.fontWeight = "bold";
        x.style.backgroundColor = "blue"
        x.innerText = "Save"
        z.innerText = "Save"
        z.style.padding = "0.5rem"
        z.style.color = "white"
        z.style.fontSize = "1rem"
    }
}

function myFunc2() {
    var y = document.getElementById('demo212');
    var x = document.getElementById("demo1001");
    var z = document.getElementById("demo0");
    if (y.style.color == "blue") {
        y.style.color = "black";
        y.style.fontWeight = "normal"
        x.style.backgroundColor = "rgb(72, 72, 250)"
        x.innerText = "Saved"
        z.innerText = "Saved"
    } else {
        y.style.color = "blue";
        y.style.fontWeight = "bold";
        x.style.backgroundColor = "blue"
        x.innerText = "Save"
        z.innerText = "Save"
        z.style.padding = "0.5rem"
        z.style.color = "white"
        z.style.fontSize = "1rem"
    }
}

function myFunc3() {
    var y = document.getElementById('demo333');
    if (y.style.color == "blue") {
        y.style.color = "black";
    } else {
        y.style.color = "blue";
    }
}

function myFunc00() {
    var x = document.getElementById("demo000");
    var y = document.getElementById("demo001");
    x.style.color = "blue";
    y.style.color = "black"
}
function myFunc01() {
    var x = document.getElementById("demo001");
    var y = document.getElementById("demo000");
    x.style.color = "blue";
    y.style.color = "black"
}

function myFunc7() {
    var x = document.getElementById("editPersonaldob_settings");
    var y = document.getElementById("demo1001");
    var z = document.getElementById("demo0");
    x.type = "date"
    y.style.backgroundColor = "blue";
    y.innerText = "Save";
    z.innerText = "Save";
    z.style.padding = "0.5rem"
    z.style.color = "white"
    z.style.fontSize = "1rem"
}

function changeTheColorOfButtonDemo() {
    if (document.getElementById("floatingFirstname").value !== "") {
        document.getElementById("demo1001").style.background = "blue";
        document.getElementById("demo1001").innerText = "Save";
        document.getElementById("demo0").style.padding = "0.5rem"
        document.getElementById("demo0").style.color = "white"
        document.getElementById("demo0").style.fontSize = "1rem"
        document.getElementById("demo0").innerText = "Save";
    } else {
        document.getElementById("demo1001").style.background = "rgb(72, 72, 250)";
        document.getElementById("demo0").innerText = "Saved"
        document.getElementById("demo1001").innerText = "Saved"
    }
}
function changeTheColorOfButtonDemo1() {
    if (document.getElementById("floatingLastname").value !== "") {
        document.getElementById("demo1001").style.background = "blue";
        document.getElementById("demo1001").innerText = "Save";
        document.getElementById("demo0").innerText = "Save";
        document.getElementById("demo0").style.padding = "0.5rem"
        document.getElementById("demo0").style.color = "white"
        document.getElementById("demo0").style.fontSize = "1rem"
    } else {
        document.getElementById("demo1001").style.background = "rgb(72, 72, 250)";
        document.getElementById("demo1001").innerText = "Saved";
        document.getElementById("demo0").innerText = "Saved"
    }
}
function changeTheColorOfButtonDemo2() {
    if (document.getElementById("floatingSelect").value !== "") {
        document.getElementById("demo1001").style.background = "blue";
        document.getElementById("demo1001").innerText = "Save";
        document.getElementById("demo0").innerText = "Save";
        document.getElementById("demo0").style.padding = "0.5rem"
        document.getElementById("demo0").style.color = "white"
        document.getElementById("demo0").style.fontSize = "1rem"
    } else {
        document.getElementById("demo1001").style.background = "rgb(72, 72, 250)";
        document.getElementById("demo1001").innerText = "Saved";
        document.getElementById("demo0").innerText = "Saved";
    }
}
function changeTheColorOfButtonDemo3() {
    if (document.getElementById("floatingSelect").value !== "") {
        document.getElementById("demo1001").style.background = "blue";
        document.getElementById("demo1001").innerText = "Save";
        document.getElementById("demo0").innerText = "Save";
        document.getElementById("demo0").style.padding = "0.5rem"
        document.getElementById("demo0").style.color = "white"
        document.getElementById("demo0").style.fontSize = "1rem"
    } else {
        document.getElementById("demo1001").style.background = "rgb(72, 72, 250)";
        document.getElementById("demo1001").innerText = "Saved";
        document.getElementById("demo0").innerText = "Saved";
    }
}
function changeTheColorOfButtonDemo4() {
    if (document.getElementById("floatingBusinessname").value !== "") {
        document.getElementById("demo1001").style.background = "blue";
        document.getElementById("demo1001").innerText = "Save";
        document.getElementById("demo0").innerText = "Save";
        document.getElementById("demo0").style.padding = "0.5rem"
        document.getElementById("demo0").style.color = "white"
        document.getElementById("demo0").style.fontSize = "1rem"
    } else {
        document.getElementById("demo1001").style.background = "rgb(72, 72, 250)";
        document.getElementById("demo1001").innerText = "Saved";
        document.getElementById("demo0").innerText = "Saved";
    }
}
function changeTheColorOfButtonDemo5() {
    if (document.getElementById("floatingDomain").value !== "") {
        document.getElementById("demo1001").style.background = "blue";
        document.getElementById("demo1001").innerText = "Save";
        document.getElementById("demo0").innerText = "Save";
        document.getElementById("demo0").style.padding = "0.5rem"
        document.getElementById("demo0").style.color = "white"
        document.getElementById("demo0").style.fontSize = "1rem"
    } else {
        document.getElementById("demo1001").style.background = "rgb(72, 72, 250)";
        document.getElementById("demo1001").innerText = "Saved";
        document.getElementById("demo0").innerText = "Saved";
    }
}
function changeTheColorOfButtonDemo6() {
    if (document.getElementById("floatingSelect").value !== "") {
        document.getElementById("demo1001").style.background = "blue";
        document.getElementById("demo1001").innerText = "Save";
        document.getElementById("demo0").innerText = "Save";
        document.getElementById("demo0").style.padding = "0.5rem"
        document.getElementById("demo0").style.color = "white"
        document.getElementById("demo0").style.fontSize = "1rem"
    } else {
        document.getElementById("demo1001").style.background = "rgb(72, 72, 250)";
        document.getElementById("demo1001").innerText = "Saved";
        document.getElementById("demo0").innerText = "Saved";
    }
}
function changeTheColorOfButtonDemo7() {
    if (document.getElementById("floatingPassword").value !== "") {
        document.getElementById("demo1001").style.background = "blue";
        document.getElementById("demo1001").innerText = "Save";
        document.getElementById("demo0").innerText = "Save";
        document.getElementById("demo0").style.padding = "0.5rem"
        document.getElementById("demo0").style.color = "white"
        document.getElementById("demo0").style.fontSize = "1rem"
    } else {
        document.getElementById("demo1001").style.background = "rgb(72, 72, 250)";
        document.getElementById("demo1001").innerText = "Saved";
        document.getElementById("demo0").innerText = "Saved";
    }
}
function changeTheColorOfButtonDemo8() {
    if (document.getElementById("floatingAbout").value !== "") {
        document.getElementById("demo1001").style.background = "blue";
        document.getElementById("demo1001").innerText = "Save";
        document.getElementById("demo0").innerText = "Save";
        document.getElementById("demo0").style.padding = "0.5rem"
        document.getElementById("demo0").style.color = "white"
        document.getElementById("demo0").style.fontSize = "1rem"
    } else {
        document.getElementById("demo1001").style.background = "rgb(72, 72, 250)";
        document.getElementById("demo1001").innerText = "Saved";
        document.getElementById("demo0").innerText = "Saved";
    }
}
function changeTheColorOfButtonDemo9() {
    if (document.getElementById("floatingAddress").value !== "") {
        document.getElementById("demo1001").style.background = "blue";
        document.getElementById("demo1001").innerText = "Save";
        document.getElementById("demo0").innerText = "Save";
        document.getElementById("demo0").style.padding = "0.5rem"
        document.getElementById("demo0").style.color = "white"
        document.getElementById("demo0").style.fontSize = "1rem"
    } else {
        document.getElementById("demo1001").style.background = "rgb(72, 72, 250)";
        document.getElementById("demo1001").innerText = "Saved";
        document.getElementById("demo0").innerText = "Saved";
    }
}
function changeTheColorOfButtonDemo10() {
    if (document.getElementById("floatingSelectGrid").value !== "") {
        document.getElementById("demo1001").style.background = "blue";
        document.getElementById("demo1001").innerText = "Save";
        document.getElementById("demo0").innerText = "Save";
        document.getElementById("demo0").style.padding = "0.5rem"
        document.getElementById("demo0").style.color = "white"
        document.getElementById("demo0").style.fontSize = "1rem"
    } else {
        document.getElementById("demo1001").style.background = "rgb(72, 72, 250)";
        document.getElementById("demo1001").innerText = "Saved";
        document.getElementById("demo0").innerText = "Saved";
    }
}
function changeTheColorOfButtonDemo11() {
    if (document.getElementById("btncheck1").value !== "") {
        document.getElementById("demo1001").style.background = "blue";
        document.getElementById("demo1001").innerText = "Save";
        document.getElementById("demo0").innerText = "Save";
        document.getElementById("demo0").style.padding = "0.5rem"
        document.getElementById("demo0").style.color = "white"
        document.getElementById("demo0").style.fontSize = "1rem"
    } else {
        document.getElementById("demo1001").style.background = "rgb(72, 72, 250)";
        document.getElementById("demo1001").innerText = "Saved";
        document.getElementById("demo0").innerText = "Saved";
    }
}
function changeTheColorOfButtonDemo12() {
    if (document.getElementById("btncheck2").value !== "") {
        document.getElementById("demo1001").style.background = "blue";
        document.getElementById("demo1001").innerText = "Save";
        document.getElementById("demo0").innerText = "Save";
        document.getElementById("demo0").style.padding = "0.5rem"
        document.getElementById("demo0").style.color = "white"
        document.getElementById("demo0").style.fontSize = "1rem"
    } else {
        document.getElementById("demo1001").style.background = "rgb(72, 72, 250)";
        document.getElementById("demo1001").innerText = "Saved";
        document.getElementById("demo0").innerText = "Saved";
    }
}
function changeTheColorOfButtonDemo13() {
    if (document.getElementById("btncheck3").value !== "") {
        document.getElementById("demo1001").style.background = "blue";
        document.getElementById("demo1001").innerText = "Save";
        document.getElementById("demo0").innerText = "Save";
        document.getElementById("demo0").style.padding = "0.5rem"
        document.getElementById("demo0").style.color = "white"
        document.getElementById("demo0").style.fontSize = "1rem"
    } else {
        document.getElementById("demo1001").style.background = "rgb(72, 72, 250)";
        document.getElementById("demo1001").innerText = "Saved";
        document.getElementById("demo0").innerText = "Saved";
    }
}
function changeTheColorOfButtonDemo14() {
    if (document.getElementById("btncheck4").value !== "") {
        document.getElementById("demo1001").style.background = "blue";
        document.getElementById("demo1001").innerText = "Save";
        document.getElementById("demo0").innerText = "Save";
        document.getElementById("demo0").style.padding = "0.5rem"
        document.getElementById("demo0").style.color = "white"
        document.getElementById("demo0").style.fontSize = "1rem"
    } else {
        document.getElementById("demo1001").style.background = "rgb(72, 72, 250)";
        document.getElementById("demo1001").innerText = "Saved";
        document.getElementById("demo0").innerText = "Saved";
    }
}
function changeTheColorOfButtonDemo15() {
    if (document.getElementById("btncheck5").value !== "") {
        document.getElementById("demo1001").style.background = "blue";
        document.getElementById("demo1001").innerText = "Save";
        document.getElementById("demo0").innerText = "Save";
        document.getElementById("demo0").style.padding = "0.5rem"
        document.getElementById("demo0").style.color = "white"
        document.getElementById("demo0").style.fontSize = "1rem"
    } else {
        document.getElementById("demo1001").style.background = "rgb(72, 72, 250)";
        document.getElementById("demo1001").innerText = "Saved";
        document.getElementById("demo0").innerText = "Saved";
    }
}
function changeTheColorOfButtonDemo16() {
    if (document.getElementById("btncheck6").value !== "") {
        document.getElementById("demo1001").style.background = "blue";
        document.getElementById("demo1001").innerText = "Save";
        document.getElementById("demo0").innerText = "Save";
        document.getElementById("demo0").style.padding = "0.5rem"
        document.getElementById("demo0").style.color = "white"
        document.getElementById("demo0").style.fontSize = "1rem"
    } else {
        document.getElementById("demo1001").style.background = "rgb(72, 72, 250)";
        document.getElementById("demo1001").innerText = "Saved";
        document.getElementById("demo0").innerText = "Saved";
    }
}
function changeTheColorOfButtonDemo17() {
    if (document.getElementById("btncheck7").value !== "") {
        document.getElementById("demo1001").style.background = "blue";
        document.getElementById("demo1001").innerText = "Save";
        document.getElementById("demo0").innerText = "Save";
        document.getElementById("demo0").style.padding = "0.5rem"
        document.getElementById("demo0").style.color = "white"
        document.getElementById("demo0").style.fontSize = "1rem"
    } else {
        document.getElementById("demo1001").style.background = "rgb(72, 72, 250)";
        document.getElementById("demo1001").innerText = "Saved";
        document.getElementById("demo0").innerText = "Saved";
    }
}