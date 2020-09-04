<?php

try {
	require('../../database/database.php');
    require('../../database/goal_query.php');
    require('../../database/reading_bible_query.php');

    $json = file_get_contents('php://input');
    $obj = json_decode($json,true);
	
    $userSeqNo = $obj['userSeqNo'];
    $goalDate = $obj['goalDate'];
    $bibleDays = '';
    $latestGoalDate = '';
    $bibleProgress = '';
    $readingBible = '';
    $biblePlanId = '';

    $days = '';
    $chapter = '';

    
	$readingBibleYn = checkBibleSelected($userSeqNo);
	$numReadingBibleYn = mysqli_num_rows($readingBibleYn);
    
	if($numReadingBibleYn > 0) {

        while($row = mysqli_fetch_array($readingBibleYn)){
            $biblePlanId = $row['biblePlanId'];
        }

        if($biblePlanId == null) {
            echo '{"result":"fail", "errorCode": "02", "errorMessage": "biblePlanId dosen exist."}';
        }
        else {

            $latestGoalInfo = getLatestBibleGoalProgress($userSeqNo);
            $numLatestGoalInfo = mysqli_num_rows($latestGoalInfo);
            
            if($numLatestGoalInfo > 0) {

                while($row = mysqli_fetch_array($latestGoalInfo)){
                    $bibleDays = $row['bibleDays'];
                    $bibleProgress = $row['bibleProgress'];
                    $readingBible = $row['readingBible'];
                }

                // $goalDate != $latestGoalDate && 
                // When a user has finished reading the Bible for that day, let him read it for the next day.
                // Date comparisons are not made so that the Bible can be read ahead.
                if($readingBible == 'y') {
                    $bibleDays = ((int)$bibleDays + 1);
                    $bibleProgress = 0;
                }
            }
            // Because there is no record of bible reading, it begins from start.
            else {
                $bibleDays = 1;
                $bibleProgress = 0;
            }

            $result;
            // Get volume to read according to biblePlanId
            if($biblePlanId == 'custom') {
                $result = getTodaysBibleCustom($userSeqNo, $biblePlanId, $bibleDays);
            }
            else {
                $result = getTodaysBible($biblePlanId, $bibleDays);
            }
            $numResult = mysqli_num_rows($result);
            if($numResult > 0) {
                while($row = mysqli_fetch_array($result)){
                    $days = $row['days'];
                    $chapter = $row['chapter'];
                }
                echo '{"result":"success", "days": "'.$days.'", "chapter": '.json_encode($chapter).', "biblePlanId": "'.$biblePlanId.'", "bibleProgress": "'.$bibleProgress.'"}';
            }
            else {
                echo '{"result":"fail", "errorCode": "03", "errorMessage": "Can not find the chapter for today."}';
            }

        }

    }
    else {
        echo '{"result":"fail", "errorCode": "01", "errorMessage": "biblePlan dosen exist."}';
    }
    
}
catch (Exception $e) {
    echo '{"result":"fail", "errorCode": "00", "errorMessage": "'.$e->getMessage().'"}';
}
    
?>