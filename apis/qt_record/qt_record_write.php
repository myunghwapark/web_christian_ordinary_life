<?php

try {
	require('../../database/database.php');
    require('../../database/qt_record_query.php');
    require('../../common/error_message.php');

    $json = file_get_contents('php://input');
    $obj = json_decode($json,true);
    
    $qtRecordSeqNo = $obj['qtRecordSeqNo'];
    $userSeqNo = $obj['userSeqNo'];
    $title = $obj['title'];
    $bible = $obj['bible'];
    $qtDate = $obj['qtDate'];
    $content = $obj['content'];

    if($userSeqNo != null && $userSeqNo != '') {
        if($qtRecordSeqNo == null || $qtRecordSeqNo == '') {
            $insertResult = insertQtRecord($userSeqNo, $title, $qtDate, $bible, $content);
            
            if($insertResult == 1) {
                echo '{"result":"success"}';
            }
            else {
                echo '{"result":"fail", "errorCode": "'.$commonError["code"].'", "errorMessage": "'.$commonError["message"].'"}';
            }
        }
        else {
            $updateResult = updateQtRecord($qtRecordSeqNo, $userSeqNo, $title, $qtDate, $bible, $content);
    
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