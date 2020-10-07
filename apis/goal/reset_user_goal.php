<?php

try {
	require('../../database/database.php');
    require('../../database/goal_query.php');
    require('../../database/reading_bible_query.php');
    require('../../common/error_message.php');

    $json = file_get_contents('php://input');
    $obj = json_decode($json,true);

    $userSeqNo = $obj['userSeqNo'];
     
    if($userSeqNo != null && $userSeqNo != '') {

        $getGoalResult = getUserGoal($userSeqNo);
        $goalResultCnt = mysqli_num_rows($getGoalResult);

        if($goalResultCnt != 0) {
            $deleteUserGoalResult = deleteUserGoal($userSeqNo);
            $biblePlanStatus = 'P002_003'; //Cancel
            $updateBiblePlanResult = updateAllUserBiblePlanStatus($userSeqNo, $biblePlanStatus);

            if($deleteUserGoalResult == 1 && $updateBiblePlanResult == 1) {
                echo '{"result":"success"}';
            }
            else {
                echo '{"result":"fail", "errorCode": "03", "errorMessage": "'.$commonError["message"].'"}';
            }
        }
        else {
            echo '{"result":"fail", "errorCode": "02", "There is no goal set."}';
        }
        
    }
    else {
        echo '{"result":"fail", "errorCode": "01", "There is no user logged in."}';
    }

}
catch (Exception $e) {
    echo '{"result":"fail", "errorCode": "00", "errorMessage": "'.$e->getMessage().'"}';
}
    
?>