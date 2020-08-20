<?php

try {
	require('../../database/database.php');
    require('../../database/qt_record_query.php');

    $json = file_get_contents('php://input');
    $obj = json_decode($json,true);
	
    $qtRecordSeqNo = $obj['qtRecordSeqNo'];
    
    $detailResult = getQtRecord($qtRecordSeqNo);
    $deatilNumResults = mysqli_num_rows($detailResult);
    
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