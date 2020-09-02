<?php

try {
	require('../../database/database.php');
    require('../../database/reading_bible_query.php');

    $json = file_get_contents('php://input');
    $obj = json_decode($json,true);
	
    $language = $obj['language'];
    $book = $obj['book'];
    $chapter = $obj['chapter'];

    $listResult;
    if($language == 'ko') {
        $listResult = getBibleHRV($book, $chapter);
    }
    else {
        $listResult = getBibleKJV($book, $chapter);
    }
    
    $numResults = mysqli_num_rows($listResult);
    
    $counter = 0;
    
    echo '{"result":"success", "list": [';
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