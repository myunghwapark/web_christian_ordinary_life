<?php
require_once('../../common/header.php');
require_once('../../database/thank_diary_query.php');

try {
	
    $thankDiarySeqNo = $obj['thankDiarySeqNo'];
    $diaryDate = $obj['diaryDate'];
	
	$detailResult;
	$deatilNumResults;
	if($thankDiarySeqNo != null && $thankDiarySeqNo != '') {
		$detailResult = getThankDiaryBySeqNo($thankDiarySeqNo);
    	$deatilNumResults = mysqli_num_rows($detailResult);
	}
	else {
		$detailResult = getThankDiaryByDiaryDate($diaryDate);
    	$deatilNumResults = mysqli_num_rows($detailResult);
	}
    
    
	$counter = 0;
	echo '{"result":"success", "detail": [';	
	if ($detailResult->num_rows > 0) {
		while($row = mysqli_fetch_assoc($detailResult)) {
			echo json_encode($row);
			if (++$counter != $deatilNumResults) {
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