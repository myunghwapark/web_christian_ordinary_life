<?php
require('../../common/header.php');
require('../../database/goal_query.php');
require('../../database/reading_bible_query.php');

try {
    $jwtCls = new Jwt();

    $userSeqNo = $obj['userSeqNo'];
    $keepLogin = $obj['keepLogin'];
    $jwt = $obj['jwt'];

    $auch = $jwtCls->dehashing($jwt);
    
    if($auch) {
            
        $jwt = $jwtCls->hashing($userSeqNo, $keepLogin);
     
        if($userSeqNo != null && $userSeqNo != '') {

            $getGoalResult = getUserGoal($userSeqNo);
            $goalResultCnt = mysqli_num_rows($getGoalResult);

            if($goalResultCnt != 0) {
                $deleteUserGoalResult = deleteUserGoal($userSeqNo);
                $biblePlanStatus = 'P002_003'; //Cancel
                $updateBiblePlanResult = updateAllUserBiblePlanStatus($userSeqNo, $biblePlanStatus);


                insertUserGoalHistory($userSeqNo, null, null, null, null, null, null, null, null, null, null);

                if($deleteUserGoalResult == 1 && $updateBiblePlanResult == 1) {
                    echo '{"result":"success", "jwt": "'.$jwt.'"}';
                }
                else {
                    echo '{"result":"fail", "jwt": "'.$jwt.'", "errorCode": "03", "errorMessage": "'.$commonError["message"].'"}';
                }
            }
            else {
                echo '{"result":"fail", "jwt": "'.$jwt.'", "errorCode": "02", "errorMessage": "There is no goal set."}';
            }
            
        }
        else {
            echo '{"result":"fail", "errorCode": "01",  "errorMessage": "There is no user logged in."}';
        }
    }

}
catch (Exception $e) {
    echo '{"result":"fail", "errorCode": "00", "errorMessage": "'.$e->getMessage().'"}';
}
    
?>