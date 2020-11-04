<?php
require_once('../../common/header.php');
require_once('../../database/user_query.php');


try {
    $jwtCls = new Jwt();

    $userSeqNo = $obj['userSeqNo'];
    $userEmail = $obj['userEmail'];
    $oldPassword = $obj['oldPassword'];
    $newPassword = $obj['newPassword'];
    $keepLogin = $obj['keepLogin'];
    $jwt = $obj['jwt'];
 


    $auch = $jwtCls->dehashing($jwt, $userSeqNo);
    
    if($auch) {
            
        $jwt = $jwtCls->hashing($userSeqNo, $keepLogin);

        $result = getUser($userEmail);
        $numResults = mysqli_num_rows($result);

        if($numResults > 0) {
            while($row = mysqli_fetch_array($result)){
                $savedPassword = $row['user_password'];
            }

            if (password_verify($oldPassword, $savedPassword))
            {
            
                /* Create the new password hash. */
                $hash = password_hash($newPassword, PASSWORD_DEFAULT, $options);

                // Password update
                $updatePasswordResult = updateUserPassword($userEmail, $hash);

                if($updatePasswordResult == 1) {
                    echo '{"result":"success", "jwt": "'.$jwt.'"}';
                }
                else {
                    echo '{"result":"fail", "jwt": "'.$jwt.'", "errorCode": "03", "errorMessage": "'.$commonError["message"].'"}';
                }
            }
            else {
                echo '{"result":"fail", "jwt": "'.$jwt.'", "errorCode": "02", "errorMessage": "There is no user matching the registered email or password."}';
            }
        }
        else {
            echo '{"result":"fail", "jwt": "'.$jwt.'", "errorCode": "01", "errorMessage": "There is no user matching the registered email or password."}';
        }
    }

}
catch (Exception $e) {
    echo '{"result":"fail", "errorCode": "00", "errorMessage": "'.$e->getMessage().'"}';
}

?>