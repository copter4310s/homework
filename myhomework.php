<?php
	include "./module/head.php";
	include "./module/sqllogin.php";
	include "./module/login.php";
?>

<html>
    <head>
        <!-- BOOTSTRAP -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">

        <link rel="stylesheet" href="main.css" />
        <script src="script.js"></script>
		<script src="./js/myhomework.js"></script>

        <title>Homework</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=0.95">
    </head>
    <body onload="loadList()">
        <div class="container-fluid pb-5">
            <nav class="navbar navbar-expand-sm bg-primary navbar-light fixed-top">
                <a onclick="javascript: $('#modalMenu').modal();"><img src="favicon.ico" class="navbar-brand angry-animate" alt="Logo" width="40" /></a> <span class="text-light navbar-text"><font size="4"><b>ระบบจัดเก็บข้อมูล 3</b></font></span>
            </nav>
            <br/>
            <div class="container-md pt-5">
				<div id="alerthere">
				
				</div>
                <div class="pt-1" id="divWelcome">
					<font size="5">
						<b>สรุปการบ้าน</b>
					</font>
				</div>
				<hr class="mt-2 mb-2">
				<div id="unsend-loading" class="pb-1"><center class="pt-1"><img src="wheel.svg" width="24" height="24" /></center></div>
				<div id="unsend" style="display: none;">
					<font size="3">
						<div><b>มีการบ้านทั้งหมด <span id="hw-total">...</span> งาน</b></div>
						<div><b>มีการบ้านที่ส่งแล้ว <span id="hw-sendTotal">...</span> งาน</b></div>
						<div class="pl-3 text-success"> - ส่งตรงเวลา <span id="hw-intime">...</span> งาน</div>
						<div class="pl-3 text-warning"> - ส่งเกินเวลา <span id="hw-late">...</span> งาน</div>
						<div class="mt-1"><b>มีการบ้านที่ยังไม่ได้ส่งทั้งหมด <span id="hw-leftTotal">...</span> งาน</b></div>
						<div class="pl-3 text-danger"> - งานที่เลยกำหนดส่งแล้ว <span id="hw-allegedly">...</span> งาน</div>
						<div class="pl-3 text-warning"> - งานที่ต้องส่งวันนี้ <span id="hw-today">...</span> งาน</div>
						<div class="pl-3 text-primary"> - งานที่ต้องส่งวันพรุ่งนี้ <span id="hw-tomorrow">...</span> งาน</div>
						<div class="pl-3 text-success"> - งานที่ต้องส่งเร็ว ๆ นี้ <span id="hw-future">...</span> งาน</div>
					</font>
				</div>
				<hr class="mt-2 mb-2">
				<a data-toggle="collapse" data-target="#div-allegedly">
					<div id="div-allegedly-head" class="width: 100%;">
						<div class="pb-2 text-danger">
							<font size="3">
								<b>งานที่เลยกำหนดส่งแล้ว</b>
							</font>
							<i class="fas fa-angle-down rotate-icon mt-1" style="float: right;"></i>
						</div>
					</div>
				</a>
				<div id="div-allegedly" class="collapse show" aria-labelledby="div-allegedly-head">
					<div id="listSelectPage" class="pb-3">
						<div class="btn-group">
							<button type="button" class="btn btn-danger btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="allegedly-btnPageList">
								เลือกหน้า
							</button>
							<div id="allegedly-pageList" class="dropdown-menu">
								<a class="dropdown-item disabled">Not implemented yet.</a>
							</div>
						</div>
						<font size="2">
						&nbsp;(แสดงอย่างละ <?= $listEachLine ?> รายการ)
						</font>
					</div>
					<div id="allegedly-here">
						<center class="pt-1"><img src="wheel.svg" width="24" height="24" /></center>
					</div>
				</div>
				<hr class="mt-4 mb-2">
				<a data-toggle="collapse" data-target="#div-today">
					<div id="div-today-head" class="width: 100%;">
						<div class="pb-2 text-warning">
							<font size="3">
								<b>งานที่ต้องส่งวันนี้</b>
							</font>
							<i class="fas fa-angle-down rotate-icon mt-1" style="float: right;"></i>
						</div>
					</div>
				</a>
				<div id="div-today" class="collapse show" aria-labelledby="div-today-head">
					<div id="listSelectPage" class="pb-3">
						<div class="btn-group">
							<button type="button" class="btn btn-warning text-light btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="today-btnPageList">
								เลือกหน้า
							</button>
							<div id="today-pageList" class="dropdown-menu">
								<a class="dropdown-item disabled">Not implemented yet.</a>
							</div>
						</div>
						<font size="2">
						&nbsp;(แสดงอย่างละ <?= $listEachLine ?> รายการ)
						</font>
					</div>
					<div id="today-here">
						<center class="pt-1"><img src="wheel.svg" width="24" height="24" /></center>
					</div>
				</div>
				<hr class="mt-4 mb-2">
				<a data-toggle="collapse" data-target="#div-tomorrow">
					<div id="div-tomorrow-head" class="width: 100%;">
						<div class="pb-2 text-primary">
							<font size="3">
								<b>งานที่ต้องส่งวันพรุ่งนี้</b>
							</font>
							<i class="fas fa-angle-down rotate-icon mt-1" style="float: right;"></i>
						</div>
					</div>
				</a>
				<div id="div-tomorrow" class="collapse show" aria-labelledby="div-tomorrow-head">
					<div id="listSelectPage" class="pb-3">
						<div class="btn-group">
							<button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="tomorrow-btnPageList">
								เลือกหน้า
							</button>
							<div id="tomorrow-pageList" class="dropdown-menu">
								<a class="dropdown-item disabled">Not implemented yet.</a>
							</div>
						</div>
						<font size="2">
						&nbsp;(แสดงอย่างละ <?= $listEachLine ?> รายการ)
						</font>
					</div>
					<div id="tomorrow-here">
						<center class="pt-1"><img src="wheel.svg" width="24" height="24" /></center>
					</div>
				</div>
				<hr class="mt-4 mb-2">
				<a data-toggle="collapse" data-target="#div-future">
					<div id="div-future-head" class="width: 100%;">
						<div class="pb-2 text-success">
							<font size="3">
								<b>งานที่ต้องส่งเร็ว ๆ นี้</b>
							</font>
							<i class="fas fa-angle-down rotate-icon mt-1" style="float: right;"></i>
						</div>
					</div>
				</a>
				<div id="div-future" id="div-tomorrow" class="collapse show" aria-labelledby="div-future-head">
					<div id="listSelectPage" class="pb-3">
						<div class="btn-group">
							<button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="future-btnPageList">
								เลือกหน้า
							</button>
							<div id="future-pageList" class="dropdown-menu">
								<a class="dropdown-item disabled">Not implemented yet.</a>
							</div>
						</div>
						<font size="2">
						&nbsp;(แสดงอย่างละ <?= $listEachLine ?> รายการ)
						</font>
					</div>
					<div id="future-here">
						<center class="pt-1"><img src="wheel.svg" width="24" height="24" /></center>
					</div>
				</div>
            </div>
			<div class="modal fade" id="modalInfo">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<font size="5" class="modal-title"><b id="info-topic">topic</b></font>
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>
						<div class="modal-body">
							<img src="" width="100%" style="border-radius: 4px;" loading="lazy" id="info-subjectImg">
							<div class="p-2">
								<font size="3"><span id="info-description">description</span></font>
							</div>
							<div>
								<small class="text-muted">
									<i class="fas fa-book-reader"></i> วิชา: <span id="info-subjectName">subjectName</span><br>
									<i class="fas fa-plus-square"></i> วันที่สั่ง: <span id="info-assignDate">assignDate</span><br>
									<i class="fas fa-clock"></i> กำหนดส่ง: <span id="info-dueDate">dueDate</span>
									<div style="padding-top: 4px;"></div>
									<i class="fas fa-user"></i> เพิ่มโดย: <span id="info-studentName">studentName</span><br>
									<i class="fas fa-user-clock"></i> วันที่เพิ่ม: <span id="info-addDate">addDate</span>
								</small>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-sm btn-success" data-dismiss="modal" id="markAsSent" onclick="confirmSendDate()"><i class="fas fa-check"></i>&nbsp; ทำเครื่องหมายว่าส่งแล้ว</button>
							<button type="button" class="btn btn-sm btn-warning text-light" data-dismiss="modal" id="markAsUnsend" onclick="markAsUnsend()"><i class="fas fa-times"></i>&nbsp; ทำเครื่องหมายว่ายังไม่ได้ส่ง</button>
							<button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">ปิดข้อความ</button>
						</div>
					</div>
				</div>
			</div>
			<div class="modal fade" id="modalSelectDate">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<font size="4" class="modal-title"><b>เลือกวันที่และเวลาที่ส่ง</b></font>
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>
						<div class="modal-body">
							<input type="datetime-local" id="dateTimeSend" class="form-control">
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-sm btn-success" data-dismiss="modal" id="markAsSent" onclick="markAsSent()">ตกลง</button>
						</div>
					</div>
				</div>
			</div>
			<?= $modalMenu ?>
			<div class="blockall" id="loading" style="display:none;">
				<div class="center">
					<img src="wheel.svg" width="48" height="48" />
				</div>
			</div>
        </div>
    </body>
</html>