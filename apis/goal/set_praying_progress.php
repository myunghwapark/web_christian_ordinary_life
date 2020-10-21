<?php
require_once $_SERVER["DOCUMENT_ROOT"].'/col/common/header.php';
require('../../database/goal_query.php');

try {
    $jwtCls = new Jwt();
	
    $userSeqNo = $obj['userSeqNo'];
    $goalDate = $obj['goalDate'];
    $praying = $obj['praying'];
    $keepLogin = $obj['keepLogin'];
    $jwt = $obj['jwt'];

    $auch = $jwtCls->dehashing($jwt);

    if($auch) {
            
        $jwt = $jwtCls->hashing($userSeqNo, $keepLogin);

        $getGoalResult = getGoalProgress($userSeqNo, $goalDate);
        $numGetGoalResults = mysqli_num_rows($getGoalResult);
        
        $counter = 0;

        if($numGetGoalResults > 0) {

            $result = updatePrayingProgress($userSeqNo, $goalDate, $praying);

            if($result == 1) {
                echo '{"result":"success", "jwt": "'.$jwt.'"}';
            }
            else {
                echo '{"result":"fail", "jwt": "'.$jwt.'", "errorCode": "'.$commonError["code"].'", "errorMessage": "'.$commonError["message"].'"}';
            }
        }
        else {

            $userGoalResult = getUserGoal($userSeqNo);
            $numUserGoalResult = mysqli_num_rows($userGoalResult);
        
        
            $readingBible = "";
            $thankDiary = "";
            $qtRecord = "";
            if($numUserGoalResult > 0) {
                while($row = mysqli_fetch_array($userGoalResult)){
                    $readingBible = $row['readingBible'] != 'true' ? '-' : 'n';
                    $thankDiary = $row['thankDiary'] != 'true' ? '-' : 'n';
                    $qtRecord = $row['qtRecord'] != 'true' ? '-' : 'n';
                }
            }

            $result = setGoalProgress($userSeqNo, $goalDate, $readingBible, $thankDiary, $qtRecord, $praying);

            if($result == 1) {
                echo '{"result":"success", "jwt": "'.$jwt.'"}';
            }
            else {
                echo '{"result":"fail", "jwt": "'.$jwt.'", "errorCode": "'.$commonError["code"].'", "errorMessage": "'.$commonError["message"].'"}';
            }
        }
    }

}
catch (Exception $e) {
    echo '{"result":"fail", "errorCode": "00", "errorMessage": "'.$e->getMessage().'"}';
}
    
?>