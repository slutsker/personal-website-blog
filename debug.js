var x = 1;
function debug(msg) {
    $("#debug").append($(document.createElement("div")).text(x + ": " + msg));
    x+=1;
}
