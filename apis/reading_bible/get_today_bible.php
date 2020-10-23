<?php
require('../../common/header.php');
require('../../database/goal_query.php');
require('../../database/reading_bible_query.php');

try {
    $jwtCls = new Jwt();
	
    $userSeqNo = $obj['userSeqNo'];
    $goalDate = $obj['goalDate'];
    $bibleDays = '';
    $latestGoalDate = '';
    $bibleProgress = '';
    $bibleProgressDone = '';
    $readingBible = '';
    $biblePlanId = '';
    $userBiblePlanSeqNo = '';

    $days = '';
    $chapter = '';
    $keepLogin = $obj['keepLogin'];
    $jwt = $obj['jwt'];

    $auch = $jwtCls->dehashing($jwt);
    
    if($auch) {
            
        $jwt = $jwtCls->hashing($userSeqNo, $keepLogin);
        
        $readingBibleYn = checkBibleSelected($userSeqNo);
        $numReadingBibleYn = mysqli_num_rows($readingBibleYn);
        
        if($numReadingBibleYn > 0) {

            while($row = mysqli_fetch_array($readingBibleYn)){
                $biblePlanId = $row['biblePlanId'];
            }

            if($biblePlanId == null) {
                echo '{"result":"fail", "jwt": "'.$jwt.'", "errorCode": "02", "errorMessage": "biblePlanId dosen exist."}';
            }
            else {

                $biblePlanResult = getCurrentBiblePlanSeqNo();
                $numBiblePlanResult = mysqli_num_rows($biblePlanResult);

                if($numBiblePlanResult > 0) {

                    while($row = mysqli_fetch_array($biblePlanResult)){
                        $userBiblePlanSeqNo = $row['userBiblePlanSeqNo'];
                    }


                    $latestGoalInfo = getLatestBibleGoalProgress($userSeqNo, $userBiblePlanSeqNo);
                    $numLatestGoalInfo = mysqli_num_rows($latestGoalInfo);
                    
                    if($numLatestGoalInfo > 0) {

                        while($row = mysqli_fetch_array($latestGoalInfo)){
                            $bibleDays = $row['bibleDays'];
                            $bibleProgress = $row['bibleProgress'];
                            $bibleProgressDone = $row['bibleProgressDone'];
                        }

                        // $goalDate != $latestGoalDate && 
                        // When a user has finished reading the Bible for that day, let him read it for the next day.
                        // Date comparisons are not made so that the Bible can be read ahead.
                        if($bibleProgressDone == 'y') {
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
                    $lastDayResult;
                    // Get volume to read according to biblePlanId
                    if($biblePlanId == 'custom') {
                        $result = getTodaysBibleCustom($userSeqNo, $biblePlanId, $bibleDays);
                        $lastDayResult = getLastDayBibleCustom($userSeqNo, $biblePlanId);
                    }
                    else {
                        $result = getTodaysBible($biblePlanId, $bibleDays);
                        $lastDayResult = getLastDayBible($biblePlanId);
                    }

                    $lastDay;
                    $numLastDayResult = mysqli_num_rows($lastDayResult);
                    if($numLastDayResult > 0) {
                        while($row = mysqli_fetch_array($lastDayResult)){
                            $lastDay = $row['days'];
                        }
                    }


                    $numResult = mysqli_num_rows($result);
                    if($numResult > 0) {
                        while($row = mysqli_fetch_array($result)){
                            $days = $row['days'];
                            $chapter = $row['chapter'];
                        }
                        echo '{"result":"success", "jwt": "'.$jwt.'", "days": "'.$days.'", "chapter": '.json_encode($chapter).', "biblePlanId": "'.$biblePlanId.'", "bibleProgress": "'.$bibleProgress.'", "lastDay": "'.$lastDay.'"}';
                    }
                    else {
                        echo '{"result":"fail", "jwt": "'.$jwt.'", "errorCode": "03", "errorMessage": "Can not find the chapter for today."}';
                    }
                }
                else {
                    echo '{"result":"fail", "jwt": "'.$jwt.'", "errorCode": "04", "errorMessage": "There is no bible plan."}';
                }


            }

        }
        else {
            echo '{"result":"fail", "jwt": "'.$jwt.'", "errorCode": "01", "errorMessage": "biblePlan dosen exist."}';
        }
    }
    
}
catch (Exception $e) {
    echo '{"result":"fail", "errorCode": "00", "errorMessage": "'.$e->getMessage().'"}';
}
    
?>