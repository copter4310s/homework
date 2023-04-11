let studentName;
let studentLastName;
let inputPassword;
let emojiList = /(?:[\u2700-\u27bf]|(?:\ud83c[\udde6-\uddff]){2}|[\ud800-\udbff][\udc00-\udfff]|[\u0023-\u0039]\ufe0f?\u20e3|\u3299|\u3297|\u303d|\u3030|\u24c2|\ud83c[\udd70-\udd71]|\ud83c[\udd7e-\udd7f]|\ud83c\udd8e|\ud83c[\udd91-\udd9a]|\ud83c[\udde6-\uddff]|\ud83c[\ude01-\ude02]|\ud83c\ude1a|\ud83c\ude2f|\ud83c[\ude32-\ude3a]|\ud83c[\ude50-\ude51]|\u203c|\u2049|[\u25aa-\u25ab]|\u25b6|\u25c0|[\u25fb-\u25fe]|\u00a9|\u00ae|\u2122|\u2139|\ud83c\udc04|[\u2600-\u26FF]|\u2b05|\u2b06|\u2b07|\u2b1b|\u2b1c|\u2b50|\u2b55|\u231a|\u231b|\u2328|\u23cf|[\u23e9-\u23f3]|[\u23f8-\u23fa]|\ud83c\udccf|\u2934|\u2935|[\u2190-\u21ff])/g;

function afterLoad() {
	studentName = document.getElementById('studentName');
	studentLastName = document.getElementById('studentLastName');
	inputPassword = document.getElementById('inputPassword');
}

function doChange() {
	showloading();
	document.getElementById("btn2").blur();
	
	if (studentName.value.trim() != "" && studentLastName.value.trim() != "") {
		
		if (studentName.value.trim().length > nameMax) {
			alert("ชื่อจริงไม่สามารถมีตัวอักษรเกิน " + nameMax + " ตัวได้");
			hideloading();
		} else if (studentLastName.value.trim().length > lastnameMax) {
			alert("นามสกุลไม่สามารถมีตัวอักษรเกิน " + lastnameMax + " ตัวได้");
			hideloading();
		} else {
			let sendData = "";
			if (inputPassword.value.trim() != "") {
				
				if (inputPassword.value.trim().length < inputPasswordMin && inputPassword.value.trim().length > inputPasswordMax) {
					alert("รหัสผ่านไม่สามารถมีตัวอักษรน้อยกว่า " + inputPasswordMin + " ตัวและมากกว่า " + inputPasswordMax + " ได้");
					hideloading();
					return;
				} else {
					sendData = "do=1&studentName=" + encodeURI(studentName.value.trim()) + "&studentLastName=" + encodeURI(studentLastName.value.trim()) + "&password=" + encodeURI(inputPassword.value.trim());
				}
			} else {
				sendData = "do=1&studentName=" + encodeURI(studentName.value.trim()) + "&studentLastName=" + encodeURI(studentLastName.value.trim());
			}
			
			let xhr;
			if (window.XMLHttpRequest) { // Mozilla, Safari, ...
				xhr = new XMLHttpRequest();
			} else if (window.ActiveXObject) { // IE 8 and older
				xhr = new ActiveXObject('Microsoft.XMLHTTP');
			}
			xhr.open('POST', 'account.php', true); 
			xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			xhr.timeout = httpTimeout;
			xhr.ontimeout = function() { bsalert("danger", "<b>แจ้งเตือน</b> ไม่สามารถโหลดข้อมูลได้ในเวลาที่กำหนด (Timeout)"); } ;
			xhr.send(sendData);
			xhr.onreadystatechange = display_data;
			function display_data() {
				if (xhr.readyState == 4) {
					if (xhr.status == 200) {
						if (xhr.responseText == "ok") {
							$("#modalSuccess").modal();
						} else if (xhr.responseText == "error1") {
							alert("ชื่อจริงไม่สามารถมีตัวอักษรเกิน " + nameMax + " ตัวได้\n" + "นามสกุลไม่สามารถมีตัวอักษรเกิน " + lastnameMax + " ตัวได้\n" + "รหัสผ่านไม่สามารถมีตัวอักษรน้อยกว่า " + inputPasswordMin + " ตัวและมากกว่า " + inputPasswordMax + " ได้");
						} else {
							alert(xhr.responseText);
						}
					}
					
					hideloading();
				}
			}
		}
		
	} else {
		alert("กรุณาใส่ข้อมูลให้ครบถ้วน");
		hideloading();
	}
}

function checkInfo() {
	studentName.value = studentName.value.replace(emojiList, "").trim();
	studentLastName.value = studentLastName.value.replace(emojiList, "").trim();
	
	if (studentName.value.trim() != "" && studentLastName.value.trim() != "") {
		showNext();
	} else {
		alert("กรุณาใส่ข้อมูลให้ครบถ้วน");
	}
}

function showNext() {
	document.getElementById("btn1").style.display = "none";
	document.getElementById("btn2").style.display = "inline-table";
	document.getElementById("btn3").style.display = "inline-table";
	
	studentName.readOnly = true;
	studentLastName.readOnly = true;
	inputPassword.readOnly = true;
}

function hideNext() {
	document.getElementById("btn1").style.display = "inline-table";
	document.getElementById("btn2").style.display = "none";
	document.getElementById("btn3").style.display = "none";
	
	studentName.readOnly = false;
	studentLastName.readOnly = false;
	inputPassword.readOnly = false;
}