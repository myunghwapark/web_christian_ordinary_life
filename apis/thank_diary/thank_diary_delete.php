<?php
require_once $_SERVER["DOCUMENT_ROOT"].'/col/common/header.php';
require('../../database/thank_diary_query.php');

try {
    $jwtCls = new Jwt();
	
    $userSeqNo = $obj['userSeqNo'];
    $thankDiarySeqNo = $obj['thankDiarySeqNo'];
    $imageURL = $obj['imageURL'];
    $keepLogin = $obj['keepLogin'];
    $jwt = $obj['jwt'];

    $auch = $jwtCls->dehashing($jwt);
    
    if($auch) {
            
        $jwt = $jwtCls->hashing($userSeqNo, $keepLogin);

        $result = deleteThankDiary($userSeqNo, $thankDiarySeqNo);

        if($imageURL != null && $imageURL != '') {
            $saveFileName = $_SERVER['DOCUMENT_ROOT'] . '/col/images/diary/' . $imageURL;
            if(file_exists($saveFileName)) unlink($saveFileName);
        }

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