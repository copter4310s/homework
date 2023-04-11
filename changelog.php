<?php
	include "./module/head.php";
?>

<html>
    <head>
        <!-- BOOTSTRAP -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">

        <link rel="stylesheet" href="main.css" />
        <script src="script.js"></script>

        <title>Homework</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=0.95">
		<style>
			.left1 {
				padding-left: 1rem;
			}
			
			.left2 {
				padding-left: 3rem;
			}
		</style>
    </head>
    <body>
        <div class="container-fluid pb-5">
            <nav class="navbar navbar-expand-sm bg-primary navbar-light fixed-top">
                <a onclick="javascript: $('#modalMenu').modal();"><img src="favicon.ico" class="navbar-brand angry-animate" alt="Logo" width="40" /></a> <span class="text-light navbar-text"><font size="4"><b>ระบบจัดเก็บข้อมูล 3</b></font></span>
            </nav>
            <br/>
            <div class="container-md pt-5">
				<div class="pt-2"></div>
				<font size="5"><b>Changelog</b></font>
				<hr class="mt-2 mb-2">
				<font size="4"><b>เวอร์ชั่น 1.0.1</b></font>
				<div class="left1"> - &nbsp;เริ่มพัฒนาวันที่ 17 สิงหาคม 2564</div>
				<div class="left1"> - &nbsp;สิ่งที่เพิ่ม</div>
				<div class="left2 mb-1">
					- &nbsp;เพิ่มการเปลี่ยนหน้าของการสรุปการบ้านแต่ละแบบ***<br>
				</div>
				<div class="left1"> - สิ่งที่แก้ไข</div>
				<div class="left2 mb-2">
					- &nbsp;แก้ไขสถานะการส่งไม่เปลี่ยนหลังจากแก้ไขข้อมูล (เฉพาะจากส่งช้า เป็น ส่งตรงเวลา)<br>
					- &nbsp;แก้ไขไม่สามารถแก้ไขข้อมูลได้ ที่ขึ้นว่า "เฉพาะคนที่เพิ่มการบ้านนี้เท่านั้นที่จะสามารถแก้ไขข้อมูลนี้ได้!"<br>
					- &nbsp;เปลี่ยนปุ่มเลือกหน้า***<br>
				</div>
				
				<font size="4"><b>เวอร์ชั่น 1.0.0 (วันที่ 14 สิงหาคม 2564)</b></font>
				<div class="left1"><b>The first release is here!</b></div>
				<div class="left1"> - &nbsp;เริ่มพัฒนาวันที่ 10 สิงหาคม 2564</div>
				<div class="left1"> - &nbsp;สิ่งที่เพิ่ม</div>
				<div class="left2 mb-1">
					- &nbsp;เพิ่มกำหนดส่งแบบไม่มีกำหนด<br>
					- &nbsp;เพิ่มการดูการบ้านจากช่วงเวลาที่สั่งและต้องส่ง ในหน้าการบ้านทั้งหมด<br>
					- &nbsp;เพิ่มหน้า Changelog<br>
					- &nbsp;เพิ่มหน้าลบข้อมูล<br>
				</div>
				<div class="left1"> - สิ่งที่แก้ไข</div>
				<div class="left2 mb-2">
					- &nbsp;แก้ไขการดูการบ้านจากสัปดาห์ที่สั่งผิดเพี้ยนไป<br>
					- &nbsp;แก้ไขการจัดเรียงการบ้านในหน้าสรุปการบ้าน<br>
					- &nbsp;แก้ไขการแสดงรายละเอียดแบบย่อให้มีตัวอักษรมากขึ้น จากเดิม 100 ตัวเป็น 150 ตัว<br>
					- &nbsp;แก้ไขหัวเว็บของหน้าการบ้านทั้งหมด โดยลบคำว่า "(ค้นหาแบบละเอียด)" ออก<br>
					- &nbsp;แก้ไขบัคเล็ก ๆ น้อย ๆ<br>
				</div>

				<font size="4"><b>เวอร์ชั่น 1.0.0 Beta (วันที่ 9 สิงหาคม 2564)</b></font>
				<div class="left1"> - &nbsp;เริ่มพัฒนาวันที่ 2 สิงหาคม 2564 </div>
            </div>
			<?= $modalMenu ?>
        </div>
    </body>
</html>