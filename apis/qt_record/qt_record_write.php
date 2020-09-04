<?php

try {
	require('../../database/database.php');
    require('../../database/qt_record_query.php');
    require('../../database/goal_query.php');
    require('../../common/error_message.php');

    $json = file_get_contents('php://input');
    $obj = json_decode($json,true);
    
    $qtRecordSeqNo = $obj['qtRecordSeqNo'];
    $userSeqNo = $obj['userSeqNo'];
    $title = $obj['title'];
    $bible = $obj['bible'];
    $qtDate = $obj['qtDate'];
    $content = $obj['content'];
    $qtRecord = $obj['qtRecord'];
    $goalDate = $obj['goalDate'];

    $setResult;
    if($userSeqNo != null && $userSeqNo != '') {
        if($qtRecordSeqNo == null || $qtRecordSeqNo == '') {
            $setResult = insertQtRecord($userSeqNo, $title, $qtDate, $bible, $content);
            
        }
        else {
            $setResult = updateQtRecord($qtRecordSeqNo, $userSeqNo, $title, $qtDate, $bible, $content);
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
    
            $result = updateQtRecordProgress($userSeqNo, $goalDate, $qtRecord);
    
            if($result == 1) {
                echo '{"result":"success"}';
            }
            else {
                echo '{"result":"fail", "errorCode": "'.$commonError["code"].'", "errorMessage": "'.$commonError["message"].'"}';
            }
        }
        else {
    
            $result = insertQtRecordProgress($userSeqNo, $goalDate, $qtRecord);
    
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