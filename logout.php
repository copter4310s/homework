<html>
    <head>
        <!-- BOOTSTRAP -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

        <link rel="stylesheet" href="main.css" />
        <script src="script.js"></script>

        <title>Homework</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>
        <div class="container-fluid">
            <nav class="navbar navbar-expand-sm bg-primary navbar-light fixed-top">
                <img src="favicon.ico" class="navbar-brand" alt="Logo" width="40" /> <span class="text-light navbar-text"><font size="4"><b>ระบบจัดเก็บข้อมูล 3</b></font></span>
            </nav>
            <br/>
            <div class="container-fluid">
                <div class="center p-4" style="margin-top: 30px; border: 1px solid rgb(224, 224, 224); box-shadow: 0px 0px 10px 1px #AAAAAA; display: inline-table;">
                    <center>
						<font size="5">
							<b>กำลังออกจากระบบ...</b>
						</font>
						<br>
						<font size="2">
							แล้วพบกันใหม่ :)
						</font>
						<br>
						<br>
						<img src="wheel.svg" width="48" height="48" />
					</center>
                </div>
            </div>
        </div>
    </body>
</html>

<?php
	include "./module/head.php";
	include "./module/sqllogin.php";
	
	usleep(50000); //SLEEP 50MS
	
	$token = $_COOKIE[$cookie];
	$expireTime = time() + $timeToExpire;
	setcookie($cookie, "", time() - 432000, "/");
	$sql_com = "DELETE FROM $sql_login WHERE token='$token' OR expireTime < $expireTime;";
	$sql_que = mysqli_query( $conn, $sql_com );
	
	usleep(50000); //SLEEP 50MS
	
	header("Location: index.php");
?>