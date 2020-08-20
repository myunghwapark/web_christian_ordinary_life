<?php

try {
	require('../../database/database.php');
	require('../../database/goal_query.php');
	require('../../database/reading_bible_query.php');
    require('../../common/error_message.php');

    // Getting the received JSON into $json variable.
    $json = file_get_contents('php://input');
 
    // Decoding the received JSON and store into $obj variable.
    $obj = json_decode($json,true);


    $userSeqNo = '3';
    $readingBible = true;
    $thankDiary = true;
    $qtRecord = true;
    $qtAlarm = false;
    $qtTime = '07:30';
    $praying = false;
    $prayingAlarm = null;
    $prayingTime = null;
    $prayingDuration = null;

    $biblePlanId = 'custom';
    $planPeriod = '5';
    $customBible = '[{ "bible": "ezek", "volume":"48" }, { "bible": "est", "volume":"10" }, { "bible": "2chron", "volume":"36" }, { "bible": "1kings", "volume":"22" }]';
    $planEndDate = '2020-08-25 00:00:00';

    /* 
    $userSeqNo = $obj['userSeqNo'];
    $readingBible = $obj['readingBible'];
    $thankDiary = $obj['thankDiary'];
    $qtRecord = $obj['qtRecord'];
    $qtAlarm = $obj['qtAlarm'];
    $qtTime = $obj['qtTime'];
    $praying = $obj['praying'];
    $prayingAlarm = $obj['prayingAlarm'];
    $prayingTime = $obj['prayingTime'];
    $prayingDuration = $obj['prayingDuration'];

    $biblePlanId = $obj['biblePlanId'];
    $planPeriod = $obj['planPeriod'];
    $customBible = $obj['customBible'];
    $planEndDate = $obj['planEndDate']; */


	$getGoalResult = getUserGoal($userSeqNo);
	$goalResultCnt = mysqli_num_rows($getGoalResult);
	
    $counter = 0;

    // Goal setting (If there is goal for the user, insert otherwise update.)
	if($goalResultCnt == 0) {

        $setGoalResult = setUserGoal($userSeqNo, $readingBible, $thankDiary, $qtRecord, $qtAlarm, $qtTime, $praying, $prayingAlarm, $prayingTime, $prayingDuration);

        if($setGoalResult == 1) {
            echo '{"result":"success"}';
        }
        else {
            echo '{"result":"fail", "errorCode": "'.$commonError["code"].'", "errorMessage": "'.$commonError["message"].'"}';
        }
    }
    else {
        $updateGoalResult = updateUserGoal($userSeqNo, $readingBible, $thankDiary, $qtRecord, $qtAlarm, $qtTime, $praying, $prayingAlarm, $prayingTime, $prayingDuration);


        if($updateGoalResult == 1) {
            echo '{"result":"success"}';
        }
        else {
            echo '{"result":"fail", "errorCode": "'.$commonError["code"].'", "errorMessage": "'.$commonError["message"].'"}';
        }
    }

    $getBiblePlanResult = getUserBiblePlanSeqNo($userSeqNo);
    $biblePlanResultCnt = mysqli_num_rows($getBiblePlanResult);
    
    // Case of when there is a old bible plan
    if($biblePlanResultCnt != 0) {
        // update needed
        $userBiblePlanSeqNo = "";
        while($row = mysqli_fetch_array($getBiblePlanResult)){
            $userBiblePlanSeqNo = $row['userBiblePlanSeqNo'];
        }
    
        $oldBiblePlanStatus = 'P002_003'; // Cancel
        updateUserBiblePlanStatus($userSeqNo, $oldBiblePlanStatus, $userBiblePlanSeqNo);
    }

    // Case of when user set reading bible
    if($readingBible == true || $readingBible == 'true') {

        $setBiblePlanResult = setUserBiblePlan($userSeqNo, $biblePlanId, $planPeriod, $customBible, $planEndDate);

        if($setBiblePlanResult == 1) {
            echo '{"result":"success"}';
        }
        else {
            echo '{"result":"fail", "errorCode": "'.$commonError["code"].'", "errorMessage": "'.$commonError["message"].'"}';
        }

    }
        
    
    if(($readingBible == true || $readingBible == 'true') && $biblePlanId == 'custom') {

        $newBiblePlanSeqNo = '';
        $newBiblePlanResult = getUserBiblePlanSeqNo($userSeqNo);
        while($row = mysqli_fetch_array($newBiblePlanResult)){
            $newBiblePlanSeqNo = $row['userBiblePlanSeqNo'];
        }

        $customBibleList = json_decode($customBible, true);
        foreach ($customBibleList as $key => $value) {
            //setUserBibleCustom($newBiblePlanSeqNo, $value["volume"], $value["chapter"]);
          }
    }
 

}
catch (Exception $e) {
    echo '{"result":"fail", "errorCode": "00", "errorMessage": "'.$e->getMessage().'"}';
}


?>