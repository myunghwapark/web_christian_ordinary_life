<?php
require_once $_SERVER["DOCUMENT_ROOT"].'/col/common/header.php';
require('../../database/reading_bible_query.php');

try {
	
    $biblePlanId = $obj['biblePlanId'];

    $listResult = getBiblePlanDetail($biblePlanId);
    $numResults = mysqli_num_rows($listResult);
    
	$counter = 0;
    
    echo '{"result":"success", "biblePlanDetail": [';
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