<?php
require('../../common/header.php');
require('../../database/bible_phrase_query.php');

try {
	
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