<?php

try {
	require('../../database/database.php');
    require('../../database/thank_diary_query.php');
    require('../../common/error_message.php');

    $json = file_get_contents('php://input');
    $obj = json_decode($json,true);

    $thankDiarySeqNo = $obj['thankDiarySeqNo'];
    $userSeqNo = $obj['userSeqNo'];
    $title = $obj['title'];
    $diaryDate = $obj['diaryDate'];
    $content = $obj['content'];
     

    if($userSeqNo != null && $userSeqNo != '') {
        if($thankDiarySeqNo == null || $thankDiarySeqNo == '') {
            $insertResult = insertThankDiary($userSeqNo, $title, $diaryDate, $content);
            
            if($insertResult == 1) {
                echo '{"result":"success"}';
            }
            else {
                echo '{"result":"fail", "errorCode": "'.$commonError["code"].'", "errorMessage": "'.$commonError["message"].'"}';
            }
        }
        else {
            $updateResult = updateThankDiary($thankDiarySeqNo, $userSeqNo, $title, $diaryDate, $content);
    
            if($updateResult == 1) {
                echo '{"result":"success"}';
            }
            else {
                echo '{"result":"fail", "errorCode": "'.$commonError["code"].'", "errorMessage": "'.$commonError["message"].'"}';
            }
        }
    }
    else {
        echo '{"result":"fail", "errorCode": "02", "There is no user logged in."}';
    }

}
catch (Exception $e) {
    echo '{"result":"fail", "errorCode": "00", "errorMessage": "'.$e->getMessage().'"}';
}
    
?>