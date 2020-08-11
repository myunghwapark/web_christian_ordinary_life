<?php

try {
	require('../../database/database.php');
	require('../../database/goal_query.php');
    require('../../common/error_message.php');

    // Getting the received JSON into $json variable.
    $json = file_get_contents('php://input');
 
    // Decoding the received JSON and store into $obj variable.
    $obj = json_decode($json,true);

    
    $userSeqNo = $obj['userSeqNo'];
    $readingBible = $obj['readingBible'];
    $thankDiary = $obj['thankDiary'];
    $qtRecord = $obj['qtRecord'];
    $praying = $obj['praying'];
    $prayingTime = $obj['prayingTime'];
    $prayingDuration = $obj['prayingDuration'];


	$getGoalResult = getUserGoal($userSeqNo);
	$numGoalResult = mysqli_num_rows($getGoalResult);
	
    $counter = 0;

	if($numGoalResult == 0) {

        $setGoalResult = setUserGoal($userSeqNo, $readingBible, $thankDiary, $qtRecord, $praying, $prayingTime, $prayingDuration);

        if($setGoalResult == 1) {
            echo '{"result":"success"}';
        }
        else {
            echo '{"result":"fail", "errorCode": "'.$commonError["code"].'", "'.$commonError["message"].'"}';
        }
    }
    else {
        $updateGoalResult = updateUserGoal($userSeqNo, $readingBible, $thankDiary, $qtRecord, $praying, $prayingTime, $prayingDuration);


        if($updateGoalResult == 1) {
            echo '{"result":"success"}';
        }
        else {
            echo '{"result":"fail", "errorCode": "'.$commonError["code"].'", "'.$commonError["message"].'"}';
        }
    }
    

}
catch (Exception $e) {
    echo '{"result":"fail", "errorCode": "00", "errorMessage": "'.$e->getMessage().'"}';
}


?>