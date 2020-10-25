<?php
require_once('../../common/header.php');
require_once('../../database/user_query.php');


try {
    $jwtCls = new Jwt();
 
	$userEmail = $obj['userEmail'];
    $userPassword = $obj['userPassword'];
    $keepLogin = $obj['keepLogin'];
 
/* 
	$userEmail = $_GET['userEmail'];
    $userPassword = $_GET['userPassword'];
    $keepLogin = $_GET['keepLogin'];
 */
	$result = getUser($userEmail);
	$numResults = mysqli_num_rows($result);
	
	if($numResults > 0) {

        $userName = "";
        $savedPassword = "";
        while($row = mysqli_fetch_assoc($result)){
            $userSeqNo = $row['user_seq_no'];
            $userName = $row['user_name'];
            $savedPassword = $row['user_password'];
        }

        if (password_verify($userPassword, $savedPassword))
        {
       
            $jwt = $jwtCls->hashing($userSeqNo, $keepLogin);
    
            echo '{"result":"success", "jwt": "'.$jwt.'", "seqNo": "'.$userSeqNo.'", "name":"'.$userName.'", "email": "'.$userEmail.'"}';
        }
        else {
            echo '{"result":"fail", "errorCode": "02", "errorMessage": "There is no user matching the registered email or password."}';
        }

	}
	else {
		echo '{"result":"fail", "errorCode": "01", "errorMessage":"'.$commonError["message"].'"}';
	}
}
catch (Exception $e) {
    echo '{"result":"fail", "errorCode": "00", "errorMessage": "'.$e->getMessage().'"}';
}



?>