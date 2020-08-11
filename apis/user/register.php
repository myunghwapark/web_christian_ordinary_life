<?php

try {
	require('../../database/database.php');
	require('../../database/user_query.php');

    // Getting the received JSON into $json variable.
    $json = file_get_contents('php://input');
 
    // Decoding the received JSON and store into $obj variable.
    $obj = json_decode($json,true);

    
	$userName = $obj['userName'];
	$userEmail = $obj['userEmail'];
	$userPassword = $obj['userPassword'];
    $userGrade = $obj['userGrade'];
    
    if(!empty($userEmail) && !empty($userName) && !empty($userPassword)) {
        $getUserEmail = getUserEmail($userEmail);
    
        if($getUserEmail == 0) {
            $result = registerUser($userName, $userEmail, $userPassword, $userGrade);
        
            if($result == 1) {
                echo '{"result":"success"}';
            }
            else {
                echo '{"result":"fail", "errorCode": "00", "errorMessage": "'.$result.'"}';
            }
        }
        else {
            echo '{"result":"fail", "errorCode": "01", "errorMessage": "Email exist"}';
        }
    }
    else {
        echo '{"result":"fail", "errorCode": "02", "errorMessage": "Please fill in all fields."}';
    }
    
}
catch (Exception $e) {
    echo '{"result":"fail", "errorCode": "00", "errorMessage": "'.$e->getMessage().'"}';
}


?>