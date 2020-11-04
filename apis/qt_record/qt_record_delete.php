<?php
require_once('../../common/header.php');
require_once('../../database/qt_record_query.php');

try {
    $jwtCls = new Jwt();
	
    $userSeqNo = $obj['userSeqNo'];
    $qtRecordSeqNo = $obj['qtRecordSeqNo'];
    $keepLogin = $obj['keepLogin'];
    $jwt = $obj['jwt'];

    $auch = $jwtCls->dehashing($jwt, $userSeqNo);
    
    if($auch) {
            
        $jwt = $jwtCls->hashing($userSeqNo, $keepLogin);

        $result = deleteQtRecord($userSeqNo, $qtRecordSeqNo);

        if($result == 1) {
            echo '{"result":"success", "jwt": "'.$jwt.'"}';
        }
        else {
            echo '{"result":"fail", "jwt": "'.$jwt.'", "errorCode": "'.$commonError["code"].'", "errorMessage": "'.$commonError["message"].'"}';
        }
    }
}
catch (Exception $e) {
    echo '{"result":"fail", "errorCode": "00", "errorMessage": "'.$e->getMessage().'"}';
}
    
?>