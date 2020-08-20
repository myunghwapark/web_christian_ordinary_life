<?php

try {
	require('../../database/database.php');
    require('../../database/qt_record_query.php');
    require('../../common/error_message.php');

    $json = file_get_contents('php://input');
    $obj = json_decode($json,true);
	
    $userSeqNo = $obj['userSeqNo'];
    $qtRecordSeqNo = $obj['qtRecordSeqNo'];

    $result = deleteQtRecord($userSeqNo, $qtRecordSeqNo);

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