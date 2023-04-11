<?php
	if (! isset( $sql_passive )) {
		$sql_decodeconnectiondetails = base64_decode( file_get_contents("./module/dbpassword.txt") );
	} else {
		$sql_decodeconnectiondetails = base64_decode( file_get_contents("./dbpassword.txt") );
	}

    $sql_currentLine = 1;
    $sql_details = explode("\n", $sql_decodeconnectiondetails);

    foreach($sql_details as $sql_detailperline) {    
        if ($sql_currentLine == 1) {
            $sql_servername = base64_decode( base64_decode( $sql_detailperline ) );
        } else if ($sql_currentLine == 2) {
            $sql_username = base64_decode( base64_decode( $sql_detailperline ) );
        } else if ($sql_currentLine == 3) {
            $sql_password = base64_decode(  base64_decode( base64_decode( $sql_detailperline ) ) );
        } else if ($sql_currentLine == 4) {
            $sql_dbname = base64_decode( base64_decode( $sql_detailperline ) );
        }
        
        $sql_currentLine += 1;
    }
	
	$conn = new mysqli($sql_servername, $sql_username, $sql_password, $sql_dbname);
	
	if ($conn->connect_error) {
		echo "Couldn't connect to database.";
	}
	
	$sql_account = "nac_hw_account";
	$sql_list = "nac_hw_list";
	$sql_login = "nac_hw_login";
	$sql_progress = "nac_hw_progress";
	$sql_subjectList = "nac_hw_subjectList";
?>