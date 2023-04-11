<?php
	include "./module/head.php";
	include "./module/sqllogin.php";
	include "./module/login.php";
	
	if ( $isLoggedIn ) {
		if ( isset( $_POST["loadUnsend"] ) ) {
			$sql_com = "SELECT COUNT(hwid) AS total FROM $sql_list WHERE NOT EXISTS (SELECT 1 FROM $sql_progress WHERE $sql_list.hwid=$sql_progress.hwid AND $sql_progress.progress!=0 AND $sql_progress.studentId = $studentId)";
			$sql_que = mysqli_query( $conn, $sql_com );
			$sql_res = mysqli_fetch_assoc( $sql_que );
			$hwLeft = $sql_res["total"];
			
			echo $hwLeft;
			
			exit();
		}
	}
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
		<script src="./js/home.js"></script>

        <title>Homework</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=0.95">
    </head>
    <body onload="loadList(); loadUnsend()">
        <div class="container-fluid pb-5">
            <nav class="navbar navbar-expand-sm bg-primary navbar-light fixed-top">
                <a onclick="javascript: $('#modalMenu').modal();"><img src="favicon.ico" class="navbar-brand angry-animate" alt="Logo" width="40" /></a> <span class="text-light navbar-text"><font size="4"><b>ระบบจัดเก็บข้อมูล 3</b></font></span>
            </nav>
            <br/>
            <div class="container-md pt-5">
				<div id="alerthere">
				
				</div>
                <div class="pt-2" id="divWelcome">
					<font size="4">
						<b>ยินดีต้อนรับ <?= $studentName . " " . $studentLastName ?></b>
					</font>
				</div>
				<hr class="mt-2 mb-2">
				<div id="divHWleft">
					<font size="3">
						ขณะนี้มีการบ้านที่ยังไม่ได้ทำ <span id="hwLeft">...</span> งาน นะจ๊ะ
					</font>
					<div>
						<table border="0">
							<tr>
								<td>
									<a class="btn btn btn-primary" href="myhomework.php"><i class="fas fa-calendar-day"></i> &nbsp;สรุปการบ้าน</a>
								</td>
								<td>
									<a class="btn btn btn-primary w-100" href="addhomework.php"><i class="fas fa-calendar-plus"></i> &nbsp;เพิ่มการบ้าน</a>
								</td>
							</tr>
						</table>
					</div>
				</div>
				<hr class="mt-2 mb-2">
				<div id="divList">
					<div id="listSelectPage" class="pb-3">
						<div class="pb-2">
							<font size="3">
								<b>รายการการบ้านทั้งหมด</b>
							</font>
						</div>
						<a href="viewhomework.php" class="btn btn-sm btn-info" id="btnAdvancedSearch">
							ค้นหาแบบละเอียด
						</a>
						<div class="btn-group">
							<button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="btnPageList">
								เลือกหน้า
							</button>
							<div id="pageList" class="dropdown-menu">
								
							</div>
						</div>
						<font size="2">
						&nbsp;(แสดงหน้าละ <?= $listEachPage ?> รายการ)
						</font>
					</div>
					<div id="cardhere">
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