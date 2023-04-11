<?php
	include "./module/head.php";
	include "./module/sqllogin.php";
	include "./module/login.php";
	
	$formatStartWeek = date("Y-m-d", $timeStartThisWeek);
	$formatEndWeek = date("Y-m-d", $timeEndThisWeek);
?>

<html>
    <head>
        <!-- BOOTSTRAP -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
		<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

        <link rel="stylesheet" href="main.css" />
        <script src="script.js"></script>
		<script src="./js/viewhomework.js"></script>

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
						<center><b>การบ้านทั้งหมด</b></center>
					</font>
				</div>
				<div id="accordion" class="mb-3" style="width: 100%;">
					<div class="card border-primary mt-2" style="width: 100%;">
						<div class="card-header" id="headingOne" style="width: 100%;">
							<span class="mb-0">
								<button class="btn text-primary" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
									<font size="4"><b>ตั้งค่าการค้นหา</b></font>
								</button>
								<button class="btn btn-link" style="float: right" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
									<font size="4"><i class="fas fa-angle-down rotate-icon pt-1 pb-1"></i></font>
								</button>
							</span>
						</div>
						<div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion" style="width: 100%;">
							<div class="card-body" style="width: 100%;">
								<div>
									<font size="3"><b>ค้นหาจากการส่งงาน</b></font>
									<hr class="mt-1 mb-2">
									<button class="btn btn-sm btn-success mt-1" onclick="modeSent()">การบ้านที่ส่งแล้ว</button>
									<button class="btn btn-sm btn-danger mt-1" onclick="modeUnsend()">การบ้านที่ยังไม่ได้ส่ง</button>
									<button class="btn btn-sm btn-warning text-light mt-1" onclick="modeLate()">การบ้านที่ส่งแล้วแต่ช้า</button>
								</div>
								<div class="mt-2">
									<font size="3"><b>ค้นหาจากวันที่และเวลา</b></font>
									<hr class="mt-1 mb-2">
									<div class="input-group input-group-sm" style="width: 98%;">
										<div class="input-group-prepend">
											<span class="input-group-text">วันที่สั่งงาน</span>
										</div>
										<input type="date" id="assignStart" class="form-control" value="<?= $formatStartWeek ?>">
										<input type="date" id="assignEnd" class="form-control" value="<?= $formatEndWeek ?>">
										<div class="input-group-append">
											<button class="btn btn-primary" onclick="modeAssign()">ค้นหา</button>
										</div>
									</div>
									<div class="input-group input-group-sm mt-2" style="width: 98%;">
										<div class="input-group-prepend">
											<span class="input-group-text">วันที่ส่งงาน</span>
										</div>
										<input type="date" id="dueStart" class="form-control" value="<?= $formatStartWeek ?>">
										<input type="date" id="dueEnd" class="form-control" value="<?= $formatEndWeek ?>">
										<div class="input-group-append">
											<button class="btn btn-primary" onclick="modeDue()">ค้นหา</button>
										</div>
									</div>
									<div class="mt-2" style="width: 90%;">
										<button class="btn btn-sm btn-primary" onclick="modeLastweek()">การบ้านสัปดาห์ที่แล้ว</button>
										<button class="btn btn-sm btn-primary" onclick="modeThisweek()">การบ้านสัปดาห์นี้</button>
									</div>
								</div>
								<div class="mt-2">
									<font size="3"><b>ค้นหาจากวิชา</b></font>
									<hr class="mt-1 mb-2">
									<div style="width: 90%;">
										<select id="subjectId" class="selectpicker" data-size="10" data-width="73%" data-live-search="true">
											<?php
												$sql_comm = "SELECT subjectId, subjectName FROM $sql_subjectList WHERE subjectEnabled = 1 ORDER BY subjectId ASC;";
												$sql_query = mysqli_query($conn, $sql_comm);
												$returnlist = "";
												
												while($row = mysqli_fetch_array($sql_query)) {
													$returnlist .= "<option value=\"" . $row["subjectId"] . "\">" . $row["subjectId"] . " " . $row["subjectName"] . "</option>\n";
												}
												echo $returnlist;
											?>
										</select>
										<button class="btn btn-primary" style="width: 25%;" onclick="modeSubject()">ตกลง</button>
									</div>
								</div>
								<div class="mt-2">
									<hr class="mt-1 mb-2">
									<div style="width: 90%;">
										<button class="btn btn-sm btn-info" onclick="modeDefault()">ลบการตั้งค่า</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<hr class="mt-2 mb-2">
				<div id="listSelectPage" class="pb-3">
					<div class="btn-group">
						<button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="btnPageList">
							เลือกหน้า
						</button>
						<div id="pageList" class="dropdown-menu">
							
						</div>
					</div>
					<font size="2">
					&nbsp;(แสดงหน้าละ <?= $viewListEachPage ?> รายการ)
					</font>
					</div>
				<div id="listhere">
					<center class="pt-1"><img src="wheel.svg" width="24" height="24" /></center>
				</div>
				<div style="display:none;">
					<form action="edit.php" method="POST" id="formEdit">
						<input type="hidden" id="formEditId" name="hwid">
					</form>
					<form action="delete.php" method="POST" id="formDelete">
						<input type="hidden" id="formDeleteId" name="hwid">
					</form>
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
							<center class="mt-3">
								<button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"onclick="goDelete()"><i class="fas fa-trash-alt"></i>&nbsp; ลบข้อมูล</button>
								<button type="button" class="btn btn-sm btn-primary" data-dismiss="modal"onclick="goEdit()"><i class="fas fa-edit"></i>&nbsp; แก้ไขข้อมูล</button>
							</center>
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