<?php
require_once $_SERVER["DOCUMENT_ROOT"].'/col/common/header.php';
require('../../database/user_query.php');


try {
    $jwtCls = new Jwt();

	$userEmail = $obj['userEmail'];
    $userPassword = $obj['userPassword'];
    $keepLogin = $obj['keepLogin'];

	$result = getUser($userEmail, $userPassword);
	$numResults = mysqli_num_rows($result);
	
	if($numResults > 0) {
       
        $userName = "";
        while($row = mysqli_fetch_array($result)){
            $userSeqNo = $row['user_seq_no'];
            $userName = $row['user_name'];
        }
        $jwt = $jwtCls->hashing($userSeqNo, $keepLogin);

        echo '{"result":"success", "jwt": "'.$jwt.'", "seqNo": "'.$userSeqNo.'", "name":"'.$userName.'", "email": "'.$userEmail.'"}';

	}
	else {
		echo '{"result":"fail", "errorCode": "'.$commonError["code"].'", "errorMessage":"'.$commonError["message"].'"}';
	}
}
catch (Exception $e) {
    echo '{"result":"fail", "errorCode": "00", "errorMessage": "'.$e->getMessage().'"}';
}



?>