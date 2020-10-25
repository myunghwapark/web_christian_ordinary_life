<?php
require_once('../../common/header.php');
require_once('../../database/reading_bible_query.php');

try {
    $language = $obj['language'];
    $biblePlanId = $obj['biblePlanId'];

    $result = getBiblePlan($language, $biblePlanId);
    $numResults = mysqli_num_rows($result);
    
	$counter = 0;
    
    echo '{"result":"success", "biblePlanList": [';
	if ($result->num_rows > 0) {
		while($row = mysqli_fetch_assoc($result)) {
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