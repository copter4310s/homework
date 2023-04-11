<?php
	include "./module/head.php";
	include "./module/sqllogin.php";
	include "./module/login.php";
	
	usleep(5000); //SLEEP 5MS
	
	$outputOwnerA = ' display: none;';
	$outputOwnerB = '';
	
	if ( isset( $_POST["do"] ) && $isLoggedIn ) {
		$sql_com = "SELECT studentId FROM $sql_list WHERE hwid = $hwid";
		$sql_que = mysqli_query( $conn, $sql_com );
		$sql_res = mysqli_fetch_array( $sql_que );
		
		if ($onlyOwnerCanEdit && $sql_res["studentId"] != $studentId) {
			$outputOwnerA = ' display: inline-table;';
			$outputOwnerB = ' display: none;';
			echo "error0";
		} else {
		
			$hwid = (int) $_POST["hwid"];
			$subjectId = trim(urldecode($_POST["subjectId"]));
			$assignDate = (int) trim($_POST["assignDate"]);
			$topic = trim(urldecode($_POST["topic"]));
			$description = trim(urldecode($_POST["description"]));
			$dueDate = (int) trim(urldecode($_POST["dueDate"]));
			
			if (mb_strlen($topic, "UTF-8") <= $topicMax && mb_strlen($description, "UTF-8") <= $descriptionMax) {
				$subjectId = mysqli_real_escape_string( $conn, $subjectId );
				$topic = mysqli_real_escape_string( $conn, $topic );
				$description = mysqli_real_escape_string( $conn, $description );
				
				$sql_com = "UPDATE $sql_list SET subjectId = '$subjectId', addDate = " . time() . ", studentId = $studentId, assignDate = $assignDate, topic = '$topic', description = '$description', dueDate = $dueDate WHERE hwid = $hwid";
				$sql_que = mysqli_query( $conn, $sql_com );
				
				usleep(5000); //SLEEP 5MS
				
				if ($dueDate != 1) {
					$sql_com = "UPDATE $sql_progress SET progress = 2 WHERE hwid = $hwid AND lastModifiedDate > $dueDate";
					$sql_que = mysqli_query( $conn, $sql_com );
					
					$sql_com = "UPDATE nac_hw_progress SET progress = 1 WHERE hwid = $hwid AND lastModifiedDate <= $dueDate";
					$sql_que = mysqli_query( $conn, $sql_com );
				} else {
					$sql_com = "UPDATE $sql_progress SET progress = 1 WHERE hwid = $hwid AND progress = 2";
					$sql_que = mysqli_query( $conn, $sql_com );
				}
				
				if ($sql_com) {
					echo "ok";
				} else {
					echo "Couldn't query the database.";
				}
				echo $conn->error;
			} else {
				echo "error1";
			}
			
		}
		
		exit();
	} else if ( isset( $_POST["hwid"] ) && $isLoggedIn ) {
		
		$hwid = (int) $_POST["hwid"];
		$sql_com = "SELECT * FROM $sql_list WHERE hwid = $hwid";
		$sql_que = mysqli_query( $conn, $sql_com );
		$sql_res = mysqli_fetch_array( $sql_que );
		
		$outputAssignDate = date("Y-m-d\TH:i", $sql_res["assignDate"]);
		$outputDueDate = "";
		$noDueDate = "";
		if ($sql_res["dueDate"] != 1) {
			$outputDueDate = date("Y-m-d\TH:i", $sql_res["dueDate"]);
		} else {
			$outputDueDate = date("Y-m-d\TH:i", time());
			$noDueDate = " checked";
		}
		
		if ($onlyOwnerCanEdit && $sql_res["studentId"] != $studentId) {
			$outputOwnerA = ' display: inline-table;';
			$outputOwnerB = ' style="display: none;"';
		}
		
	} else {
		http_response_code(401);
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
		<script src="./js/edit.js"></script>

        <title>Homework</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=0.88">
    </head>
    <body onload="afterLoad(); hideloading(); hideNext(); changenoDueDate()">
        <div class="container-fluid">
            <nav class="navbar navbar-expand-sm bg-primary navbar-light fixed-top">
                <img src="favicon.ico" class="navbar-brand" alt="Logo" width="40" /> <span class="text-light navbar-text"><font size="4"><b>ระบบจัดเก็บข้อมูล 3</b></font></span>
            </nav>
			<div class="blockall" id="loading">
				<div class="center">
					<img src="wheel.svg" width="48" height="48" />
				</div>
			</div>
            <br/>
			<div class="center p-4" style="margin-top: 30px; border: 1px solid rgb(224, 224, 224); box-shadow: 0px 0px 10px 1px #AAAAAA;<?= $outputOwnerA ?>">
				<div>
					<center><font size="5"><b>แจ้งเตือน</b></font></center>
				</div>
				<div style="padding: 8px;"></div>
				<div class="container-bg">
					<center>
						เฉพาะคนที่เพิ่มการบ้านนี้เท่านั้นที่จะสามารถแก้ไขข้อมูลนี้ได้!
						<div class="p-2"></div>
						<button class="btn btn-primary" onclick="javascript: go(-1); this.disabled=true;">กลับไปหน้าที่แล้ว</button>
					</center>
				</div>
			</div>
            <div class="container-md pt-5"<?= $outputOwnerB ?>>
				<center>
					<div>
						<b><font size="5">แก้ไขข้อมูล</font></b><br>
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
												<input type="datetime-local" id="assignDate" class="form-control" value="<?= $outputAssignDate ?>">
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
												<input type="text" id="topic" class="form-control" maxlength="<?= $topicMax; ?>" value="<?= $sql_res["topic"]; ?>">
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
												<center><textarea id="description" class="form-control" rows="5" maxlength="<?= $descriptionMax; ?>"><?= $sql_res["description"]; ?></textarea>
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
												<center><input type="datetime-local" id="dueDate" class="form-control" value="<?= $outputDueDate ?>">
											</div>
											<div class="custom-control custom-checkbox mt-2" style="width: 86%;">
												<input type="checkbox" id="noDueDate" class="custom-control-input" onclick="changenoDueDate()" value="yes"<?= $noDueDate ?>>
												<label class="custom-control-label" for="noDueDate"> ไม่มี/ไม่ได้ระบุกำหนดส่ง
											</div>
										</center>
									</td>
								</tr>
							</tbody>
						</table>
						<input type="hidden" id="hwid" value="<?= $_POST["hwid"]; ?>">
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
							<button class="btn btn-sm btn-success" onclick="go(-1)" data-dismiss="modal">กลับไปหน้าที่แล้ว</button>
						</div>
					</div>
				</div>
			</div>
        </div>
    </body>
	<script>
		document.getElementById("subjectId").value = "<?= $sql_res["subjectId"]; ?>";
	</script>
</html>