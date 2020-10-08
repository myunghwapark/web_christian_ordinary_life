<?php

require('../../database/database.php');
require('../../database/user_query.php');
require('../../common/error_message.php');

$json = file_get_contents('php://input');
$obj = json_decode($json,true);

$userEmail = $obj['userEmail'];
$oldPassword = $obj['oldPassword'];
$newPassword = $obj['newPassword'];

$result = getUser($userEmail, $oldPassword);
$numResults = mysqli_num_rows($result);

if($numResults > 0) {
    // Password update
    $updatePasswordResult = updateUserPassword($userEmail, $newPassword);

    if($updatePasswordResult == 1) {
        echo '{"result":"success"}';
    }
    else {
        echo '{"result":"fail", "errorCode": "01", "errorMessage": "'.$commonError["message"].'"}';
    }
}
else {
    echo '{"result":"fail", "errorCode": "00", "errorMessage": "There is no user matching the registered email or password."}';
}

?>