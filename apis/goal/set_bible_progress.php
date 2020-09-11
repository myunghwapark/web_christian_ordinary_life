<?php

try {
	require('../../database/database.php');
    require('../../database/goal_query.php');
    require('../../database/reading_bible_query.php');
    require('../../common/error_message.php');

    $json = file_get_contents('php://input');
    $obj = json_decode($json,true);
	
    $userSeqNo = $obj['userSeqNo'];
    $goalDate = $obj['goalDate'];
    $readingBible = $obj['readingBible'];
    $bibleProgress = $obj['bibleProgress'];
    $bibleProgressDone = $obj['bibleProgressDone'];
    $bibleDays = $obj['bibleDays'];
    $lastDay = $obj['lastDay'];
    $userBiblePlanSeqNo = $obj['userBiblePlanSeqNo'];


	$getBibleProgressResult = getBibleProgress($userSeqNo, $userBiblePlanSeqNo, $bibleDays);
	$numBibleProgressResult = mysqli_num_rows($getBibleProgressResult);
	
    $bibleProgressResult;

	if($numBibleProgressResult > 0) {

        $bibleProgressResult = updateBibleProgress($userSeqNo, $goalDate, $bibleProgress, $bibleProgressDone, $bibleDays, $userBiblePlanSeqNo);

    }
    else {

        $bibleProgressResult = insertBibleProgress($userSeqNo, $goalDate, $bibleProgress, $bibleProgressDone, $bibleDays, $userBiblePlanSeqNo);

    }

    $result = 1;
    if($readingBible == 'y') {

        $getGoalResult = getGoalProgress($userSeqNo, $goalDate);
        $numGetGoalResults = mysqli_num_rows($getGoalResult);
    
        if($numGetGoalResults > 0) {
            $result = updateReadingBible($userSeqNo, $goalDate, $readingBible);
        }
        else {
            $result = insertReadingBible($userSeqNo, $goalDate, $readingBible);
        }

    }

    $statusUpdateResult = 1;
    //echo ' readingBible: '.$readingBible.' bibleDays: '.$bibleDays.' lastDay: '.$lastDay;
    // Bible plan done
    if($bibleProgressDone == 'y' && ($bibleDays == $lastDay)) {
        $statusUpdateResult = updateUserBiblePlanStatus($userSeqNo, 'P002_002', $userBiblePlanSeqNo);
    }

    if($bibleProgressResult == 1 && $result == 1 && $statusUpdateResult == 1) {
        echo '{"result":"success"}';
    }
    else {
        echo '{"result":"fail", "errorCode": "'.$commonError["code"].'", "errorMessage": "'.$commonError["message"].'"}';
    }

}
catch (Exception $e) {
    echo '{"result":"fail", "errorCode": "00", "errorMessage": "'.$e->getMessage().'"}';
}
    
?>