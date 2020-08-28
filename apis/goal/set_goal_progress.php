<?php

try {
	require('../../database/database.php');
    require('../../database/goal_query.php');
    require('../../common/error_message.php');

    $json = file_get_contents('php://input');
    $obj = json_decode($json,true);
	
    $userSeqNo = $obj['userSeqNo'];
    $goalDate = $obj['goalDate'];
    $readingBible = $obj['readingBible'];
    $thankDiary = $obj['thankDiary'];
    $qtRecord = $obj['qtRecord'];
    $praying = $obj['praying'];


	$getGoalResult = getGoalProgress($userSeqNo, $goalDate);
	$numGetGoalResults = mysqli_num_rows($getGoalResult);
	
    $counter = 0;

	if($numGetGoalResults > 0) {

        $result = updateGoalProgress($userSeqNo, $goalDate, $readingBible, $thankDiary, $qtRecord, $praying) ;

        if($result == 1) {
            echo '{"result":"success"}';
        }
        else {
            echo '{"result":"fail", "errorCode": "'.$commonError["code"].'", "errorMessage": "'.$commonError["message"].'"}';
        }
    }
    else {

        $result = setGoalProgress($userSeqNo, $goalDate, $readingBible, $thankDiary, $qtRecord, $praying);

        if($result == 1) {
            echo '{"result":"success"}';
        }
        else {
            echo '{"result":"fail", "errorCode": "'.$commonError["code"].'", "errorMessage": "'.$commonError["message"].'"}';
        }
    }

}
catch (Exception $e) {
    echo '{"result":"fail", "errorCode": "00", "errorMessage": "'.$e->getMessage().'"}';
}
    
?>