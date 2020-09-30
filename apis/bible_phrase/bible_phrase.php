<?php

try {
	require('../../database/database.php');
    require('../../database/bible_phrase_query.php');

    $json = file_get_contents('php://input');
    $obj = json_decode($json,true);
	
    $language = $obj['language'];

    $listResult = getPhrase($language);
    
    $numResults = mysqli_num_rows($listResult);
    
	$counter = 0;
    echo '{"result":"success", "biblePhrase": [';
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