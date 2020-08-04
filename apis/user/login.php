<?php

	require('../../database/database.php');
    require('../../database/user_query.php');

    $json = file_get_contents('php://input');
    $obj = json_decode($json,true);
	
	$userEmail = $obj['userEmail'];
	$userPassword = $obj['userPassword'];

	$result = getUser($userEmail, $userPassword);
	$numResults = mysqli_num_rows($result);
	

	if($numResults > 0) {
		echo '{"result":"success"}';
	}
	else {
		echo '{"result":"fail"}';
	}



?>