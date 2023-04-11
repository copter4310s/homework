let subjectId;
let assignDate;
let topic;
let description;
let dueDate;
let noDueDate;
let isNoDueDate = false;
let emojiList = /(?:[\u2700-\u27bf]|(?:\ud83c[\udde6-\uddff]){2}|[\ud800-\udbff][\udc00-\udfff]|[\u0023-\u0039]\ufe0f?\u20e3|\u3299|\u3297|\u303d|\u3030|\u24c2|\ud83c[\udd70-\udd71]|\ud83c[\udd7e-\udd7f]|\ud83c\udd8e|\ud83c[\udd91-\udd9a]|\ud83c[\udde6-\uddff]|\ud83c[\ude01-\ude02]|\ud83c\ude1a|\ud83c\ude2f|\ud83c[\ude32-\ude3a]|\ud83c[\ude50-\ude51]|\u203c|\u2049|[\u25aa-\u25ab]|\u25b6|\u25c0|[\u25fb-\u25fe]|\u00a9|\u00ae|\u2122|\u2139|\ud83c\udc04|[\u2600-\u26FF]|\u2b05|\u2b06|\u2b07|\u2b1b|\u2b1c|\u2b50|\u2b55|\u231a|\u231b|\u2328|\u23cf|[\u23e9-\u23f3]|[\u23f8-\u23fa]|\ud83c\udccf|\u2934|\u2935|[\u2190-\u21ff])/g;

function afterLoad() {
	subjectId = document.getElementById('subjectId');
	assignDate = document.getElementById('assignDate');
	topic = document.getElementById('topic');
	description = document.getElementById('description');
	dueDate = document.getElementById('dueDate');
	noDueDate = document.getElementById('noDueDate');
}

function doAdd() {
	showloading();
	document.getElementById("btn2").blur();
	
	if (subjectId.value.trim() != "" && assignDate.value.trim() != "" && topic.value.trim() != "" && description.value.trim() != "") {
		
		if (topic.value.length > topicMax) {
			alert("หัวข้อไม่สามารถมีตัวอักษรเกิน " + topicMax + " ตัวได้");
			hideloading();
		} else if (description.value.length > descriptionMax) {
			alert("รายละเอียดไม่สามารถมีตัวอักษรเกิน " + descriptionMax + " ตัวได้");
			hideloading();
		} else if (isNoDueDate == false && dueDate.value.trim() == "") {
			alert("กรุณาใส่กำหนดส่ง");
			hideloading();
		} else {
			let assignDateUnix = Date.parse( assignDate.value.replace("T", " ") )/1000;
			let dueDateUnix = Date.parse( dueDate.value.replace("T", " ") )/1000;
			let sendData = "";
			
			if (! isNoDueDate) {
				sendData = "do=1&subjectId=" + encodeURI(subjectId.value.trim()) + "&assignDate=" + encodeURI(assignDateUnix) + "&topic=" + encodeURI(topic.value.trim()) + "&description=" + encodeURI(description.value.trim()) + "&dueDate=" + encodeURI(dueDateUnix);
			} else {
				sendData = "do=1&subjectId=" + encodeURI(subjectId.value.trim()) + "&assignDate=" + encodeURI(assignDateUnix) + "&topic=" + encodeURI(topic.value.trim()) + "&description=" + encodeURI(description.value.trim()) + "&dueDate=1";
			}
						
			let xhr;
			if (window.XMLHttpRequest) { // Mozilla, Safari, ...
				xhr = new XMLHttpRequest();
			} else if (window.ActiveXObject) { // IE 8 and older
				xhr = new ActiveXObject('Microsoft.XMLHTTP');
			}
			xhr.open('POST', 'addhomework.php', true); 
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
							alert("หัวข้อไม่สามารถมีตัวอักษรเกิน " + topicMax + " ตัวหรือรายละเอียดไม่สามารถมีตัวอักษรเกิน " + descriptionMax + " ตัวได้");
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
	topic.value = topic.value.replace(emojiList, "").trim();
	description.value = description.value.replace(emojiList, "").trim();
	
	if (subjectId.value.trim() != "" && assignDate.value.trim() != "" && topic.value.trim() != "" && description.value.trim() != "") {
		showNext();
	} else {
		alert("กรุณาใส่ข้อมูลให้ครบถ้วน");
	}
}

function changenoDueDate() {
	if (noDueDate.checked) {
		isNoDueDate = true;
		dueDate.readOnly = true;
	} else {
		isNoDueDate = false;
		dueDate.readOnly = false;
	}
}

function showNext() {
	document.getElementById("btn1").style.display = "none";
	document.getElementById("btn2").style.display = "inline-table";
	document.getElementById("btn3").style.display = "inline-table";
	
	assignDate.readOnly = true;
	topic.readOnly = true;
	description.readOnly = true;
	dueDate.readOnly = true;
	noDueDate.disabled = true;
}

function hideNext() {
	document.getElementById("btn1").style.display = "inline-table";
	document.getElementById("btn2").style.display = "none";
	document.getElementById("btn3").style.display = "none";
	
	assignDate.readOnly = false;
	topic.readOnly = false;
	description.readOnly = false;
	if (! isNoDueDate) {
		dueDate.readOnly = false;
	}
	noDueDate.disabled = false;
}

function clearInfo() {
	setDate();
	topic.value = "";
	description.value = "";
	hideNext();
}

function setDate() {
	let x = new Date();
    let y = x.getFullYear().toString();
    let m = (x.getMonth() + 1).toString();
    let d = x.getDate().toString();
	let H = x.getHours().toString();
	let i = x.getMinutes().toString();
    (d.length == 1) && (d = '0' + d);
    (m.length == 1) && (m = '0' + m);
	(H.length == 1) && (H = '0' + H);
	(i.length == 1) && (i = '0' + i);
    let todayDate = y + "-" + m + "-" + d + "T" + H + ":" + i;
	
	assignDate.value = todayDate;
}