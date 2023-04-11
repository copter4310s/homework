<?php
	include "./module/head.php";
	include "./module/sqllogin.php";
	include "./module/login.php";
	
	usleep(50000); //SLEEP 50MS
	
	if ( isset( $_POST["do"] ) && $isLoggedIn ) {
		$subjectId = trim( urldecode( $_POST["subjectId"] ) );
		$assignDate = (int) trim( urldecode( $_POST["assignDate"] ) );
		$topic = trim( urldecode( $_POST["topic"] ) );
		$description = trim( urldecode( $_POST["description"] ) );
		$dueDate = (int) trim( urldecode( $_POST["dueDate"] ) );

		$addDate = time();
		
		if (mb_strlen($topic, "UTF-8") <= $topicMax && mb_strlen($description, "UTF-8") <= $descriptionMax) {
			$subjectId = mysqli_real_escape_string( $conn, $subjectId );
			$topic = mysqli_real_escape_string( $conn, $topic );
			$description = mysqli_real_escape_string( $conn, $description );

			$sql_com = "INSERT INTO $sql_list(subjectId, addDate, studentId, assignDate, topic, description, dueDate) VALUES ('$subjectId', $addDate, $studentId, '$assignDate', '$topic', '$description', $dueDate)";
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
			const topicMax = <?= $topicMax; ?>;
			const descriptionMax = <?= $descriptionMax; ?>;
		</script>
        <script src="script.js"></script>
		<script src="./js/addhomework.js"></script>

        <title>Homework</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=0.88">
    </head>
    <body onload="afterLoad(); hideloading(); hideNext(); setDate()">
        <div class="container-fluid pb-5">
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
						<b><font size="5">เพิ่มการบ้าน</font></b><br>
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
										วิชา
									</td>
									<td>
										<center>
											<div style="width: 86%;">
												<select id="subjectId" class="selectpicker" data-size="10" data-width="100%" data-live-search="true">
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
											</div>
										</center>
									</td>
								</tr>
								<tr>
									<td>
										วันที่สั่ง
									</td>
									<td>
										<center>
											<div style="width: 86%;">
												<input type="datetime-local" id="assignDate" class="form-control">
											</div>
										</center>
									</td>
								</tr>
								<tr>
									<td>
										หัวข้อ
									</td>
									<td>
										<center>
											<div style="width: 86%;">
												<input type="text" id="topic" class="form-control" maxlength="<?= $topicMax ?>">
											</div>
										</center>
									</td>
								</tr>
								<tr>
									<td>
										รายละเอียด
									</td>
									<td>
										<center>
											<div style="width: 86%;">
												<center><textarea id="description" class="form-control" rows="5" maxlength="<?= $descriptionMax ?>"></textarea>
											</div>
										</center>
									</td>
								</tr>
								<tr>
									<td>
										กำหนดส่ง
									</td>
									<td>
										<center>
											<div style="width: 86%;">
												<center><input type="datetime-local" id="dueDate" class="form-control">
											</div>
											<div class="custom-control custom-checkbox mt-2" style="width: 86%;">
												<input type="checkbox" id="noDueDate" class="custom-control-input" onclick="changenoDueDate()" value="yes">
												<label class="custom-control-label" for="noDueDate"> ไม่มี/ไม่ได้ระบุกำหนดส่ง
											</div>
										</center>
									</td>
								</tr>
							</tbody>
						</table>
						<div>
							<button class="btn btn-primary" id="btn1" onclick="checkInfo()">ถัดไป</button>
							<button class="btn btn-danger" id="btn2" onclick="doAdd()">บันทึก</button>
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
							<font size="3">เพิ่มการบ้านเรียบร้อยแล้ว</font>
						</div>
						<div class="modal-footer">
							<button class="btn btn-sm btn-success" onclick="clearInfo()" data-dismiss="modal">ปิดข้อความ</button>
							<button class="btn btn-sm btn-success" onclick="go(-1)" data-dismiss="modal">กลับไปหน้าที่แล้ว</button>
						</div>
					</div>
				</div>
			</div>
			<?= $modalMenu ?>
        </div>
    </body>
</html>