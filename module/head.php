<?php
	date_default_timezone_set('Asia/Bangkok');
	//ini_set('display_errors', 1);
	error_reporting(0);

	$powder = base64_decode("bmFjLQ==");
	$cookie = "token";
	$listEachLine = 4;
	$listEachPage = 8;
	$viewListEachLine = 4;
	$viewListEachPage = 16;
	$viewDescriptionMax = 150;
	
	$topicMax = 35;
	$descriptionMax = 560;
	$nameMax = 30;
	$lastnameMax = 60;
	$passwordMin = 4;
	$passwordMax = 36;
	
	$onlyOwnerCanEdit = false;
	$onlyOwnerCanDelete = true;
	
	$timeStartThisWeek =  mktime(0, 0, 0, date("m", strtotime("sunday last week")), date("d", strtotime("sunday last week")), date("Y", strtotime("sunday last week")));
	$timeEndThisWeek = $timeStartThisWeek + 518400 + 86399;
	$timeStartLastWeek = mktime(0, 0, 0, date("m", strtotime("sunday last week -7days")), date("d", strtotime("sunday last week -7days")), date("Y", strtotime("sunday last week -7days")));
	$timeEndLastWeek = $timeStartLastWeek + 518400 + 86399;
	
	$modalMenu = '<div class="modal fade" id="modalMenu">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<font size="5" class="modal-title"><b id="info-topic">เมนู</b></font>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<div id="menu-account">
					<font size="4"><b>บัญชี</b></font>
					<table border="0" width="100%">
						<tr>
							<td width="50%">
								<a class="btn btn btn-primary w-100" href="account.php"><i class="fas fa-user-cog"></i> &nbsp;การตั้งค่าบัญชี</a>
							</td>
							<td width="50%">
								
							</td>
						</tr>
					</table>
				</div>
				<hr style="margin-bottom: 10px;">
				<div id="menu-navigation">
					<font size="4"><b>หน้า</b></font>
					<table border="0" width="100%">
						<tr>
							<td width="50%">
								<a class="btn btn btn-primary w-100" href="home.php"><i class="fas fa-home"></i> &nbsp;หน้าแรก</a>
							</td>
							<td width="50%">
								<a class="btn btn btn-primary w-100" href="myhomework.php"><i class="fas fa-calendar-day"></i> &nbsp;สรุปการบ้าน</a>
							</td>
						</tr>
						<tr>
							<td width="50%">
								<a class="btn btn btn-primary w-100" href="addhomework.php"><i class="fas fa-calendar-plus"></i> &nbsp;เพิ่มการบ้าน</a>
							</td>
							<td width="50%">
								<a class="btn btn btn-primary w-100" href="viewhomework.php"><i class="fas fa-calendar-alt"></i> &nbsp;การบ้านทั้งหมด</a>
							</td>
						</tr>
					</table>
				</div>
				<hr style="margin-bottom: 10px;">
				<div id="menu-other">
					<font size="4"><b>อื่น ๆ</b></font>
					<table border="0" width="100%">
						<tr>
							<td width="50%">
								<a class="btn btn btn-success w-100 disabled" href="about.php"><i class="fas fa-info-circle"></i> &nbsp;เกี่ยวกับเว็บไซต์นี้</a>
							</td>
							<td width="50%">
								<a class="btn btn btn-success w-100" href="changelog.php"><i class="fas fa-clipboard-list"></i> &nbsp;Changelog</a>
							</td>
						</tr>
						<tr>
							<td width="50%">
								<a class="btn btn btn-info w-100 disabled" href="guide.php"><i class="fas fa-book-reader"></i> &nbsp;วิธีใช้งาน</a>
							</td>
							<td width="50%">
							</td>
						</tr>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<small class="mr-auto">เวอร์ชั่น: 1.0.0</small>
				<a class="btn btn-secondary" onclick="showloading()" href="logout.php"><i class="fas fa-sign-out-alt"></i> &nbsp;ออกจากระบบ</a>
				<button type="button" class="btn btn-danger" data-dismiss="modal">ปิดเมนู</button>
			</div>
		</div>
	</div>
</div>';
?>