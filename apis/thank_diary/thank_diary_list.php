<?php

try {
	require('../../database/database.php');
    require('../../database/thank_diary_query.php');

    $json = file_get_contents('php://input');
    $obj = json_decode($json,true);
	
    $userSeqNo = $obj['userSeqNo'];
    $searchKeyword = $obj['searchKeyword'];
    $startPageNum = $obj['startPageNum'];
    $rowCount = $obj['rowCount'];

    $listResult = getThankDiaryList($userSeqNo, $searchKeyword, $startPageNum, $rowCount);
    
    $numResults = mysqli_num_rows($listResult);
    
    $totalCntResult = getThankDiaryTotalCnt($userSeqNo, $searchKeyword);
    $totalCntRow = mysqli_fetch_assoc($totalCntResult);
    $totalCnt = $totalCntRow['totalCnt'];

    $counter = 0;
    
    echo '{"result":"success", "totalCnt": '.$totalCnt.', "diaryList": [';
	if ($listResult->num_rows > 0) {
		while($row = mysqli_fetch_assoc($listResult)) {
			echo json_encode($row);
			if (++$counter != $numResults) {
				echo',';
			}
		}
	}
	echo ']}';
}
catch (Exception $e) {
    echo '{"result":"fail", "errorCode": "00", "errorMessage": "'.$e->getMessage().'"}';
}
    
?>