<?php

try {
	require('../../database/database.php');
    require('../../database/thank_diary_query.php');
    require('../../common/error_message.php');

    $json = file_get_contents('php://input');
    $obj = json_decode($json,true);
	
    $userSeqNo = $obj['userSeqNo'];
    $thankDiarySeqNo = $obj['thankDiarySeqNo'];
    $imageURL = $obj['imageURL'];

    $result = deleteThankDiary($userSeqNo, $thankDiarySeqNo);

	if($imageURL != null && $imageURL != '') {
		$saveFileName = $_SERVER['DOCUMENT_ROOT'] . '/col/images/diary/' . $imageURL;
		if(file_exists($saveFileName)) unlink($saveFileName);
	}

    if($result == 1) {
        echo '{"result":"success"}';
    }
    else {
        echo '{"result":"fail", "errorCode": "'.$commonError["code"].'", "errorMessage": "'.$commonError["message"].'"}';
    }
}
catch (Exception $e) {
    echo '{"result":"fail", "errorCode": "00", "errorMessage": "'.$e->getMessage().'"}';
}
    
?>