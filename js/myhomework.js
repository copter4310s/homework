let prefixInfo = "info-";
let prefixHw = "hw-";
let currentViewHwid = 0;
let currentPage = 1;

function loadInfo(btnId) {
    document.getElementById(btnId).blur();
	showloading();

	let xhr;
	if (window.XMLHttpRequest) { // Mozilla, Safari, ...
		xhr = new XMLHttpRequest();
	} else if (window.ActiveXObject) { // IE 8 and older
		xhr = new ActiveXObject('Microsoft.XMLHTTP');
	}
	let data = "mode=information&hwid=" + btnId;
	xhr.open('POST', './homeworklist.php', true); 
	xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');          
	xhr.timeout = httpTimeout;
	xhr.ontimeout = function() { bsalert("danger", "<b>แจ้งเตือน</b> ไม่สามารถโหลดข้อมูลได้ในเวลาที่กำหนด (Timeout)"); } ;
	xhr.send(data);
	xhr.onreadystatechange = display_data;
	function display_data() {
		if (xhr.readyState == 4) {
			if (xhr.status == 200) {
				let decodedJson = JSON.parse(xhr.responseText);
				
				for (var key in decodedJson) {
                    if (key == "hwid") {
						currentViewHwid = decodedJson[key];
					} else if (key == "isSent") {
						if (decodedJson[key] == true) {
							document.getElementById("markAsSent").style.display = "none";
							document.getElementById("markAsUnsend").style.display = "inline-table";
						} else {
							document.getElementById("markAsSent").style.display = "inline-table";
							document.getElementById("markAsUnsend").style.display = "none";
						}
					} else if (key == "subjectImg") {
						document.getElementById(prefixInfo + key).src = decodedJson[key];
                    } else {
						document.getElementById(prefixInfo + key).innerHTML = decodedJson[key];
					}
                }

				$("#modalInfo").modal();
			}
			
			hideloading();
		}
	}
}

function loadList() {
	setTimeout( function() { loadUnsend(); loadListMyhomework(); }, 100 );
}

function loadUnsend() {
	offUnsend();

	let xhr;
	if (window.XMLHttpRequest) { // Mozilla, Safari, ...
		xhr = new XMLHttpRequest();
	} else if (window.ActiveXObject) { // IE 8 and older
		xhr = new ActiveXObject('Microsoft.XMLHTTP');
	}
	let data = "mode=unsend";
	xhr.open('POST', './homeworklist.php', true); 
	xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');       
	xhr.timeout = httpTimeout;
	xhr.ontimeout = function() { bsalert("danger", "<b>แจ้งเตือน</b> ไม่สามารถโหลดข้อมูลได้ในเวลาที่กำหนด (Timeout)"); } ;
	xhr.send(data);
	xhr.onreadystatechange = display_data;
	function display_data() {
		if (xhr.readyState == 4) {
			if (xhr.status == 200) {
				let decodedJson = JSON.parse(xhr.responseText);
				
				for (var key in decodedJson) {
					document.getElementById(prefixHw + key).innerHTML = decodedJson[key];
                }
			}
			
			onUnsend();
		}
	}
}

function loadListMyhomework(page) {
	offList();
	
	if (page == null) {
		page = currentPage;
	} else {
		currentPage = page
	}
	
	let xhr;
	if (window.XMLHttpRequest) { // Mozilla, Safari, ...
		xhr = new XMLHttpRequest();
	} else if (window.ActiveXObject) { // IE 8 and older
		xhr = new ActiveXObject('Microsoft.XMLHTTP');
	}
	let data = "mode=myhomework";
	xhr.open('POST', './homeworklist.php', true); 
	xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');       
	xhr.timeout = httpTimeout;
	xhr.ontimeout = function() { bsalert("danger", "<b>แจ้งเตือน</b> ไม่สามารถโหลดข้อมูลได้ในเวลาที่กำหนด (Timeout)"); } ;
	xhr.send(data);
	xhr.onreadystatechange = display_data;
	function display_data() {
		if (xhr.readyState == 4) {
			if (xhr.status == 200) {
				let decodedJson = JSON.parse(xhr.responseText);
				let pageListOutput = "";
				
				for (var key in decodedJson) {
					if (key == "allegedly") {
						/*for (i=1;i<=decodedJson["allegedly"][0];i++) {
							pageListOutput = pageListOutput + '<a class="dropdown-item" href="#" onclick="loadList(' + i + ')">' + i + '</a>';
						}
						document.getElementById("allegedly-pageList").innerHTML = pageListOutput*/
						document.getElementById("allegedly-here").innerHTML = decodedJson["allegedly"][1] + "<div class='mt-2'><small>ทั้งหมด " + decodedJson["allegedly"][0] + " รายการ";
					} else if (key == "today") {
						/*for (i=1;i<=decodedJson["today"][0];i++) {
							pageListOutput = pageListOutput + '<a class="dropdown-item" href="#" onclick="loadList(' + i + ')">' + i + '</a>';
						}
						document.getElementById("today-pageList").innerHTML = pageListOutput;*/
						document.getElementById("today-here").innerHTML = decodedJson["today"][1] + "<div class='mt-2'><small>ทั้งหมด " + decodedJson["today"][0] + " รายการ";
					} else if (key == "tomorrow") {
						/*for (i=1;i<=decodedJson["tomorrow"][0];i++) {
							pageListOutput = pageListOutput + '<a class="dropdown-item" href="#" onclick="loadList(' + i + ')">' + i + '</a>';
						}
						document.getElementById("tomorrow-pageList").innerHTML = pageListOutput;*/
						document.getElementById("tomorrow-here").innerHTML = decodedJson["tomorrow"][1] + "<div class='mt-2'><small>ทั้งหมด " + decodedJson["tomorrow"][0] + " รายการ";
					} else if (key == "future") {
						/*for (i=1;i<=decodedJson["future"][0];i++) {
							pageListOutput = pageListOutput + '<a class="dropdown-item" href="#" onclick="loadList(' + i + ')">' + i + '</a>';
						}
						document.getElementById("future-pageList").innerHTML = pageListOutput;*/
						document.getElementById("future-here").innerHTML = decodedJson["future"][1] + "<div class='mt-2'><small>ทั้งหมด " + decodedJson["future"][0] + " รายการ";
					}
                }
			}
		}
		
		onList();
	}
}

function markAsSent() {
	showloading();
	if (currentViewHwid == 0) {
		
		bsalert("danger", '<b>แจ้งเตือน</b> ไม่สามารถทำเครื่องหมายว่าส่งแล้วได้เนื่องจากไม่ได้รับข้อมูลที่เพียงพอ');
		hideloading();
		
	} else {
		
		const inputDate = document.getElementById("dateTimeSend").value
		let unixDate = Date.parse( inputDate.replace("T", " ") )/1000;
	
		let xhrM;
		if (window.XMLHttpRequest) { // Mozilla, Safari, ...
			xhrM = new XMLHttpRequest();
		} else if (window.ActiveXObject) { // IE 8 and older
			xhrM = new ActiveXObject('Microsoft.XMLHTTP');
		}
		let data = "mode=markAsSent&date=" + unixDate + "&hwid=" + currentViewHwid;
		xhrM.open('POST', './homeworklist.php', true); 
		xhrM.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xhrM.timeout = httpTimeout;
		xhrM.ontimeout = function() { bsalert("danger", "<b>แจ้งเตือน</b> ไม่สามารถโหลดข้อมูลได้ในเวลาที่กำหนด (Timeout)"); } ;
		xhrM.send(data);
		xhrM.onreadystatechange = display_data;
		function display_data() {
			if (xhrM.readyState == 4) {
				if (xhrM.status == 200) {
					if (xhrM.responseText == "ok") {
						//bsalert("success", '<b>แจ้งเตือน</b> ทำเครื่องหมายว่าส่งแล้วเรียบร้อย!');
					} else {
						bsalert("danger", '<b>แจ้งเตือน</b> ไม่สามารถทำเครื่องหมายว่าส่งแล้วได้!<br>' + xhrM.responseText);
					}
				}
				
				hideloading();
				loadList();
			}
		}
	}
}

function markAsUnsend() {
	showloading();
	if (currentViewHwid == 0) {
		
		bsalert("danger", '<b>แจ้งเตือน</b> ไม่สามารถทำเครื่องหมายว่ายังไม่ได้ส่งแล้วได้เนื่องจากไม่ได้รับข้อมูลที่เพียงพอ');
		
	} else {
	
		let xhrU;
		if (window.XMLHttpRequest) { // Mozilla, Safari, ...
			xhrU = new XMLHttpRequest();
		} else if (window.ActiveXObject) { // IE 8 and older
			xhrU = new ActiveXObject('Microsoft.XMLHTTP');
		}
		let data = "mode=markAsUnsend&hwid=" + currentViewHwid;
		xhrU.open('POST', './homeworklist.php', true); 
		xhrU.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xhrU.timeout = httpTimeout;
		xhrU.ontimeout = function() { bsalert("danger", "<b>แจ้งเตือน</b> ไม่สามารถโหลดข้อมูลได้ในเวลาที่กำหนด (Timeout)"); } ;		
		xhrU.send(data);
		xhrU.onreadystatechange = display_data;
		function display_data() {
			if (xhrU.readyState == 4) {
				if (xhrU.status == 200) {
					if (xhrU.responseText == "ok") {
						//bsalert("success", '<b>แจ้งเตือน</b> ทำเครื่องหมายว่ายังไม่ได้ส่งแล้วเรียบร้อย!');
					} else {
						bsalert("danger", '<b>แจ้งเตือน</b> ไม่สามารถทำเครื่องหมายว่ายังไม่ได้ส่งแล้ว!<br>' + xhrU.responseText);
					}
				}
				
				hideloading();
				loadList();
			}
		}
	}
}

function confirmSendDate() {
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
	
	document.getElementById("dateTimeSend").value = todayDate;
	
	$("#modalSelectDate").modal();
}

function offUnsend() {
	document.getElementById("unsend").style.display = "none";
	document.getElementById("unsend-loading").style.display = "block";
}

function onUnsend() {
	document.getElementById("unsend").style.display = "block";
	document.getElementById("unsend-loading").style.display = "none";
}

function offList() {
	document.getElementById("allegedly-btnPageList").disabled = true;
	document.getElementById("today-btnPageList").disabled = true;
	document.getElementById("tomorrow-btnPageList").disabled = true;
	document.getElementById("future-btnPageList").disabled = true;
	
	document.getElementById("allegedly-here").innerHTML = '<center class="pt-1"><img src="wheel.svg" width="24" height="24" /></center>';
	document.getElementById("today-here").innerHTML = '<center class="pt-1"><img src="wheel.svg" width="24" height="24" /></center>';
	document.getElementById("tomorrow-here").innerHTML = '<center class="pt-1"><img src="wheel.svg" width="24" height="24" /></center>';
	document.getElementById("future-here").innerHTML = '<center class="pt-1"><img src="wheel.svg" width="24" height="24" /></center>';
}

function onList() {
	document.getElementById("allegedly-btnPageList").disabled = false;
	document.getElementById("today-btnPageList").disabled = false;
	document.getElementById("tomorrow-btnPageList").disabled = false;
	document.getElementById("future-btnPageList").disabled = false;
}