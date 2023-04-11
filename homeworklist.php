<?php
	include "./module/head.php";
	include "./module/sqllogin.php";
	include "./module/login.php";

	if ( $isLoggedIn && isset( $_POST["mode"] ) ) {
		if ( $_POST["mode"] == "basic" ) {
			//FIND ALL
			$sql_com = "SELECT COUNT(hwid) AS total FROM $sql_list;";
			$sql_que = mysqli_query( $conn, $sql_com );
			$sql_result = mysqli_fetch_assoc($sql_que);
			$totalList = $sql_result["total"];
			$totalPage = ceil($totalList / $listEachPage);
			$totalLine = ceil($listEachPage / $listEachLine);
			$breakAfterList = [];
			for ($i=1;$i<=$totalLine;$i++) {
				array_push($breakAfterList, ($listEachLine * $i));
			}
			
			$selectPage = (int) $_POST["page"];
			$selectPage = ($listEachPage * $selectPage) - $listEachPage;
			
			usleep(10000); //SLEEP 10MS
			
			$sql_com = "SELECT * FROM $sql_list AS d1 LEFT JOIN $sql_subjectList AS d3 ON (d1.subjectId=d3.subjectId) ORDER BY d1.hwid DESC LIMIT $selectPage,$listEachPage;";
			$sql_que = mysqli_query( $conn, $sql_com );
			$currentList = 1;
			$outputList = "";
			$description = "";
			
			if (mysqli_num_rows($sql_que) == 0) {
				$outputList = "0\nยังไม่มีการบ้านเลย... กดปุ่มเพิ่มการบ้านดูสิ";
			}
			
			while ($sql_result = mysqli_fetch_array($sql_que)) {
				if ($currentList == 1) {
					$outputList = $totalPage . "\n" . '<div class="card-deck mb-3">';
				}
				
				if (($currentList - 1) == $breakAfterList[0]) {
					$outputList .= '</div>
						<div class="card-deck mb-3">';
					array_shift($breakAfterList);
				}
				
				$dueDate = "";
				if ($sql_result["dueDate"] == 1) {
					$dueDate = "ไม่ได้กำหนด";
				} else {
					$dueDate = date("d/m/Y H:i น.", $sql_result["dueDate"] );
				}
				
				$description = $sql_result["description"];
				if (mb_strlen( $description, "UTF-8" ) > $viewDescriptionMax) {
					$description = mb_substr($sql_result["description"], 0, $viewDescriptionMax, "UTF-8") . "...";
				}
				
				$sql_coms = "SELECT progress,lastModifiedDate FROM $sql_progress WHERE hwid = " . $sql_result["hwid"] . " AND studentId = $studentId;";
					$sql_ques = mysqli_query( $conn, $sql_coms );
					$sql_res = mysqli_fetch_array($sql_ques);
					
					$isSent = '<i class="fas fa-times"></i> ยังไม่ได้ส่ง';
					$ocolor = "danger";
					
					if ( mysqli_num_rows($sql_ques) > 0 ) {
						if ($sql_res["progress"] == 1) {
							//IN-time
							$isSent = '<i class="fas fa-check"></i> ส่งแล้วเมื่อ ' . date("d/m/Y H:i น.", $sql_res["lastModifiedDate"]);
							$ocolor = "success";							
						} else if ($sql_res["progress"] == 2) {
							//LATE
							$isSent = '<i class="fas fa-check"></i> ส่งแล้วแต่ช้าเมื่อ ' . date("d/m/Y H:i น.", $sql_res["lastModifiedDate"]);
							$ocolor = "warning";
						}
					} else {
						
					}
					
					usleep(5000); //SLEEP 5MS
				
				$outputList .= '<div class="card border-' . $ocolor . '">
									<img class="card-img-top" src="' . $sql_result["subjectImg"] . '" loading="lazy" alt="' . $sql_result["subjectName"] . '">
									<div class="card-body">
										<h5 class="card-title text-' . $ocolor . '"><b>' . $sql_result["topic"] . '</b></h5>
										<p class="card-text">
											' . $description . '
										</p>
										<button class="btn btn-sm btn-' . $ocolor . ' text-light" onclick="loadInfo(this.id)" id="' . $sql_result["hwid"] . '" style="float: right;">เพิ่มเติม</button>
										<p class="card-text">
											<small class="text-muted">
												<i class="fas fa-book-reader"></i> วิชา: ' . $sql_result["subjectName"] .  '<br>
												<i class="fas fa-clock"></i> กำหนดส่ง: ' . $dueDate .  '<br>
												' . $isSent . '
											</small>
										</p>
									</div>
								</div>';
							
				$currentList += 1;
			}
			
			$outputList .= "</div><div class='mt-2'><small>ทั้งหมด $totalList รายการ</small></div>";
			
			echo $outputList;
			
		} else if ( $_POST["mode"] == "unsend" ) {
			
			$leftTotal = 0;
			$allegedly = 0;
			$today = 0;
			$tomorrow = 0;
			$future = 0;
			
			//FIND ALL
			$sql_com = "SELECT dueDate FROM $sql_list WHERE NOT EXISTS (SELECT 1 FROM $sql_progress WHERE $sql_list.hwid=$sql_progress.hwid AND $sql_progress.progress!=0 AND $sql_progress.studentId = $studentId)";
			$sql_que = mysqli_query( $conn, $sql_com );
			
			$currentTime = time();
			$todayAtMidnight = mktime(23, 59, 59, date("m"), date("d"), date("Y"));
			$tomorrowAtMidnight = ($todayAtMidnight + 86400);
			
			while ( $sql_result = mysqli_fetch_array($sql_que) ) {
				if ( $sql_result["dueDate"] < $currentTime && $sql_result["dueDate"] != 1) {
					$allegedly += 1;
				} else if ( $sql_result["dueDate"] < $todayAtMidnight && $sql_result["dueDate"] != 1 ) {
					$today += 1;
				} else if ( $sql_result["dueDate"] < $tomorrowAtMidnight && $sql_result["dueDate"] != 1 ) {
					$tomorrow += 1;
				} else {
					$future += 1;
				}
				
				$leftTotal += 1;
			}
						
			usleep(10000); //10MS
				
			//IN-TIME
			$sql_coms = "SELECT COUNT(hwid) AS total FROM $sql_progress WHERE progress = 1 AND studentId = $studentId";
			$sql_ques = mysqli_query( $conn, $sql_coms );
			$sql_res = mysqli_fetch_assoc($sql_ques);
			$intime = $sql_res["total"];
			
			//LATE
			$sql_coms = "SELECT COUNT(hwid) AS total FROM $sql_progress WHERE progress = 2 AND studentId = $studentId";
			$sql_ques = mysqli_query( $conn, $sql_coms );
			$sql_res = mysqli_fetch_assoc($sql_ques);
			$late = $sql_res["total"];
			
			$sendTotal = $late + $intime;
			$total = $sendTotal + $leftTotal;
			
			$sendPercent = number_format($sendTotal/$total, 2, '.', '') * 100;
			if ($total != 0) {
				$leftPercent = 100 - $sendPercent;
			} else {
				$leftPercent = 0;
			}
			
			$intimePercent = number_format($intime/$sendTotal, 2, '.', '') * 100;
			if ($sendTotal != 0) {
				$latePercent = 100 - $intimePercent;
			} else {
				$latePercent = 0;
			}
			
			$allegedlyPercent = number_format($allegedly/$leftTotal, 2, '.', '') * 100;
			$todayPercent = number_format($today/$leftTotal, 2, '.', '') * 100;
			$tomorrowPercent = number_format($tomorrow/$leftTotal, 2, '.', '') * 100;
			$futurePercent = 100 - $allegedlyPercent - $todayPercent - $tomorrowPercent;
			if ($leftTotal != 0) {
				$futurePercent = 100 - $allegedlyPercent - $todayPercent - $tomorrowPercent;
			} else {
				$futurePercent = 0;
			}
			
			$sendTotal .= " (" . $sendPercent . "%)";
			$leftTotal .= " (" . $leftPercent . "%)";
			$intime .= " (" . $intimePercent . "%)";
			$late .= " (" . $latePercent . "%)";
			$allegedly .= " (" . $allegedlyPercent . "%)";
			$today .= " (" . $todayPercent . "%)";
			$tomorrow .= " (" . $tomorrowPercent . "%)";
			$future .= " (" . $futurePercent . "%)";
			
			$outputArray = ["total" => $total, "sendTotal" => $sendTotal, "intime" => $intime, "late" => $late, "leftTotal" => $leftTotal, "allegedly" => $allegedly, "today" => $today, "tomorrow" => $tomorrow, "future" => $future];
			$outputJson = json_encode($outputArray);
			$outputArray = null;
			echo $outputJson;
			
		} else if ( $_POST["mode"] == "myhomework" ) {
			//FIND ALL
			$leftTotal = 0;
			$allegedly = 0;
			$today = 0;
			$tomorrow = 0;
			$future = 0;
			
			$allegedlyList = '<div class="card-deck mb-3">';
			$todayList = '<div class="card-deck mb-3">';
			$tomorrowList = '<div class="card-deck mb-3">';
			$futureList = '<div class="card-deck mb-3">';
			
			//FIND ALL
			$sql_com = "SELECT d1.*,d2.studentName,d2.studentLastName,d3.subjectName,d3.subjectImg,d3.subjectId FROM $sql_list AS d1 LEFT JOIN $sql_account AS d2 ON (d1.studentId=d2.studentId) LEFT JOIN $sql_subjectList AS d3 ON (d1.subjectId=d3.subjectId) WHERE NOT EXISTS (SELECT 1 FROM $sql_progress WHERE d1.hwid=$sql_progress.hwid AND $sql_progress.progress!=0 AND $sql_progress.studentId = $studentId) ORDER BY d1.hwid DESC;";
			$sql_que = mysqli_query( $conn, $sql_com );
			
			$currentTime = time();
			$todayAtMidnight = mktime(23, 59, 59, date("m"), date("d"), date("Y"));
			$tomorrowAtMidnight = ($todayAtMidnight + 86400);
			
			usleep(10000); //SLEEP 10MS
			
			while ( $sql_result = mysqli_fetch_array($sql_que) ) {
				$description = $sql_result["description"];
				if (mb_strlen( $description, "UTF-8" ) > $viewDescriptionMax) {
					$description = mb_substr($sql_result["description"], 0, $viewDescriptionMax, "UTF-8") . "...";
				}
				$dueDate = "";
				if ($sql_result["dueDate"] == 1) {
					$dueDate = "ไม่ได้กำหนด";
				} else {
					$dueDate = date("d/m/Y H:i น.", $sql_result["dueDate"] );
				}
				
				
				if ( $sql_result["dueDate"] < $currentTime && $sql_result["dueDate"] != 1 ) {
					$allegedly += 1;
					
					if ($allegedly <= $listEachLine) {
						$allegedlyList .= '<div class="card border-danger">
									<img class="card-img-top" src="' . $sql_result["subjectImg"] . '" loading="lazy" alt="' . $sql_result["subjectName"] . '">
									<div class="card-body">
										<h5 class="card-title text-danger"><b>' . $sql_result["topic"] . '</b></h5>
										<p class="card-text">
											' . $description . '
										</p>
										<button class="btn btn-sm btn-danger" onclick="loadInfo(this.id)" id="' . $sql_result["hwid"] . '" style="float: right;">เพิ่มเติม</button>
										<p class="card-text">
											<small class="text-muted">
												<i class="fas fa-book-reader"></i> วิชา: ' . $sql_result["subjectName"] .  '<br>
												<i class="fas fa-clock"></i> กำหนดส่ง: ' . $dueDate .  '
											</small>
										</p>
									</div>
								</div>';
					}
				} else if ( $sql_result["dueDate"] < $todayAtMidnight && $sql_result["dueDate"] != 1 ) {
					$today += 1;
					
					if ($today <= $listEachLine) {
						$todayList .= '<div class="card border-warning">
									<img class="card-img-top" src="' . $sql_result["subjectImg"] . '" loading="lazy" alt="' . $sql_result["subjectName"] . '">
									<div class="card-body">
										<h5 class="card-title text-warning"><b>' . $sql_result["topic"] . '</b></h5>
										<p class="card-text">
											' . $description . '
										</p>
										<button class="btn btn-sm btn-warning text-light" onclick="loadInfo(this.id)" id="' . $sql_result["hwid"] . '" style="float: right;">เพิ่มเติม</button>
										<p class="card-text">
											<small class="text-muted">
												<i class="fas fa-book-reader"></i> วิชา: ' . $sql_result["subjectName"] .  '<br>
												<i class="fas fa-clock"></i> กำหนดส่ง: ' . $dueDate .  '
											</small>
										</p>
									</div>
								</div>';
					}
				} else if ( $sql_result["dueDate"] < $tomorrowAtMidnight && $sql_result["dueDate"] != 1 ) {
					$tomorrow += 1;
					
					if ($tomorrow <= $listEachLine) {
						$tomorrowList .= '<div class="card border-primary">
									<img class="card-img-top" src="' . $sql_result["subjectImg"] . '" loading="lazy" alt="' . $sql_result["subjectName"] . '">
									<div class="card-body">
										<h5 class="card-title text-primary"><b>' . $sql_result["topic"] . '</b></h5>
										<p class="card-text">
											' . $description . '
										</p>
										<button class="btn btn-sm btn-primary" onclick="loadInfo(this.id)" id="' . $sql_result["hwid"] . '" style="float: right;">เพิ่มเติม</button>
										<p class="card-text">
											<small class="text-muted">
												<i class="fas fa-book-reader"></i> วิชา: ' . $sql_result["subjectName"] .  '<br>
												<i class="fas fa-clock"></i> กำหนดส่ง: ' . $dueDate .  '
											</small>
										</p>
									</div>
								</div>';
					}
				} else {
					$future += 1;
					
					if ($future <= $listEachLine) {
					$futureList .= '<div class="card border-success">
									<img class="card-img-top" src="' . $sql_result["subjectImg"] . '" loading="lazy" alt="' . $sql_result["subjectName"] . '">
									<div class="card-body">
										<h5 class="card-title text-success"><b>' . $sql_result["topic"] . '</b></h5>
										<p class="card-text">
											' . $description . '
										</p>
										<button class="btn btn-sm btn-success" onclick="loadInfo(this.id)" id="' . $sql_result["hwid"] . '" style="float: right;">เพิ่มเติม</button>
										<p class="card-text">
											<small class="text-muted">
												<i class="fas fa-book-reader"></i> วิชา: ' . $sql_result["subjectName"] .  '<br>
												<i class="fas fa-clock"></i> กำหนดส่ง: ' . $dueDate .  '
											</small>
										</p>
									</div>
								</div>';
					}
								
				}
				
				$leftTotal += 1;
			}
			
			$allegedlyList .= "\n</div>";
			$todayList .= "\n</div>";
			$tomorrowList .= "\n</div>";
			$futureList .= "\n</div>";
			
			$outputList = [];
			
			usleep(10000); //SLEEP 10MS

			if (mysqli_num_rows($sql_que) == 0) {
				$outputList = ["allegedly" => [0, "ดีจัง ยังไม่มีการบ้านที่ยังไม่ได้ทำแล้วเลยกำหนดแล้ว"], "today" => [0, "ดีจัง ยังไม่มีการบ้านที่ยังไม่ได้ทำแล้วต้องส่งวันนี้"], "tomorrow" => [0, "ดีจัง ยังไม่มีการบ้านที่ยังไม่ได้ทำแล้วต้องส่งพรุ่งนี้"], "future" => [0, "ดีจัง ยังไม่มีการบ้านที่ยังไม่ได้ทำแล้วต้องส่งเร็ว ๆ นี้"]];
			} else {
				//ALLEGEDLY
				if ($allegedly == 0) {
					$outputList["allegedly"] = [0, "ดีจัง ยังไม่มีการบ้านที่ยังไม่ได้ทำแล้วเลยกำหนดแล้ว"];
				} else {
					$outputList["allegedly"] = [$allegedly, $allegedlyList];
				}
				
				//TODAY
				if ($today == 0) {
					$outputList["today"] = [0, "ดีจัง ยังไม่มีการบ้านที่ยังไม่ได้ทำแล้วต้องส่งวันนี้"];
				} else {
					$outputList["today"] = [$today, $todayList];
				}
				
				//TOMORROW
				if ($tomorrow == 0) {
					$outputList["tomorrow"] = [0, "ดีจัง ยังไม่มีการบ้านที่ยังไม่ได้ทำแล้วต้องส่งพรุ่งนี้"];
				} else {
					$outputList["tomorrow"] = [$tomorrow, $tomorrowList];
				}
				
				//FUTURE
				if ($future == 0) {
					$outputList["future"] = [0, "ดีจัง ยังไม่มีการบ้านที่ยังไม่ได้ทำแล้วต้องส่งเร็ว ๆ นี้"];
				} else {
					$outputList["future"] = [$future, $futureList];
				}
			}
			
			$outputJson = json_encode($outputList);
			
			usleep(10000); //SLEEP 10MS
			
			echo $outputJson;
			
		} else if ( $_POST["mode"] == "advanced" ) {
			$selectPage = (int) $_POST["page"];
			
			if ( $_POST["advancedMode"] == "default" ) {
				//FIND ALL
				$sql_com = "SELECT COUNT(hwid) AS total FROM $sql_list;";
				$sql_que = mysqli_query( $conn, $sql_com );
				$sql_result = mysqli_fetch_assoc($sql_que);
				$totalList = $sql_result["total"];
				$totalPage = ceil($totalList / $viewListEachPage);
				$totalLine = ceil($viewListEachPage / $viewListEachLine);
				$breakAfterList = [];
				for ($i=1;$i<=$totalLine;$i++) {
					array_push($breakAfterList, ($viewListEachLine * $i));
				}
				
				$selectPage = ($viewListEachPage * $selectPage) - $viewListEachPage;
				
				usleep(10000); //SLEEP 10MS
				
				$sql_com = "SELECT * FROM $sql_list AS d1 LEFT JOIN $sql_subjectList AS d3 ON (d1.subjectId=d3.subjectId) ORDER BY d1.hwid DESC LIMIT $selectPage,$viewListEachPage;";
				$sql_que = mysqli_query( $conn, $sql_com );
				$currentList = 1;
				$outputList = "";
				$description = "";
				
				if (mysqli_num_rows($sql_que) == 0) {
					$outputList = "0\nไม่พบข้อมูล";
				}
				
				while ($sql_result = mysqli_fetch_array($sql_que)) {
					if ($currentList == 1) {
						$outputList = $totalPage . "\n" . '<div class="card-deck mb-3">';
					}
					
					if (($currentList - 1) == $breakAfterList[0]) {
						$outputList .= '</div>
							<div class="card-deck mb-3">';
						array_shift($breakAfterList);
					}
					
					$dueDate = "";
					if ($sql_result["dueDate"] == 1) {
						$dueDate = "ไม่ได้กำหนด";
					} else {
						$dueDate = date("d/m/Y H:i น.", $sql_result["dueDate"] );
					}
					
					$description = $sql_result["description"];
					if (mb_strlen( $description, "UTF-8" ) > $viewDescriptionMax) {
						$description = mb_substr($sql_result["description"], 0, $viewDescriptionMax, "UTF-8") . "...";
					}
					
					$sql_coms = "SELECT progress,lastModifiedDate FROM $sql_progress WHERE hwid = " . $sql_result["hwid"] . " AND studentId = $studentId;";
					$sql_ques = mysqli_query( $conn, $sql_coms );
					$sql_res = mysqli_fetch_array($sql_ques);
					
					$isSent = '<i class="fas fa-times"></i> ยังไม่ได้ส่ง';
					$ocolor = "danger";
					
					if ( mysqli_num_rows($sql_ques) > 0 ) {
						if ($sql_res["progress"] == 1) {
							//IN-time
							$isSent = '<i class="fas fa-check"></i> ส่งแล้วเมื่อ ' . date("d/m/Y H:i น.", $sql_res["lastModifiedDate"]);
							$ocolor = "success";							
						} else if ($sql_res["progress"] == 2) {
							//LATE
							$isSent = '<i class="fas fa-check"></i> ส่งแล้วแต่ช้าเมื่อ ' . date("d/m/Y H:i น.", $sql_res["lastModifiedDate"]);
							$ocolor = "warning";
						}
					} else {
						
					}
					
					usleep(5000); //SLEEP 5MS
					
					$outputList .= '<div class="card border-' . $ocolor . '">
									<img class="card-img-top" src="' . $sql_result["subjectImg"] . '" loading="lazy" alt="' . $sql_result["subjectName"] . '">
									<div class="card-body">
										<h5 class="card-title text-' . $ocolor . '"><b>' . $sql_result["topic"] . '</b></h5>
										<p class="card-text">
											' . $description . '
										</p>
										<button class="btn btn-sm btn-' . $ocolor . ' text-light" onclick="loadInfo(this.id)" id="' . $sql_result["hwid"] . '" style="float: right;">เพิ่มเติม</button>
										<p class="card-text">
											<small class="text-muted">
												<i class="fas fa-book-reader"></i> วิชา: ' . $sql_result["subjectName"] .  '<br>
												<i class="fas fa-clock"></i> กำหนดส่ง: ' . $dueDate .  '<br>
												' . $isSent . '
											</small>
										</p>
									</div>
								</div>';
								
					$currentList += 1;
				}
				
				$outputList .= "</div><div class='mt-2'><small>ทั้งหมด $totalList รายการ</small></div>";
				
				echo $outputList;
				
			} else if ( $_POST["advancedMode"] == "subject" ) {
				
				$subjectId = mysqli_real_escape_string( $conn, $_POST["subjectId"] );

				//FIND ALL
				$sql_com = "SELECT COUNT(hwid) AS total FROM $sql_list WHERE subjectId = '$subjectId';";
				$sql_que = mysqli_query( $conn, $sql_com );
				$sql_result = mysqli_fetch_assoc($sql_que);
				$totalList = $sql_result["total"];
				$totalPage = ceil($totalList / $viewListEachPage);
				$totalLine = ceil($viewListEachPage / $viewListEachLine);
				$breakAfterList = [];
				for ($i=1;$i<=$totalLine;$i++) {
					array_push($breakAfterList, ($viewListEachLine * $i));
				}

				$selectPage = ($viewListEachPage * $selectPage) - $viewListEachPage;
				
				usleep(10000); //SLEEP 10MS
				
				$sql_com = "SELECT * FROM $sql_list AS d1 LEFT JOIN $sql_subjectList AS d3 ON (d1.subjectId=d3.subjectId) WHERE d1.subjectId = '$subjectId' ORDER BY d1.hwid DESC LIMIT $selectPage,$viewListEachPage;";
				$sql_que = mysqli_query( $conn, $sql_com );
				$currentList = 1;
				$outputList = "";
				$description = "";
		
				if (mysqli_num_rows($sql_que) == 0) {
					$outputList = "0\nไม่พบข้อมูล";
				}
				
				while ($sql_result = mysqli_fetch_array($sql_que)) {
					if ($currentList == 1) {
						$outputList = $totalPage . "\n" . '<div class="card-deck mb-3">';
					}
					
					if (($currentList - 1) == $breakAfterList[0]) {
						$outputList .= '</div>
							<div class="card-deck mb-3">';
						array_shift($breakAfterList);
					}

					$dueDate = "";
					if ($sql_result["dueDate"] == 1) {
						$dueDate = "ไม่ได้กำหนด";
					} else {
						$dueDate = date("d/m/Y H:i น.", $sql_result["dueDate"] );
					}
					
					$description = $sql_result["description"];
					if (mb_strlen( $description, "UTF-8" ) > $viewDescriptionMax) {
						$description = mb_substr($sql_result["description"], 0, $viewDescriptionMax, "UTF-8") . "...";
					}
					
					$sql_coms = "SELECT progress,lastModifiedDate FROM $sql_progress WHERE hwid = " . $sql_result["hwid"] . " AND studentId = $studentId;";
					$sql_ques = mysqli_query( $conn, $sql_coms );
					$sql_res = mysqli_fetch_array($sql_ques);
					
					$isSent = '<i class="fas fa-times"></i> ยังไม่ได้ส่ง';
					$ocolor = "danger";
					
					if ( mysqli_num_rows($sql_ques) > 0 ) {
						if ($sql_res["progress"] == 1) {
							//IN-time
							$isSent = '<i class="fas fa-check"></i> ส่งแล้วเมื่อ ' . date("d/m/Y H:i น.", $sql_res["lastModifiedDate"]);
							$ocolor = "success";							
						} else if ($sql_res["progress"] == 2) {
							//LATE
							$isSent = '<i class="fas fa-check"></i> ส่งแล้วแต่ช้าเมื่อ ' . date("d/m/Y H:i น.", $sql_res["lastModifiedDate"]);
							$ocolor = "warning";
						}
					} else {
						
					}
					
					usleep(5000); //SLEEP 5MS
					
					$outputList .= '<div class="card border-' . $ocolor . '">
									<img class="card-img-top" src="' . $sql_result["subjectImg"] . '" loading="lazy" alt="' . $sql_result["subjectName"] . '">
									<div class="card-body">
										<h5 class="card-title text-' . $ocolor . '"><b>' . $sql_result["topic"] . '</b></h5>
										<p class="card-text">
											' . $description . '
										</p>
										<button class="btn btn-sm btn-' . $ocolor . ' text-light" onclick="loadInfo(this.id)" id="' . $sql_result["hwid"] . '" style="float: right;">เพิ่มเติม</button>
										<p class="card-text">
											<small class="text-muted">
												<i class="fas fa-book-reader"></i> วิชา: ' . $sql_result["subjectName"] .  '<br>
												<i class="fas fa-clock"></i> กำหนดส่ง: ' . $dueDate .  '<br>
												' . $isSent . '
											</small>
										</p>
									</div>
								</div>';
								
					$currentList += 1;
				}
				
				$outputList .= "</div><div class='mt-2'><small>ทั้งหมด $totalList รายการ</small></div>";
				
				echo $outputList;
				
			} else if ( $_POST["advancedMode"] == "sent" ) {
				//FIND ALL
				$sql_com = "SELECT COUNT(hwid) AS total FROM $sql_progress WHERE progress = 1 AND studentId = '$studentId';";
				$sql_que = mysqli_query( $conn, $sql_com );
				$sql_result = mysqli_fetch_assoc($sql_que);
				$totalList = $sql_result["total"];
				$totalPage = ceil($totalList / $viewListEachPage);
				$totalLine = ceil($viewListEachPage / $viewListEachLine);
				$breakAfterList = [];
				for ($i=1;$i<=$totalLine;$i++) {
					array_push($breakAfterList, ($viewListEachLine * $i));
				}

				$selectPage = ($viewListEachPage * $selectPage) - $viewListEachPage;
				
				usleep(10000); //SLEEP 10MS
				
				$sql_coms = "SELECT * FROM $sql_progress AS d1 LEFT JOIN $sql_list AS d2 ON (d1.hwid=d2.hwid) LEFT JOIN $sql_subjectList AS d3 ON (d2.subjectId=d3.subjectId) WHERE d1.progress = 1 AND d1.studentId = '$studentId' ORDER BY d1.hwid DESC LIMIT $selectPage,$viewListEachPage;";
				$sql_ques = mysqli_query( $conn, $sql_coms );
				$currentList = 1;
				$outputList = "";
				$description = "";
		
				if (mysqli_num_rows($sql_ques) == 0) {
					$outputList = "0\nไม่พบข้อมูล";
				}
				
				while ($sql_result = mysqli_fetch_array($sql_ques)) {
					if ($currentList == 1) {
						$outputList = $totalPage . "\n" . '<div class="card-deck mb-3">';
					}
					
					if (($currentList - 1) == $breakAfterList[0]) {
						$outputList .= '</div>
							<div class="card-deck mb-3">';
						array_shift($breakAfterList);
					}

					$dueDate = "";
					if ($sql_result["dueDate"] == 1) {
						$dueDate = "ไม่ได้กำหนด";
					} else {
						$dueDate = date("d/m/Y H:i น.", $sql_result["dueDate"] );
					}
					
					$description = $sql_result["description"];
					if (mb_strlen( $description, "UTF-8" ) > $viewDescriptionMax) {
						$description = mb_substr($sql_result["description"], 0, $viewDescriptionMax, "UTF-8") . "...";
					}
					
				
					$isSent = '<i class="fas fa-check"></i> ส่งแล้วเมื่อ ' . date("d/m/Y H:i น.", $sql_result["lastModifiedDate"]);
					$ocolor = "success";
					
					//usleep(5000); //SLEEP 5MS
					
					$outputList .= '<div class="card border-' . $ocolor . '">
									<img class="card-img-top" src="' . $sql_result["subjectImg"] . '" loading="lazy" alt="' . $sql_result["subjectName"] . '">
									<div class="card-body">
										<h5 class="card-title text-' . $ocolor . '"><b>' . $sql_result["topic"] . '</b></h5>
										<p class="card-text">
											' . $description . '
										</p>
										<button class="btn btn-sm btn-' . $ocolor . ' text-light" onclick="loadInfo(this.id)" id="' . $sql_result["hwid"] . '" style="float: right;">เพิ่มเติม</button>
										<p class="card-text">
											<small class="text-muted">
												<i class="fas fa-book-reader"></i> วิชา: ' . $sql_result["subjectName"] .  '<br>
												<i class="fas fa-clock"></i> กำหนดส่ง: ' . $dueDate .  '<br>
												' . $isSent . '
											</small>
										</p>
									</div>
								</div>';
								
					$currentList += 1;
				}
				
				$outputList .= "</div><div class='mt-2'><small>ทั้งหมด $totalList รายการ</small></div>";
				
				echo $outputList;
				
			} else if ( $_POST["advancedMode"] == "late" ) {
				//FIND ALL
				$sql_com = "SELECT COUNT(hwid) AS total FROM $sql_progress WHERE progress = 2 AND studentId = '$studentId';";
				$sql_que = mysqli_query( $conn, $sql_com );
				$sql_result = mysqli_fetch_assoc($sql_que);
				$totalList = $sql_result["total"];
				$totalPage = ceil($totalList / $viewListEachPage);
				$totalLine = ceil($viewListEachPage / $viewListEachLine);
				$breakAfterList = [];
				for ($i=1;$i<=$totalLine;$i++) {
					array_push($breakAfterList, ($viewListEachLine * $i));
				}

				$selectPage = ($viewListEachPage * $selectPage) - $viewListEachPage;
				
				usleep(10000); //SLEEP 10MS
				
				$sql_com = "SELECT * FROM $sql_progress AS d1 LEFT JOIN $sql_list AS d2 ON (d1.hwid=d2.hwid) LEFT JOIN $sql_subjectList AS d3 ON (d2.subjectId=d3.subjectId) WHERE d1.progress = 2 AND d1.studentId = '$studentId' ORDER BY d1.hwid DESC LIMIT $selectPage,$viewListEachPage;";
				$sql_que = mysqli_query( $conn, $sql_com );
				$currentList = 1;
				$outputList = "";
				$description = "";
		
				if (mysqli_num_rows($sql_que) == 0) {
					$outputList = "0\nไม่พบข้อมูล";
				}
				
				echo $conn->error;
				
				while ($sql_result = mysqli_fetch_array($sql_que)) {
					if ($currentList == 1) {
						$outputList = $totalPage . "\n" . '<div class="card-deck mb-3">';
					}
					
					if (($currentList - 1) == $breakAfterList[0]) {
						$outputList .= '</div>
							<div class="card-deck mb-3">';
						array_shift($breakAfterList);
					}

					$dueDate = "";
					if ($sql_result["dueDate"] == 1) {
						$dueDate = "ไม่ได้กำหนด";
					} else {
						$dueDate = date("d/m/Y H:i น.", $sql_result["dueDate"] );
					}
					
					$description = $sql_result["description"];
					if (mb_strlen( $description, "UTF-8" ) > $viewDescriptionMax) {
						$description = mb_substr($sql_result["description"], 0, $viewDescriptionMax, "UTF-8") . "...";
					}
					
				
					$isSent = '<i class="fas fa-check"></i> ส่งแล้วแต่ช้าเมื่อ ' . date("d/m/Y H:i น.", $sql_result["lastModifiedDate"]);
					$ocolor = "warning";
					
					//usleep(5000); //SLEEP 5MS
					
					$outputList .= '<div class="card border-' . $ocolor . '">
									<img class="card-img-top" src="' . $sql_result["subjectImg"] . '" loading="lazy" alt="' . $sql_result["subjectName"] . '">
									<div class="card-body">
										<h5 class="card-title text-' . $ocolor . '"><b>' . $sql_result["topic"] . '</b></h5>
										<p class="card-text">
											' . $description . '
										</p>
										<button class="btn btn-sm btn-' . $ocolor . ' text-light" onclick="loadInfo(this.id)" id="' . $sql_result["hwid"] . '" style="float: right;">เพิ่มเติม</button>
										<p class="card-text">
											<small class="text-muted">
												<i class="fas fa-book-reader"></i> วิชา: ' . $sql_result["subjectName"] .  '<br>
												<i class="fas fa-clock"></i> กำหนดส่ง: ' . $dueDate .  '<br>
												' . $isSent . '
											</small>
										</p>
									</div>
								</div>';
								
					$currentList += 1;
				}
				
				$outputList .= "</div><div class='mt-2'><small>ทั้งหมด $totalList รายการ</small></div>";
				
				echo $outputList;
				
			} else if ( $_POST["advancedMode"] == "unsend" ) {
				//FIND ALL
				$sql_com = "SELECT COUNT(hwid) AS total FROM $sql_list WHERE NOT EXISTS (SELECT 1 FROM $sql_progress WHERE $sql_list.hwid=$sql_progress.hwid AND $sql_progress.progress!=0 AND $sql_progress.studentId = $studentId)";
				$sql_que = mysqli_query( $conn, $sql_com );
				$sql_result = mysqli_fetch_assoc($sql_que);
				$totalList = $sql_result["total"];
				$totalPage = ceil($totalList / $viewListEachPage);
				$totalLine = ceil($viewListEachPage / $viewListEachLine);
				$breakAfterList = [];
				for ($i=1;$i<=$totalLine;$i++) {
					array_push($breakAfterList, ($viewListEachLine * $i));
				}

				$selectPage = ($viewListEachPage * $selectPage) - $viewListEachPage;
				
				usleep(10000); //SLEEP 10MS
				
				$sql_com = "SELECT * FROM $sql_list AS d1 LEFT JOIN $sql_subjectList AS d3 ON (d1.subjectId=d3.subjectId) WHERE NOT EXISTS (SELECT 1 FROM $sql_progress WHERE d1.hwid=$sql_progress.hwid AND $sql_progress.progress!=0 AND $sql_progress.studentId = $studentId) ORDER BY d1.hwid DESC LIMIT $selectPage,$viewListEachPage;";
				$sql_que = mysqli_query( $conn, $sql_com );
				$currentList = 1;
				$outputList = "";
				$description = "";
				
				if (mysqli_num_rows($sql_que) == 0) {
					$outputList = "0\nไม่พบข้อมูล";
				}
				
				while ($sql_result = mysqli_fetch_array($sql_que)) {
					if ($currentList == 1) {
						$outputList = $totalPage . "\n" . '<div class="card-deck mb-3">';
					}
					
					if (($currentList - 1) == $breakAfterList[0]) {
						$outputList .= '</div>
							<div class="card-deck mb-3">';
						array_shift($breakAfterList);
					}

					$dueDate = "";
					if ($sql_result["dueDate"] == 1) {
						$dueDate = "ไม่ได้กำหนด";
					} else {
						$dueDate = date("d/m/Y H:i น.", $sql_result["dueDate"] );
					}
					
					$description = $sql_result["description"];
					if (mb_strlen( $description, "UTF-8" ) > $viewDescriptionMax) {
						$description = mb_substr($sql_result["description"], 0, $viewDescriptionMax, "UTF-8") . "...";
					}
					
				
					$isSent = '<i class="fas fa-times"></i> ยังไม่ได้ส่ง ';
					$ocolor = "danger";
					
					//usleep(5000); //SLEEP 5MS
					
					$outputList .= '<div class="card border-' . $ocolor . '">
									<img class="card-img-top" src="' . $sql_result["subjectImg"] . '" loading="lazy" alt="' . $sql_result["subjectName"] . '">
									<div class="card-body">
										<h5 class="card-title text-' . $ocolor . '"><b>' . $sql_result["topic"] . '</b></h5>
										<p class="card-text">
											' . $description . '
										</p>
										<button class="btn btn-sm btn-' . $ocolor . ' text-light" onclick="loadInfo(this.id)" id="' . $sql_result["hwid"] . '" style="float: right;">เพิ่มเติม</button>
										<p class="card-text">
											<small class="text-muted">
												<i class="fas fa-book-reader"></i> วิชา: ' . $sql_result["subjectName"] .  '<br>
												<i class="fas fa-clock"></i> กำหนดส่ง: ' . $dueDate .  '<br>
												' . $isSent . '
											</small>
										</p>
									</div>
								</div>';
								
					$currentList += 1;
				}
				
				$outputList .= "</div><div class='mt-2'><small>ทั้งหมด $totalList รายการ</small></div>";
				
				echo $outputList;
				
			} else if ( $_POST["advancedMode"] == "week" ) {
				if ( $_POST["week"] == "this" ) {
					$timeStartWeek = $timeStartThisWeek;
					$timeEndWeek = $timeEndThisWeek;
				} else if ( $_POST["week"] == "last" ) {
					$timeStartWeek = $timeStartLastWeek;
					$timeEndWeek = $timeEndLastWeek;
				}
				
				//FIND ALL
				$sql_com = "SELECT COUNT(hwid) AS total FROM $sql_list WHERE assignDate BETWEEN $timeStartWeek AND $timeEndWeek";
				$sql_que = mysqli_query( $conn, $sql_com );
				$sql_result = mysqli_fetch_assoc($sql_que);
				$totalList = $sql_result["total"];
				$totalPage = ceil($totalList / $viewListEachPage);
				$totalLine = ceil($viewListEachPage / $viewListEachLine);
				$breakAfterList = [];
				for ($i=1;$i<=$totalLine;$i++) {
					array_push($breakAfterList, ($viewListEachLine * $i));
				}

				$selectPage = ($viewListEachPage * $selectPage) - $viewListEachPage;
				
				usleep(10000); //SLEEP 10MS
				
				$sql_com = "SELECT * FROM $sql_list AS d1 LEFT JOIN $sql_subjectList AS d3 ON (d1.subjectId=d3.subjectId) WHERE d1.assignDate BETWEEN $timeStartWeek AND $timeEndWeek ORDER BY d1.hwid DESC LIMIT $selectPage,$viewListEachPage";
				$sql_que = mysqli_query( $conn, $sql_com );
				$currentList = 1;
				$outputList = "";
				$description = "";
				
				if (mysqli_num_rows($sql_que) == 0) {
					$outputList = "0\nไม่พบข้อมูล";
				}
				
				while ($sql_result = mysqli_fetch_array($sql_que)) {
					if ($currentList == 1) {
						$outputList = $totalPage . "\n" . '<div class="card-deck mb-3">';
					}
					
					if (($currentList - 1) == $breakAfterList[0]) {
						$outputList .= '</div>
							<div class="card-deck mb-3">';
						array_shift($breakAfterList);
					}

					$dueDate = "";
					if ($sql_result["dueDate"] == 1) {
						$dueDate = "ไม่ได้กำหนด";
					} else {
						$dueDate = date("d/m/Y H:i น.", $sql_result["dueDate"] );
					}
					
					$description = $sql_result["description"];
					if (mb_strlen( $description, "UTF-8" ) > $viewDescriptionMax) {
						$description = mb_substr($sql_result["description"], 0, $viewDescriptionMax, "UTF-8") . "...";
					}
					
					$sql_coms = "SELECT progress,lastModifiedDate FROM $sql_progress WHERE hwid = " . $sql_result["hwid"] . " AND studentId = $studentId;";
					$sql_ques = mysqli_query( $conn, $sql_coms );
					$sql_res = mysqli_fetch_array($sql_ques);
					
					$isSent = '<i class="fas fa-times"></i> ยังไม่ได้ส่ง';
					$ocolor = "danger";
					
					if ( mysqli_num_rows($sql_ques) > 0 ) {
						if ($sql_res["progress"] == 1) {
							//IN-time
							$isSent = '<i class="fas fa-check"></i> ส่งแล้วเมื่อ ' . date("d/m/Y H:i น.", $sql_res["lastModifiedDate"]);
							$ocolor = "success";							
						} else if ($sql_res["progress"] == 2) {
							//LATE
							$isSent = '<i class="fas fa-check"></i> ส่งแล้วแต่ช้าเมื่อ ' . date("d/m/Y H:i น.", $sql_res["lastModifiedDate"]);
							$ocolor = "warning";
						}
					} else {
						
					}
					
					usleep(5000); //SLEEP 5MS
					
					$outputList .= '<div class="card border-' . $ocolor . '">
									<img class="card-img-top" src="' . $sql_result["subjectImg"] . '" loading="lazy" alt="' . $sql_result["subjectName"] . '">
									<div class="card-body">
										<h5 class="card-title text-' . $ocolor . '"><b>' . $sql_result["topic"] . '</b></h5>
										<p class="card-text">
											' . $description . '
										</p>
										<button class="btn btn-sm btn-' . $ocolor . ' text-light" onclick="loadInfo(this.id)" id="' . $sql_result["hwid"] . '" style="float: right;">เพิ่มเติม</button>
										<p class="card-text">
											<small class="text-muted">
												<i class="fas fa-book-reader"></i> วิชา: ' . $sql_result["subjectName"] .  '<br>
												<i class="fas fa-clock"></i> กำหนดส่ง: ' . $dueDate .  '<br>
												' . $isSent . '
											</small>
										</p>
									</div>
								</div>';
								
					$currentList += 1;
				}
				
				$outputList .= "</div><div class='mt-2'><small>ทั้งหมด $totalList รายการ</small></div>";
				
				echo $outputList;
				
			} else if ( $_POST["advancedMode"] == "assign" || $_POST["advancedMode"] == "due" ) {
				$startTime = (int) $_POST["start"];
				$endTime = (int) $_POST["end"];
				$inputMode = trim( mysqli_real_escape_string( $conn, $_POST["advancedMode"] ) );
				
				//FIND ALL
				$sql_com = "SELECT COUNT(hwid) AS total FROM $sql_list WHERE " . $inputMode . "Date BETWEEN $startTime AND $endTime";
				$sql_que = mysqli_query( $conn, $sql_com );
				$sql_result = mysqli_fetch_assoc($sql_que);
				$totalList = $sql_result["total"];
				$totalPage = ceil($totalList / $viewListEachPage);
				$totalLine = ceil($viewListEachPage / $viewListEachLine);
				$breakAfterList = [];
				for ($i=1;$i<=$totalLine;$i++) {
					array_push($breakAfterList, ($viewListEachLine * $i));
				}

				$selectPage = ($viewListEachPage * $selectPage) - $viewListEachPage;
				
				usleep(10000); //SLEEP 10MS
				
				$sql_com = "SELECT * FROM $sql_list AS d1 LEFT JOIN $sql_subjectList AS d3 ON (d1.subjectId=d3.subjectId) WHERE d1." . $inputMode . "Date BETWEEN $startTime AND $endTime ORDER BY d1.hwid DESC LIMIT $selectPage,$viewListEachPage";
				$sql_que = mysqli_query( $conn, $sql_com );
				$currentList = 1;
				$outputList = "";
				$description = "";
				
				if (mysqli_num_rows($sql_que) == 0) {
					$outputList = "0\nไม่พบข้อมูล";
				}
				
				while ($sql_result = mysqli_fetch_array($sql_que)) {
					if ($currentList == 1) {
						$outputList = $totalPage . "\n" . '<div class="card-deck mb-3">';
					}
					
					if (($currentList - 1) == $breakAfterList[0]) {
						$outputList .= '</div>
							<div class="card-deck mb-3">';
						array_shift($breakAfterList);
					}

					$dueDate = "";
					if ($sql_result["dueDate"] == 1) {
						$dueDate = "ไม่ได้กำหนด";
					} else {
						$dueDate = date("d/m/Y H:i น.", $sql_result["dueDate"] );
					}
					
					$description = $sql_result["description"];
					if (mb_strlen( $description, "UTF-8" ) > $viewDescriptionMax) {
						$description = mb_substr($sql_result["description"], 0, $viewDescriptionMax, "UTF-8") . "...";
					}
					
					$sql_coms = "SELECT progress,lastModifiedDate FROM $sql_progress WHERE hwid = " . $sql_result["hwid"] . " AND studentId = $studentId;";
					$sql_ques = mysqli_query( $conn, $sql_coms );
					$sql_res = mysqli_fetch_array($sql_ques);
					
					$isSent = '<i class="fas fa-times"></i> ยังไม่ได้ส่ง';
					$ocolor = "danger";
					
					if ( mysqli_num_rows($sql_ques) > 0 ) {
						if ($sql_res["progress"] == 1) {
							//IN-time
							$isSent = '<i class="fas fa-check"></i> ส่งแล้วเมื่อ ' . date("d/m/Y H:i น.", $sql_res["lastModifiedDate"]);
							$ocolor = "success";							
						} else if ($sql_res["progress"] == 2) {
							//LATE
							$isSent = '<i class="fas fa-check"></i> ส่งแล้วแต่ช้าเมื่อ ' . date("d/m/Y H:i น.", $sql_res["lastModifiedDate"]);
							$ocolor = "warning";
						}
					} else {
						
					}
					
					usleep(5000); //SLEEP 5MS
					
					$outputList .= '<div class="card border-' . $ocolor . '">
									<img class="card-img-top" src="' . $sql_result["subjectImg"] . '" loading="lazy" alt="' . $sql_result["subjectName"] . '">
									<div class="card-body">
										<h5 class="card-title text-' . $ocolor . '"><b>' . $sql_result["topic"] . '</b></h5>
										<p class="card-text">
											' . $description . '
										</p>
										<button class="btn btn-sm btn-' . $ocolor . ' text-light" onclick="loadInfo(this.id)" id="' . $sql_result["hwid"] . '" style="float: right;">เพิ่มเติม</button>
										<p class="card-text">
											<small class="text-muted">
												<i class="fas fa-book-reader"></i> วิชา: ' . $sql_result["subjectName"] .  '<br>
												<i class="fas fa-clock"></i> กำหนดส่ง: ' . $dueDate .  '<br>
												' . $isSent . '
											</small>
										</p>
									</div>
								</div>';
								
					$currentList += 1;
				}
				
				$outputList .= "</div><div class='mt-2'><small>ทั้งหมด $totalList รายการ</small></div>";
				
				echo $outputList;
				
			}
		
		} else if ( $_POST["mode"] == "information" ) {
			
			usleep(10000); //SLEEP 10MS
			$hwid = (int) $_POST["hwid"];
			
			$sql_com = "SELECT d1.*,d2.studentName,d2.studentLastName,d3.* FROM $sql_list AS d1 LEFT JOIN $sql_account AS d2 ON (d1.studentId=d2.studentId) LEFT JOIN $sql_subjectList AS d3 ON (d1.subjectId=d3.subjectId) WHERE d1.hwid=$hwid";
			$sql_que = mysqli_query( $conn, $sql_com );
			$sql_result = mysqli_fetch_array($sql_que);
			
			if ($sql_que) {
			
				$hwid = $sql_result["hwid"];
				$topic = $sql_result["topic"];
				$subjectImg = $sql_result["subjectImg"];
				$description = $sql_result["description"];
				$subjectId = $sql_result["subjectId"];
				$subjectName = $sql_result["subjectName"];
				$dueDate = $sql_result["dueDate"];
				$assignDate = $sql_result["assignDate"];
				$addDate = $sql_result["addDate"];
				$studentName = $sql_result["studentName"];
				$studentLastName = $sql_result["studentLastName"];
				
				$isSent = false;
				$sql_com = "SELECT COUNT(id) AS total FROM $sql_progress WHERE hwid = $hwid AND studentId = $studentId AND progress != 0;";
				$sql_que = mysqli_query( $conn, $sql_com );
				$sql_result = mysqli_fetch_assoc($sql_que);
				if ($sql_result["total"] > 0) {
					$isSent = true;
				} else {
					$isSent = false;
				}
				
				$studentName = $studentName . " " . $studentLastName;
				$subjectName = $subjectId . " " . $subjectName;
				if ($dueDate == 1) {
					$dueDate = "ไม่ได้กำหนด";
				} else {
					$dueDate = date("d/m/Y H:i น.", $dueDate );
				}
				$assignDate = date("d/m/Y H:i น.", $assignDate );
				$addDate = date("d/m/Y H:i น.", $addDate );
				
				$outputArray = ["hwid"=>$hwid, "isSent"=>$isSent, "topic"=>$topic, "subjectImg"=>$subjectImg, "description"=>$description, "subjectName"=>$subjectName, "assignDate"=>$assignDate, "dueDate"=>$dueDate, "addDate"=>$addDate, "studentName"=>$studentName];
				$outputJson = json_encode($outputArray);
				$outputArray = null;
				
				echo $outputJson;
				
			} else {
				echo "Couldn't query the database.";
			}
			
		} else if ( $_POST["mode"] == "markAsSent" ) {
			
			usleep(10000); //SLEEP 10MS
			$date = (int) $_POST["date"];
			$hwid = (int) $_POST["hwid"];
			
			$sql_com = "SELECT COUNT(id) AS total FROM $sql_progress WHERE hwid = $hwid AND studentId = $studentId;";
			$sql_que = mysqli_query( $conn, $sql_com );
			$sql_result = mysqli_fetch_assoc($sql_que);
			
			//CHECK IF LATE
			$progress = 1;
			usleep(5000); //SLEEP 5MS
			$sql_coms = "SELECT dueDate FROM $sql_list WHERE hwid = $hwid";
			$sql_ques = mysqli_query( $conn, $sql_coms );
			$sql_results = mysqli_fetch_array($sql_ques);
			
			if ( $date > $sql_results["dueDate"] && $sql_results["dueDate"] != 1 ) {
				$progress = 2;
			}
		
			if ($sql_que) {
				if ($sql_result["total"] > 0) {
					//UPDATE
					$sql_com = "UPDATE $sql_progress SET progress = $progress, lastModifiedDate = $date WHERE hwid = $hwid AND studentId = $studentId;";
					$sql_que = mysqli_query($conn, $sql_com);
				} else {
					//INSERT
					$sql_com = "INSERT INTO $sql_progress(hwid, studentId, progress, lastModifiedDate) VALUES ($hwid, $studentId, $progress, $date);";
					$sql_que = mysqli_query($conn, $sql_com);
				}
				
				echo "ok";
			} else {
				echo "Couldn't query the database.";
			}
			
		} else if ( $_POST["mode"] == "markAsUnsend" ) {
			
			usleep(10000); //SLEEP 10MS
			$hwid = (int) $_POST["hwid"];
			
			$sql_com = "SELECT COUNT(id) AS total FROM $sql_progress WHERE hwid = $hwid AND studentId = $studentId;";
			$sql_que = mysqli_query( $conn, $sql_com );
			$sql_result = mysqli_fetch_assoc($sql_que);
			
			if ($sql_que) {
				if ($sql_result["total"] > 0) {
					//UPDATE
					$sql_com = "UPDATE $sql_progress SET progress = 0, lastModifiedDate = " . time() . "  WHERE hwid = $hwid AND studentId = $studentId;";
					$sql_que = mysqli_query($conn, $sql_com);
				} else {
					//INSERT
					$sql_com = "INSERT INTO $sql_progress(hwid, studentId, progress, lastModifiedDate) VALUES ($hwid, $studentId, 0, " . time() . ");";
					$sql_que = mysqli_query($conn, $sql_com);
				}
				
				echo "ok";
			} else {
				echo "Couldn't query the database.";
			}
			
		}
	} else {
		http_response_code(401); 
	}
?>