<?php
require_once('../../common/header.php');
require_once('../../database/goal_query.php');

try {
    $jwtCls = new Jwt();
	
    $userSeqNo = $obj['userSeqNo'];
    $goalDate = $obj['goalDate'];

    $keepLogin = $obj['keepLogin'];
    $jwt = $obj['jwt'];

    $auch = $jwtCls->dehashing($jwt, $userSeqNo);
    
    if($auch) {

        $result = getGoalProgress($userSeqNo, $goalDate);
        $numResults = mysqli_num_rows($result);
        
        $counter = 0;

        if($numResults > 0) {

            echo '{"result":"success", "goalProgress":[';

            $goalSeqNo = "";
            $readingBible = "";
            while($row = mysqli_fetch_assoc($result)){
                echo json_encode($row);
                if (++$counter != $numResults) {
                    echo",";
                }
            }
            echo ']}';
        }
        else {
            echo '{"result":"fail", "errorCode": "01", "errorMessage": "There are no goals in progress."}';
        }

    }

}
catch (Exception $e) {
    echo '{"result":"fail", "errorCode": "00", "errorMessage": "'.$e->getMessage().'"}';
}
    
?>