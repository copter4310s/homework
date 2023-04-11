<?php
	include "./module/head.php";
	include "./module/sqllogin.php";
	include "./module/login.php";
	
	usleep(5000); //SLEEP 5MS
	
	if ( isset( $_POST["do"] ) && $isLoggedIn ) {
		$studentName = trim(urldecode($_POST["studentName"]));
		$studentLastName = trim(urldecode($_POST["studentLastName"]));
		$password = trim(urldecode($_POST["password"]));
		
		if (mb_strlen($studentName, "UTF-8") <= $nameMax && mb_strlen($studentLastName, "UTF-8") <= $lastnameMax) {
			$studentName = mysqli_real_escape_string( $conn, $studentName );
			$studentLastName = mysqli_real_escape_string( $conn, $studentLastName );
			$password = mysqli_real_escape_string( $conn, $password );
			
			if ( isset( $_POST["password"] ) ) {
				
				if ( mb_strlen($password, "UTF-8") >= $passwordMin && mb_strlen($password, "UTF-8") <= $passwordMax ) {
					$password = sha1( $powder . $password );
					$sql_com = "UPDATE $sql_account SET studentName = '$studentName', studentLastName = '$studentLastName', password = '$password' WHERE studentId = $studentId";
				} else {
					echo "error1";
					exit();
				}
				
			} else {
				$sql_com = "UPDATE $sql_account SET studentName = '$studentName', studentLastName = '$studentLastName' WHERE studentId = $studentId";
			}
			
			$sql_que = mysqli_query( $conn, $sql_com);
			
			if ($sql_com) {
				echo "ok";
			} else {
				echo "Couldn't query the database.";
			}
			//echo $conn->error;
		} else {
			echo "error1";
		}
		
		exit();
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
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
		<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

        <link rel="stylesheet" href="main.css" />
		<script>
			const nameMax = <?= $nameMax; ?>;
			const lastnameMax = <?= $lastnameMax; ?>;
			const passwordMin = <?= $passwordMin; ?>;
			const passwordMax = <?= $passwordMax; ?>;
		</script>
        <script src="script.js"></script>
		<script src="./js/account.js"></script>

        <title>Homework</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=0.95">
    </head>
    <body onload="afterLoad(); hideloading(); hideNext()">
        <div class="container-fluid">
            <nav class="navbar navbar-expand-sm bg-primary navbar-light fixed-top">
                <a onclick="javascript: $('#modalMenu').modal();"><img src="favicon.ico" class="navbar-brand angry-animate" alt="Logo" width="40" /></a> <span class="text-light navbar-text"><font size="4"><b>ระบบจัดเก็บข้อมูล 3</b></font></span>
            </nav>
			<div class="blockall" id="loading">
				<div class="center">
					<img src="wheel.svg" width="48" height="48" />
				</div>
			</div>
            <br/>
            <div class="container-md pt-5">
				<center>
					<div>
						<b><font size="5">ตั้งค่าบัญชี</font></b><br>
						<font size="3">ใส่ข้อมูลให้ครบถ้วน</font>
					</div>
					<div class="mt-2">
						<table class="table table-bordered">
							<thead class="thead-light">
								<tr>
									<th width="35%">
										<font size="4">ชนิด</font>
									</th>
									<th width="65%">
										<font size="4">ข้อมูล</font>
									</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>
										เลขที่
									</td>
									<td>
										<center>
											<div style="width: 86%;">
												<input type="number" id="studentNumber" class="form-control" maxlength="3" disabled value="<?= $studentNumber ?>">
											</div>
										</center>
									</td>
								</tr>
								<tr>
									<td>
										เลขประจำตัว
									</td>
									<td>
										<center>
											<div style="width: 86%;">
												<input type="number" id="studentId" class="form-control" maxlength="6" disabled value="<?= $studentId ?>">
											</div>
										</center>
									</td>
								</tr>
								<tr>
									<td>
										ชื่อจริง
									</td>
									<td>
										<center>
											<div style="width: 86%;">
												<input type="text" id="studentName" class="form-control" maxlength="<?= $nameMax ?>" autocomplete="off" value="<?= $studentName ?>">
											</div>
										</center>
									</td>
								</tr>
								<tr>
									<td>
										นามสกุล
									</td>
									<td>
										<center>
											<div style="width: 86%;">
												<input type="text" id="studentLastName" class="form-control" maxlength="<?= $lastnameMax ?>" autocomplete="off" value="<?= $studentLastName ?>">
											</div>
										</center>
									</td>
								</tr>
								<tr>
									<td>
										รหัสผ่าน
									</td>
									<td>
										<center>
											<div style="width: 86%;">
												<input type="password" id="inputPassword" class="form-control" minlength="<?= $minlength ?>" autocomplete="off" maxlength="<?= $passwordMax ?>">
											</div>
											<div class="mt-2"><small>หากไม่ต้องการเปลี่ยนรหัสผ่านให้เว้นว่างไว้</small></div>
										</center>
									</td>
								</tr>
							</tbody>
						</table>
						<div>
							<button class="btn btn-primary" id="btn1" onclick="checkInfo()">ถัดไป</button>
							<button class="btn btn-danger" id="btn2" onclick="doChange()">บันทึก</button>
							<button class="btn btn-success" id="btn3" onclick="hideNext()">กลับไปแก้ไข</button>
						</div>
					</div>
				</center>
            </div>
			<div class="modal fade" id="modalSuccess" data-backdrop="static" data-keyboard="false">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<font size="5" class="modal-title"><b>แจ้งเตือน</b></font>
						</div>
						<div class="modal-body">
							<font size="3">เปลี่ยนข้อมูลเรียบร้อยแล้ว</font>
						</div>
						<div class="modal-footer">
							<button class="btn btn-sm btn-success" onclick="hideNext()" data-dismiss="modal">ปิดข้อความ</button>
							<button class="btn btn-sm btn-success" onclick="go(-1)" data-dismiss="modal">กลับไปหน้าที่แล้ว</button>
						</div>
					</div>
				</div>
			</div>
			<?= $modalMenu ?>
        </div>
    </body>
</html>