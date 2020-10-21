<?php
require_once $_SERVER["DOCUMENT_ROOT"].'/col/common/header.php';
require('../../database/user_query.php');


try {
    $jwtCls = new Jwt();

    $userSeqNo = $obj['userSeqNo'];
    $userEmail = $obj['userEmail'];
    $oldPassword = $obj['oldPassword'];
    $newPassword = $obj['newPassword'];
    $keepLogin = $obj['keepLogin'];
    $jwt = $obj['jwt'];

    $auch = $jwtCls->dehashing($jwt);
    
    if($auch) {
            
        $jwt = $jwtCls->hashing($userSeqNo, $keepLogin);

        $result = getUser($userEmail, $oldPassword);
        $numResults = mysqli_num_rows($result);

        if($numResults > 0) {
            // Password update
            $updatePasswordResult = updateUserPassword($userEmail, $newPassword);

            if($updatePasswordResult == 1) {
                echo '{"result":"success", "jwt": "'.$jwt.'"}';
            }
            else {
                echo '{"result":"fail", "jwt": "'.$jwt.'", "errorCode": "01", "errorMessage": "'.$commonError["message"].'"}';
            }
        }
        else {
            echo '{"result":"fail", "jwt": "'.$jwt.'", "errorCode": "00", "errorMessage": "There is no user matching the registered email or password."}';
        }
    }

}
catch (Exception $e) {
    echo '{"result":"fail", "errorCode": "00", "errorMessage": "'.$e->getMessage().'"}';
}

?>