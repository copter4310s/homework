<?php
	$isLoggedIn = false;
	$studentId = 0;
	$studentNumber = 0;
	$studentName = "";
	$studentLastName = "";
	
	$timeToExpire = 432000; //5 DAYS
	
	if ( isset( $_POST["studentId"] ) && isset( $_POST["password"] ) ) {
		include "./head.php";
		$sql_passive = true;
		include "./sqllogin.php";
		
		$getId = (int) $_POST["studentId"];
		$getPassword = trim($_POST["password"]);
		$password = sha1( $powder . $getPassword );
		
		$sql_com = "SELECT * FROM $sql_account WHERE studentId = " . $getId . " AND password = '" . $password . "';";
		$sql_que = mysqli_query( $conn, $sql_com );
		
		if ($sql_que) {
			
			if ( mysqli_num_rows($sql_que) > 0 ) {
				//SET VARIABLES
				$row = mysqli_fetch_array($sql_que);
				$isLoggedIn = true;
				$studentId = $getId;
				$studentNumber = $row["studentNumber"];
				$studentName = $row["studentName"];
				$studentLastName = $row["studentLastName"];
				
				//SLEEP 10MS
				usleep(10000);
				
				//SET COOKIE
				$expireTime = ( time() + $timeToExpire );
				$cookieContent = sha1( $password . time() );
				setcookie($cookie, $cookieContent, $expireTime, "/");
				
				//SET LOGIN TOKEN
				$sql_com = "INSERT INTO $sql_login (expireTime, studentId, token, userAgent) VALUES (" . $expireTime . ", " . $studentId . ", '" . $cookieContent . "', '" . mysqli_real_escape_string( $conn, $_SERVER["HTTP_USER_AGENT"] ) . "')";
				$sql_que = mysqli_query( $conn, $sql_com );
				
				echo "ok";
			} else {
				echo "no";
			}
			
		} else {
			echo "Couldn't query the database.";
		}
	} else if ( isset( $_COOKIE[$cookie] ) || $checkCookie ) {
		$sql_com = "SELECT studentId FROM $sql_login WHERE token = '" . mysqli_real_escape_string( $conn, $_COOKIE[$cookie] ) . "';";
		$sql_que = mysqli_query( $conn, $sql_com );
		
		if ($sql_que) {
			
			if ( mysqli_num_rows($sql_que) > 0 ) {
				$row = mysqli_fetch_array($sql_que);
				$studentId= $row["studentId"];
				
				$sql_com = "SELECT studentNumber, studentName, studentLastName FROM $sql_account WHERE studentId = " . $studentId . ";";
				$sql_quer = mysqli_query( $conn, $sql_com );
				
				$rows = mysqli_fetch_array($sql_quer);
				$isLoggedIn = true;
				$studentNumber = $rows["studentNumber"];
				$studentName = $rows["studentName"];
				$studentLastName = $rows["studentLastName"];

				//SLEEP 10MS
				usleep(10000);
				
				//RENEW COOKIE
				$expireTime = ( time() + $timeToExpire );
				setcookie($cookie, $_COOKIE[$cookie], $expireTime, "/");
				$sql_com = "UPDATE $sql_login SET expireTime = "  . $expireTime . " WHERE studentId = " . $studentId . ";";
				$sql_quer = mysqli_query( $conn, $sql_com );
			} else {
				$isLoggedIn = false;
				setcookie($cookie, "", time() - 432000, "/");
				
				if (! isset( $checkCookie )) {
			        header("Location: index.php");
				}
			}
			
		} else {
			echo "Couldn't query the database.";
		}
	}
?>