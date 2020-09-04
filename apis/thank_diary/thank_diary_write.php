<?php

try {
	require('../../database/database.php');
    require('../../database/thank_diary_query.php');
    require('../../database/goal_query.php');
    require('../../common/error_message.php');

    $json = file_get_contents('php://input');
    $obj = json_decode($json,true);

    $thankDiarySeqNo = $obj['thankDiarySeqNo'];
    $userSeqNo = $obj['userSeqNo'];
    $title = $obj['title'];
    $diaryDate = $obj['diaryDate'];
    $content = $obj['content'];
    $thankDiary = $obj['thankDiary'];
    $goalDate = $obj['goalDate'];
     

    $setResult;
    if($userSeqNo != null && $userSeqNo != '') {
        if($thankDiarySeqNo == null || $thankDiarySeqNo == '') {
            $setResult = insertThankDiary($userSeqNo, $title, $diaryDate, $content);
        }
        else {
            $setResult = updateThankDiary($thankDiarySeqNo, $userSeqNo, $title, $diaryDate, $content);
        }
    }
    else {
        echo '{"result":"fail", "errorCode": "02", "There is no user logged in."}';
    }


    if($setResult == 1) {

        $getGoalResult = getGoalProgress($userSeqNo, $goalDate);
        $numGetGoalResults = mysqli_num_rows($getGoalResult);
        
        $counter = 0;
    
        if($numGetGoalResults > 0) {
    
            $result = updateThankDiaryProgress($userSeqNo, $goalDate, $thankDiary);
    
            if($result == 1) {
                echo '{"result":"success"}';
            }
            else {
                echo '{"result":"fail", "errorCode": "'.$commonError["code"].'", "errorMessage": "'.$commonError["message"].'"}';
            }
        }
        else {
    
            $result = insertThankDiaryProgress($userSeqNo, $goalDate, $thankDiary);
    
            if($result == 1) {
                echo '{"result":"success"}';
            }
            else {
                echo '{"result":"fail", "errorCode": "'.$commonError["code"].'", "errorMessage": "'.$commonError["message"].'"}';
            }
        }
    }

}
catch (Exception $e) {
    echo '{"result":"fail", "errorCode": "00", "errorMessage": "'.$e->getMessage().'"}';
}
    
?>