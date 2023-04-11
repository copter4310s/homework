function goLogin() {
	offUI();
	if (! document.getElementById("password").value.trim() == "") {
	
		timer = setTimeout(function(){
			
			let studentId = document.getElementById("studentId").value;
			let password = document.getElementById("password").value;
			
			var xhr;
			if (window.XMLHttpRequest) { // Mozilla, Safari, ...
				xhr = new XMLHttpRequest();
			} else if (window.ActiveXObject) { // IE 8 and older
				xhr = new ActiveXObject('Microsoft.XMLHTTP');
			}
			var data = "studentId=" + studentId + "&password=" + password.trim();
			xhr.open('POST', './module/login.php', true); 
			xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');                  
			xhr.send(data);
			xhr.onreadystatechange = display_data;
			function display_data() {
				if (xhr.readyState == 4) {
					if (xhr.status == 200) {
						if (xhr.responseText == "no") {
							onUI();
							document.getElementById("modalWarningContent").innerHTML = "รหัสผ่านผิด, กรุณาใส่ใหม่!";
							$("#modalWrong").modal();
						} else if (xhr.responseText == "ok") {
							window.location.href = "home.php";
						} else {
							onUI();
							document.getElementById("modalWarningContent").innerHTML = xhr.responseText;
							$("#modalWrong").modal();
						}
					}
				}
			}
			
		}, 150);
		
	} else {
		
		onUI();
		document.getElementById("modalWarningContent").innerHTML = "กรุณาใส่รหัสผ่าน!";
		$("#modalWrong").modal();
		
	}
}

function offUI() {
	document.getElementById("btnLogin").disabled = true;
    document.getElementById("password").readOnly = true;
    document.getElementById("btnLogin-spinner").style.display = "inline-block";
    document.getElementById("btnLogin-text").style.display = "none";
	showloading();
}

function onUI() {
	document.getElementById("btnLogin").disabled = false;
    document.getElementById("password").readOnly = false;
    document.getElementById("btnLogin-spinner").style.display = "none";
    document.getElementById("btnLogin-text").style.display = "inline-block";
	hideloading();
}