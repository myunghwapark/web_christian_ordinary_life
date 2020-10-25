<?php
require_once('../../common/header.php');
require_once('../../database/thank_diary_query.php');

try {
    $jwtCls = new Jwt();

    $userSeqNo = $obj['userSeqNo'];
    $searchKeyword = $obj['searchKeyword'];
    $searchStartDate = $obj['searchStartDate'];
    $searchEndDate = $obj['searchEndDate'];
    $categoryNo = $obj['categoryNo'];
    $startPageNum = $obj['startPageNum'];
    $rowCount = $obj['rowCount'];
    $keepLogin = $obj['keepLogin'];
    $jwt = $obj['jwt'];

    $auch = $jwtCls->dehashing($jwt);
    
    if($auch) {
            
        $jwt = $jwtCls->hashing($userSeqNo, $keepLogin);

        $listResult = getThankDiaryList($userSeqNo, $searchKeyword, $searchStartDate, $searchEndDate, $categoryNo, $startPageNum, $rowCount);
        
        $numResults = mysqli_num_rows($listResult);
        
        $totalCntResult = getThankDiaryTotalCnt($userSeqNo, $searchKeyword);
        $totalCntRow = mysqli_fetch_assoc($totalCntResult);
        $totalCnt = $totalCntRow['totalCnt'];

        $counter = 0;
        
        echo '{"result":"success", "jwt": "'.$jwt.'", "totalCnt": '.$totalCnt.', "diaryList": [';
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
}
catch (Exception $e) {
    echo '{"result":"fail", "errorCode": "00", "errorMessage": "'.$e->getMessage().'"}';
}
    
?>