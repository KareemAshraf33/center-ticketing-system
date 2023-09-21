
<?php

require_once 'functions.php';

$token = 'aqib';

$challenge = $_REQUEST['hub_challenge'];
$verify_token = $_REQUEST['hub_verify_token'];
$payload = file_get_contents('php://input');

if ($verify_token === $token) {
	echo $challenge;
}


if (!empty($payload)) {

	$decode = json_decode($payload, true);


	$ownerno = $decode['entry'][0]['changes']['0']['value']['metadata']['display_phone_number'];
	$username = $decode['entry'][0]['changes']['0']['value']['contacts'][0]['profile']['name'];
	$userno = $decode['entry'][0]['changes']['0']['value']['messages'][0]['from'];
	$messageType = $decode['entry'][0]['changes']['0']['value']['messages'][0]['type'];
	//$usermessage = $decode['entry'][0]['changes']['0']['value']['messages'][0]['text']['body'];

	$user_data = getCurrentStage($userno);
	$result = $user_data['current_stage'];
	if ($result == -1 || $result == '') {
		sendNewListToUser($userno, $username);
	}
	// client list
	else if ($result == 0) {
		if ($messageType == 'interactive') {
			$intertype = $decode['entry'][0]['changes']['0']['value']['messages'][0]['interactive']['type'];
			$messageId = $decode['entry'][0]['changes']['0']['value']['messages'][0]['interactive'][$intertype]['id'];

			switch ($messageId) {
				case 'scheduleid': {
						storeCurrentStage(1, $userno);
						sendTextMessage('من فضلك ادخل الرقم التدريبي الخاص بك :', $userno);
						break;
					}
				case 'activitiesid': {
						storeCurrentStage(2, $userno);
						$activities = getActivities();
						$allActivitiesText = 'الأنشطة المتاحة:\\n';
						foreach ($activities as $activity) {
							$allActivitiesText .= $activity['activity_name'] . '\\n';
						}
						sendTextMessage($allActivitiesText, $userno);
						previousMessage($userno);
						break;
					}
				case 'learnid': {
						storeCurrentStage(3, $userno);
						sendLearnVideoListToUser($userno);
						break;
					}
				case 'questionsid': {
						storeCurrentStage(4, $userno);
						$messageTxt = "1️⃣  السؤال الاول؟ \\n\\n" .
							"اجابة السؤال الاول.\\n\\n" .
							"2️⃣ السؤال الثاني؟ \\n\\n" .
							" اجابة السؤال الثاني.\\n";
						sendTextMessage($messageTxt, $userno);
						previousMessage($userno);
						break;
					}
				case 'supportticketid': {

						$check = checkTicket($userno);
						if ($check) {
							storeCurrentStage(5, $userno);
							$messageTxt = "لديك طلب مسبق للدعم \\n رقم الطلب : {$check}";
							sendTextMessage($messageTxt, $userno);
							previousMessage($userno);
						} else {
							storeCurrentStage(51, $userno);
							$messageTxt = "من فضلك ادخل طلبك";
							sendTextMessage($messageTxt, $userno);
						}
						break;
					}
				case 'resetpasswordid': {
					storeCurrentStage(6, $userno);
					sendTextMessage('من فضلك ادخل الباسورد الجديد:', $userno);
					break;
				}
				case 'endchatid': {
						endChatMessage($userno);
						break;
					}
				default: {
						$content = 'من فضلك حدد اختيارك بدقة';
						sendTextMessage($content, $userno);
						sendNewListToUser($userno, $username);
						break;
					}
			}
		} else {
			$content = 'من فضلك حدد اختيارك بدقة';
			sendTextMessage($content, $userno);
			sendNewListToUser($userno, $username);
		}
	}
	else if ($result == 3)
	{
		if ($messageType == 'interactive') {
			$intertype = $decode['entry'][0]['changes']['0']['value']['messages'][0]['interactive']['type'];
			$messageId = $decode['entry'][0]['changes']['0']['value']['messages'][0]['interactive'][$intertype]['id'];

			$cuurentStageVal = -1;
			if ($result == 3) {
				$cuurentStageVal = 31;
			}

			switch ($messageId) {
				case 'video1id': {
						storeCurrentStage($cuurentStageVal, $userno);
						$link = 'https://storage.googleapis.com/gtv-videos-bucket/sample/ForBiggerJoyrides.mp4';
						sendVideoMessage($link, 'Ved1', $userno);
						previousMessage($userno);
						break;
					}

				case 'video2id': {
						storeCurrentStage($cuurentStageVal, $userno);
						$content = 'Ved2 :  \\n https://youtu.be/UsBWJy13Bjo';
						sendTextMessage($content, $userno);
						previousMessage($userno);
						break;
					}
				case 'video3id': {
						storeCurrentStage($cuurentStageVal, $userno);
						$content = 'Ved3 :  \\n https://youtu.be/lToQs1H8iD8';
						sendTextMessage($content, $userno);
						previousMessage($userno);
						break;
					}
				case 'video4id': {
						storeCurrentStage($cuurentStageVal, $userno);
						$content = 'Ved4 : \\n https://youtu.be/5Iy5ccEZr7k';
						sendTextMessage($content, $userno);
						previousMessage($userno);
						break;
					}
				case 'video5id': {
						storeCurrentStage($cuurentStageVal, $userno);
						$content = 'Ved5 : \\n https://youtu.be/DlvuezmoO4U';
						sendTextMessage($content, $userno);
						previousMessage($userno);
						break;
					}
				case 'video6id': {
						storeCurrentStage($cuurentStageVal, $userno);
						$content = 'Ved6 : \\n https://youtu.be/ywomjy32Sh4';
						sendTextMessage($content, $userno);
						previousMessage($userno);
						break;
					}
				case 'returnpreviouslistid': {
						if ($result == 3) {
							sendNewListToUser($userno, $username);
						}
						break;
					}

				default: {
						$content = 'من فضلك حدد اختيارك بدقة';
						sendTextMessage($content, $userno);
						storeCurrentStage($result, $userno);
						sendLearnVideoListToUser($userno);

						break;
					}
			}
		} else {
			$content = 'من فضلك حدد اختيارك بدقة';
			sendTextMessage($content, $userno);
			storeCurrentStage($result, $userno);
			sendLearnVideoListToUser($userno);
		}
	}
	else if ($result == 51)
	{
		try {
			if ($result == 51) {
				storeCurrentStage(52, $userno);
			}
			$usermessage = $decode['entry'][0]['changes']['0']['value']['messages'][0]['text']['body'];
			createTicket($userno, $username, $usermessage);
			$ticket_id = getTicketId($userno);

			if ($ticket_id) {

				$messageTxt = "تم انشاء طلب للدعم \\n رقم طلبك : {$ticket_id} ";
				sendTextMessage($messageTxt, $userno);
				previousMessage($userno);
			} else {
				$messageTxt = "حدث خطأ اثناء تسجيل طلب الدعم من فضلك حاول مرة اخري..";
				sendTextMessage($messageTxt, $userno);
				previousMessage($userno);
			}
		} catch (Exception $e) {
			$messageTxt = 'Message: ' . $e->getMessage();
		}
	}
	elseif ($result == 6) {
		$usermessage = $decode['entry'][0]['changes']['0']['value']['messages'][0]['text']['body'];
		resetPassword($usermessage, $userno);
	}

	elseif ($result == 1) {
		$usermessage = $decode['entry'][0]['changes']['0']['value']['messages'][0]['text']['body'];
		sendDocumentMessage('https://customerk.sahldns.xyz/ExcelSeparationProject/public/student_files/student_'.$usermessage.'_schedule.xlsx','schedule',$userno);
		previousMessage($userno);
	}
	else if ($result == 11 || $result == 2 || $result == 4 || $result == 5 || $result == 6 || $result == 52 || $result == 61 || $result == 31 ) {

		if ($messageType == 'interactive') {
			$intertype = $decode['entry'][0]['changes']['0']['value']['messages'][0]['interactive']['type'];
			$messageId = $decode['entry'][0]['changes']['0']['value']['messages'][0]['interactive'][$intertype]['id'];

			switch ($messageId) {

				case 'returnpreviouslistid': {
						if ($result == 31) {
							storeCurrentStage(3, $userno);
							sendLearnVideoListToUser($userno);
						} elseif ($result == 52 || $result == 11 || $result == 2 || $result == 3 || $result == 4 || $result == 5 || $result == 6 || $result == 61 || $result == 31) {
							sendNewListToUser($userno, $username);
						}
						break;
					}

				case 'mainlistid': {
					sendNewListToUser($userno, $username);
					break;
					}
				case 'sahlendchatid': {
						endChatMessage($userno);
						break;
					}

				default: {
						$content = 'من فضلك حدد اختيارك بدقة';
						sendTextMessage($content, $userno);
						previousMessage($userno);

						break;
					}
			}
		} else {
			$content = 'من فضلك حدد اختيارك بدقة';
			sendTextMessage($content, $userno);
			previousMessage($userno);
		}
	}
}





?>

