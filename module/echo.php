<?php
	include "./head.php";
	$getPassword = "123456";
	echo sha1( $powder . $getPassword );
?>