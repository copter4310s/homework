const httpTimeout = 15000; //15 SEC

function go(times) {
    window.history.go(times);
}

function showloading() {
    document.getElementById("loading").style.display = "block";
}

function hideloading() {
    document.getElementById("loading").style.display = "none";
}

function bsalert(type, message) {
	document.getElementById("alerthere").innerHTML = document.getElementById("alerthere").innerHTML + '<div class="alert alert-' + type +'"><button type="button" class="close" data-dismiss="alert">&times;</button>' + message + '</div>';
}