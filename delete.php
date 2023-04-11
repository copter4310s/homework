<?php
	include "./module/head.php";
	include "./module/sqllogin.php";
	include "./module/login.php";
	
	usleep(5000); //SLEEP 5MS
	
	$outputOwnerA = ' display: none;';
	$outputOwnerB = ' display: inline-table;';
	$hwid = (int) $_POST["hwid"];
	
	if ( isset( $_POST["do"] ) && $isLoggedIn ) {
		
		$sql_com = "SELECT studentId FROM $sql_list WHERE hwid = $hwid";
		$sql_que = mysqli_query( $conn, $sql_com );
		$sql_res = mysqli_fetch_array( $sql_que );
		
		if ($onlyOwnerCanDelete && $sql_res["studentId"] != $studentId) {
			$outputOwnerA = ' display: inline-table;';
			$outputOwnerB = ' display: none;';
			echo "error0";
		} else {
			usleep(10000); //SLEEP 10MS
			
			$sql_com = "DELETE FROM $sql_list WHERE hwid = $hwid";
			$sql_que = mysqli_query( $conn, $sql_com );
			
			if ( ! $conn->error ) {
				echo "ok";
			} else {
				echo $conn->error;
			}
		
		}
		
		exit();
	} else if ( isset( $_POST["hwid"] ) && $isLoggedIn ) {
		
		$sql_com = "SELECT topic, studentId FROM $sql_list WHERE hwid = $hwid";
		$sql_que = mysqli_query( $conn, $sql_com );
		$sql_res = mysqli_fetch_array( $sql_que );
		
		if ($onlyOwnerCanDelete && $sql_res["studentId"] != $studentId) {
			$outputOwnerA = ' display: inline-table;';
			$outputOwnerB = ' display: none;';
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
        <script src="script.js"></script>
		<script src="./js/delete.js"></script>

        <title>Homework</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=0.95">
    </head>
    <body onload="afterLoad()">
        <div class="container-fluid">
            <nav class="navbar navbar-expand-sm bg-primary navbar-light fixed-top">
                <img src="favicon.ico" class="navbar-brand" alt="Logo" width="40" /> <span class="text-light navbar-text"><font size="4"><b>ระบบจัดเก็บข้อมูล 3</b></font></span>
            </nav>
			<div class="center p-4" style="margin-top: 30px; border: 1px solid rgb(224, 224, 224); box-shadow: 0px 0px 10px 1px #AAAAAA;<?= $outputOwnerA ?>">
				<div>
					<center><font size="5"><b>แจ้งเตือน</b></font></center>
				</div>
				<div style="padding: 8px;"></div>
				<div class="container-bg">
					<center>
						เฉพาะคนที่เพิ่มการบ้านนี้เท่านั้นที่จะสามารถลบข้อมูลนี้ได้!
						<div class="p-2"></div>
						<button class="btn btn-primary" onclick="javascript: go(-1); this.disabled=true;">กลับไปหน้าที่แล้ว</button>
					</center>
				</div>
			</div>
			<div class="center p-4" style="margin-top: 30px; border: 1px solid rgb(224, 224, 224); box-shadow: 0px 0px 10px 1px #AAAAAA;<?= $outputOwnerB ?>">
				<div>
					<center><font size="5"><b>ลบข้อมูล</b></font></center>
				</div>
				<div style="padding: 8px;"></div>
				<div class="container-bg">
					<center>
						แน่ใจไหมว่าต้องการลบการบ้านนี้ไหม?<br>
						"<?= $sql_res["topic"] ?>"
						<div class="pt-1">
							<small>ไม่สามารถกู้คืนได้นะ!</small>
						</div>
						<div class="p-2"></div>
						<input type="hidden" id="hwid" value="<?= $_POST["hwid"]; ?>">
						<div>
							<button class="btn btn-danger" id="btn2" onclick="doDelete()">ลบข้อมูล</button>
							<button class="btn btn-success" id="btn3" onclick="go(-1)">ยกเลิก</button>
						</div>
					</center>
					<input type="hidden" id="hwid" value="<?= $_POST["hwid"]; ?>">
				</div>
			</div>
			<div class="modal fade" id="modalSuccess" data-backdrop="static" data-keyboard="false">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<font size="5" class="modal-title"><b>แจ้งเตือน</b></font>
						</div>
						<div class="modal-body">
							<font size="3">ลบข้อมูลข้อมูลเรียบร้อยแล้ว</font>
						</div>
						<div class="modal-footer">
							<button class="btn btn-sm btn-success" onclick="go(-1)" data-dismiss="modal">กลับไปหน้าที่แล้ว</button>
						</div>
					</div>
				</div>
			</div>
			<div class="blockall" id="loading" style="display: none;">
				<div class="center">
					<img src="wheel.svg" width="48" height="48" />
				</div>
			</div>
        </div>
    </body>
	<script>
		document.getElementById("subjectId").value = "<?= $sql_res["subjectId"]; ?>";
	</script>
</html>