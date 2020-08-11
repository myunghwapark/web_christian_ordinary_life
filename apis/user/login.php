<?php

try {
	require('../../database/database.php');
    require('../../database/user_query.php');
    require('../../common/error_message.php');
    //require('jwt.php');

    $json = file_get_contents('php://input');
    $obj = json_decode($json,true);
	
	$userEmail = $obj['userEmail'];
    $userPassword = $obj['userPassword'];

	$result = getUser($userEmail, $userPassword);
	$numResults = mysqli_num_rows($result);
	
	if($numResults > 0) {
/*
        $jwt = new jwt();

        $token = $jwt->hashing(array(
                'sub'=>'1234567890',
                'email'=>$userEmail,
                'iat'=>time()
        ));
        var_dump($token);
*/
        $userName = "";
        while($row = mysqli_fetch_array($result)){
            $userSeqNo = $row['user_seq_no'];
            $userName = $row['user_name'];
        }
		echo '{"result":"success", "seqNo": "'.$userSeqNo.'", "name":"'.$userName.'", "email": "'.$userEmail.'"}';
	}
	else {
		echo '{"result":"fail", "errorCode": "'.$commonError["code"].'", "errorMessage":"'.$commonError["message"].'"}';
	}
}
catch (Exception $e) {
    echo '{"result":"fail", "errorCode": "00", "errorMessage": "'.$e->getMessage().'"}';
}



?>