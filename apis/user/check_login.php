<?php
require_once('../../common/header.php');


try {
    $jwtCls = new Jwt();
	
    $userSeqNo = $obj['userSeqNo'];
    $keepLogin = $obj['keepLogin'];
    $jwt = $obj['jwt'];

    $auch = $jwtCls->dehashing($jwt, $userSeqNo);
    
    if($auch) {
            
        $jwt = $jwtCls->hashing($userSeqNo, $keepLogin);
        echo '{"result":"success", "jwt": "'.$jwt.'"}';
    }
}
catch (Exception $e) {
    echo '{"result":"fail", "errorCode": "00", "errorMessage": "'.$e->getMessage().'"}';
}
    
?>