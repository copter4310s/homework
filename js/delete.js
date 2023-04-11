let hwid;

function afterLoad() {
	hwid = document.getElementById('hwid');
}

function doDelete() {
	showloading();
	document.getElementById("btn2").blur();

	let sendData = "do=1&" + "&hwid=" + hwid.value.trim();
	
	let xhr;
	if (window.XMLHttpRequest) { // Mozilla, Safari, ...
		xhr = new XMLHttpRequest();
	} else if (window.ActiveXObject) { // IE 8 and older
		xhr = new ActiveXObject('Microsoft.XMLHTTP');
	}
	xhr.open('POST', 'delete.php', true); 
	xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xhr.timeout = httpTimeout;
	xhr.ontimeout = function() { alert("ไม่สามารถโหลดข้อมูลได้ในเวลาที่กำหนด (Timeout)"); } ;
	xhr.send(sendData);
	xhr.onreadystatechange = display_data;
	function display_data() {
		if (xhr.readyState == 4) {
			if (xhr.status == 200) {
				if (xhr.responseText == "ok") {
					$("#modalSuccess").modal();
				} else if (xhr.responseText == "error0") {
					alert("เฉพาะคนที่เพิ่มการบ้านนี้เท่านั้นที่จะสามารถลบข้อมูลนี้ได้!");
				} else {
					alert(xhr.responseText);
				}
			}
			
			hideloading();
		}
	}
}