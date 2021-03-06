<?php
require_once('../../common/header.php');
require_once('../../database/thank_diary_query.php');

try {
    $jwtCls = new Jwt();

    $userSeqNo = $obj['userSeqNo'];
    $thankDiarySeqNo = $obj['thankDiarySeqNo'];
	$diaryDate = $obj['diaryDate'];
	$language = $obj['language'];
	
    $keepLogin = $obj['keepLogin'];
    $jwt = $obj['jwt'];

    $auch = $jwtCls->dehashing($jwt, $userSeqNo);
    
    if($auch) {
            
        $jwt = $jwtCls->hashing($userSeqNo, $keepLogin);
	
		$detailResult;
		$deatilNumResults;
		if($thankDiarySeqNo != null && $thankDiarySeqNo != '') {
			$detailResult = getThankDiaryBySeqNo($thankDiarySeqNo, $language);
			$deatilNumResults = mysqli_num_rows($detailResult);
		}
		else {
			$detailResult = getThankDiaryByDiaryDate($diaryDate, $language);
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

}
catch (Exception $e) {
    echo '{"result":"fail", "errorCode": "00", "errorMessage": "'.$e->getMessage().'"}';
}
    
?>