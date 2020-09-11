<?php

try {
	require('../../database/database.php');
    require('../../database/goal_query.php');

    $json = file_get_contents('php://input');
    $obj = json_decode($json,true);
	
    $userSeqNo = $obj['userSeqNo'];
    $yearMonth = $obj['yearMonth'];

	$result = getMonthGoalProgress($userSeqNo, $yearMonth);
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
catch (Exception $e) {
    echo '{"result":"fail", "errorCode": "00", "errorMessage": "'.$e->getMessage().'"}';
}
    
?>