<?php
	include "./module/head.php";
	include "./module/sqllogin.php";
	
	$checkCookie = TRUE;
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
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
		<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

        <link rel="stylesheet" href="main.css" />
        <script src="script.js"></script>
        <script src="./js/index.js"></script>

        <title>Homework</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=0.95">
    </head>
    <body>
        <div class="container-fluid">
            <nav class="navbar navbar-expand-sm bg-primary navbar-light fixed-top">
                <img src="favicon.ico" class="navbar-brand" alt="Logo" width="40" /> <span class="text-light navbar-text"><font size="4"><b>ระบบจัดเก็บข้อมูล 3</b></font></span>
            </nav>
            <br/>
            <div class="container-fluid">
                <div class="center p-4" style="margin-top: 30px; border: 1px solid rgb(224, 224, 224); box-shadow: 0px 0px 10px 1px #AAAAAA; display: inline-table;">
                    <div>
                        <center><font size="5"><b>เข้าสู่ระบบ</b></font></center>
                    </div>
                    <div style="padding: 8px;"></div>
                    <div class="container-bg">
                        <form style="margin-block-end: 0;" action="home.php" method="POST" id="formLogin">
                            <div>
                                ชื่อผู้ใช้: 
                            </div>
                            <div>
                                <div class="mb-3">
                                    <select name="studentId" id="studentId" class="selectpicker" data-size="10" data-width="100%" data-live-search="true">
										<?php
											$sql_comm = "SELECT studentNumber, studentId, studentName FROM $sql_account;";
											$sql_query = mysqli_query($conn, $sql_comm);
											$returnlist = "";
											
											while($row = mysqli_fetch_array($sql_query)) {
												$returnlist .= "<option value=\"" . $row["studentId"] . "\">" . $row["studentNumber"] . " " . $row["studentName"] . "</option>\n";
											}
											echo $returnlist;
										?>
									</select>
                                </div>
                            </div>
                            <div>
                                รหัสผ่าน: 
                            </div>
                            <div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-key"></i></span>
                                    </div>
                                    <input type="password" id="password" name="password" class="form-control">
                                </div>
                            </div>
                            <div>
                                <center>
                                    <button type="button" id="btnLogin" onclick="goLogin()" class="btn btn-primary">
                                        <span class="spinner-border" id="btnLogin-spinner" style="display: none;"></span>
                                        <span id="btnLogin-text">เข้าสู่ระบบ</span>
                                    </button>
                                </center>
                            </div>
                        </form>
                    </div>
                </div>
				<form method="POST" action="home.php" style="margin: 0;" id="formLogin">
					<input type="hidden" name="chooselist" id="chooselist" value="" />
				</form>
            </div>
			<div class="modal fade" id="modalWrong">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<font size="5" class="modal-title"><b>แจ้งเตือน</b></font>
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>
						<div class="modal-body">
							<font size="3"><span id="modalWarningContent"></span></font>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">ปิดข้อความ</button>
						</div>
					</div>
				</div>
			</div>
			<div class="blockall" id="loading" style="display:none;">
				<div class="center">
				<img src="wheel.svg" width="48" height="48" />
				</div>
			</div>
        </div>
    </body>
</html>

<?php
    if ( $isLoggedIn ) {
        echo '<script>offUI(); setTimeout(function(){ window.location.href = "home.php"; }, 500);</script>';
    }
?>