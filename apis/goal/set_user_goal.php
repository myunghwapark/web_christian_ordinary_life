<?php
require('../../common/header.php');
require('../../database/goal_query.php');
require('../../database/reading_bible_query.php');

try {

/* 
    $userSeqNo = '3';
    $readingBible = true;
    $thankDiary = true;
    $qtRecord = true;
    $qtAlarm = false;
    $qtTime = '07:30';
    $praying = false;
    $prayingAlarm = null;
    $prayingTime = null;
    $prayingDuration = null;

    $biblePlanId = 'custom';
    $planPeriod = '3';

    $customBible = '[{ "book": "ezek", "volume":"48" }, { "book": "est", "volume":"10" }, { "book": "2chron", "volume":"36" }, { "book": "1kings", "volume":"22" }]';
    //$customBible = '[{ "book": "gen", "volume":"50" }, { "book": "num", "volume":"36" }, { "book": "judg", "volume":"21" }, { "book": "2sam", "volume":"24" }]';
    //$customBible = '[{ "book": "judg", "volume":"21" }, { "book": "ex", "volume":"40" }, { "book": "deut", "volume":"34" }, { "book": "1kings", "volume":"22" }, { "book": "mark", "volume":"16" }, { "book": "acts", "volume":"28" }, { "book": "gal", "volume":"6" }]';
    //$customBible = '[{ "book": "gal", "volume":"6" }, { "book": "jonah", "volume":"4" }, { "book": "dan", "volume":"12" }, { "book": "isa", "volume":"66" }, { "book": "acts", "volume":"28" }, { "book": "james", "volume":"5" }, { "book": "1john", "volume":"5" }, { "book": "2john", "volume":"1" }, { "book": "3john", "volume":"1" }]';
    $planEndDate = '2020-08-25 00:00:00';
  */
    $jwtCls = new Jwt();
     
    $userSeqNo = $obj['userSeqNo'];
    $readingBible = $obj['readingBible'];
    $thankDiary = $obj['thankDiary'];
    $qtRecord = $obj['qtRecord'];
    $qtAlarm = $obj['qtAlarm'];
    $qtTime = $obj['qtTime'];
    $praying = $obj['praying'];
    $prayingAlarm = $obj['prayingAlarm'];
    $prayingTime = $obj['prayingTime'];
    $prayingDuration = $obj['prayingDuration'];

    $biblePlanId = $obj['biblePlanId'];
    $planPeriod = $obj['planPeriod'];
    $customBible = $obj['customBible'];
    $planEndDate = $obj['planEndDate']; 
    $keepLogin = $obj['keepLogin'];
    $jwt = $obj['jwt'];

    $auch = $jwtCls->dehashing($jwt);
    
    if($auch) {
            
        $jwt = $jwtCls->hashing($userSeqNo, $keepLogin);
 
        //echo 'customBible: '.$customBible;
        //echo 'planPeriod: '.$planPeriod;


        $getGoalResult = getUserGoal($userSeqNo);
        $goalResultCnt = mysqli_num_rows($getGoalResult);
        
        $counter = 0;
        $setGoalResult;
        $setBiblePlanResult;

        // To return the results to the app
        $goalResultStatus = true;
        $statusUpdateStatus = true;
        $biblePlanStatus = true;
        $customBibleStatus = true;

        // Goal setting (If there is goal for the user, insert otherwise update.)
        if($goalResultCnt == 0) {

            $setGoalResult = setUserGoal($userSeqNo, $readingBible, $thankDiary, $qtRecord, $qtAlarm, $qtTime, $praying, $prayingAlarm, $prayingTime, $prayingDuration);
    
            if($setGoalResult == 1) {
                $goalResultStatus = true;
            }
            else {
                $goalResultErrorMessage = '{"result":"fail", "errorCode": "'.$commonError["code"].'", "errorMessage": "'.$commonError["message"].'"}';
                $goalResultStatus = false;
            } 
        }
        else {
            $setGoalResult = updateUserGoal($userSeqNo, $readingBible, $thankDiary, $qtRecord, $qtAlarm, $qtTime, $praying, $prayingAlarm, $prayingTime, $prayingDuration);

    
            if($setGoalResult == 1) {
                $goalResultStatus = true;
            }
            else {
                $goalResultStatus = false;
            } 
        }

        insertUserGoalHistory($userSeqNo, $readingBible, $thankDiary, $qtRecord, $qtAlarm, $qtTime, $praying, $prayingAlarm, $prayingTime, $prayingDuration, $biblePlanId);

        $getBiblePlanResult = getUserBiblePlanSeqNo($userSeqNo);
        $biblePlanResultCnt = mysqli_num_rows($getBiblePlanResult);
        
        // Case of when there is a old bible plan
        if($biblePlanResultCnt != 0) {
            // update needed
            $userBiblePlanSeqNo = "";
            while($row = mysqli_fetch_array($getBiblePlanResult)){
                $userBiblePlanSeqNo = $row['userBiblePlanSeqNo'];
            }
        
            $oldBiblePlanStatus = 'P002_003'; // Cancel
            $statusUpdateResult = updateUserBiblePlanStatus($userSeqNo, $oldBiblePlanStatus, $userBiblePlanSeqNo);
            if($statusUpdateResult == 1) {
                $statusUpdateStatus = true;
            }
            else {
                $statusUpdateStatus = false;
            }
        }

        // Case of when user set reading bible
        if($readingBible == true || $readingBible == 'true') {

            $setBiblePlanResult = setUserBiblePlan($userSeqNo, $biblePlanId, $planPeriod, $customBible, $planEndDate);
    
            if($setBiblePlanResult == 1) {
                $biblePlanStatus = true;
            }
            else {
                $biblePlanStatus = false;
            } 

        }
            
        
        if(($readingBible == true || $readingBible == 'true') && $biblePlanId == 'custom') {

            $newBiblePlanSeqNo = '';
            $newBiblePlanResult = getUserBiblePlanSeqNo($userSeqNo);
            while($row = mysqli_fetch_array($newBiblePlanResult)){
                $newBiblePlanSeqNo = $row['userBiblePlanSeqNo'];
            }

            $totalVolume = 0;
            $chapterForDay = 0;
            $planPeriod = (int)$planPeriod;
            $chapterLeft = 0;

            $customBibleList = json_decode($customBible, true);
            foreach ($customBibleList as $key => $value) {
                $totalVolume += (int)$value["volume"];
            
            }
            $chapterForDay = ($totalVolume - ($totalVolume % $planPeriod)) / $planPeriod; 
            $chapterLeft = $totalVolume % $planPeriod;
            //echo 'totalVolume: '.$totalVolume;
            //echo 'chapterForDay: '.$chapterForDay;
            //echo 'chapterLeft: '.$chapterLeft;

            $startVolume = 1;
            $nextVolume = 0;
            $daysArray = new SplFixedArray($planPeriod);
            $arrayIndex = 0;

            $leftChapter = '';
            $leftChapterStart = 0;
            $volumeLeft = 0;    // 하루 읽을 분량을 읽고 남은 챕터
            $str = '';
            $last = false;
            $duplicateCheck = false;
            

            function getArrayLast() {
                global $arrayIndex, $planPeriod;
                if($arrayIndex == ($planPeriod - 1)) return true;
                else return false;
            }


            function getVolume($startVolume, $nextVolume) {
                if($nextVolume != 1)
                    $nextVolume--;

                $vStr = $startVolume.'-'.$nextVolume;
                if($startVolume == $nextVolume) $vStr = $startVolume;
                return $vStr;
            }

            // 남은 날은 첫번째 날부터 한장씩 추가해서 읽는다.
            function addChapterLeft($nextVolume) {
                global $chapterLeft, $duplicateCheck;
                if($chapterLeft != 0 && !$duplicateCheck) {
                    $nextVolume++;
                    $chapterLeft--;
                    $duplicateCheck = true;
                }
                return $nextVolume;
            }

            function checkChapterLeft($nextVolume) {
                global $chapterLeft, $duplicateCheck;
                if($chapterLeft != 0 && !$duplicateCheck) {
                    $nextVolume++;
                }
                return $nextVolume;
            }

            foreach ($customBibleList as $key => $value) {
                $curChapter = $value["book"];
                $curVolume = (int)$value["volume"];

                if( !next( $customBibleList ) )
                    $last = true;
                else $last = false;
                
                if($volumeLeft == 0) {
                    //echo ' #1 ';
                    $startVolume = 1;

                    if($curVolume == checkChapterLeft($chapterForDay)) { 
                        $nextVolume = $startVolume + $curVolume;
                        
                        $str = '[{"book": "'.$curChapter.'", "volume": "'.getVolume($startVolume, $nextVolume).'"}]';
                        
                        //echo ' #1-1 '.$str.' ';
                        $daysArray[$arrayIndex] = $str;
                        $arrayIndex++;
                        $volumeLeft = 0;
                        $duplicateCheck = false;
                        if($chapterLeft != 0) $chapterLeft--;
                    }
                    else if($curVolume > checkChapterLeft($chapterForDay)) {

                        $remainVolume = $curVolume;

                        while($chapterLeft != 0 && $remainVolume >= checkChapterLeft($chapterForDay)) {

                            $addVolume = addChapterLeft($chapterForDay);
                            $nextVolume = $startVolume + $addVolume;
                            
                            $str = '[{"book": "'.$curChapter.'", "volume": "'.getVolume($startVolume, $nextVolume).'"}]';
                            //echo ' #1-3 '.$str.' ';
                            $daysArray[$arrayIndex] = $str;
                            $arrayIndex++;
                            $startVolume = $nextVolume;
                            $duplicateCheck = false;

                            $remainVolume -= $addVolume;
                        }
                        

                        if($remainVolume != 0) {
                            $volume = ($remainVolume - ($remainVolume % $chapterForDay)) / $chapterForDay; 

                            // 남는 거 마지막에 추가
                            $volumeLeft = $remainVolume % $chapterForDay;
            
                            for($i=0;$i<$volume;$i++) {
                                $nextVolume = $startVolume + $chapterForDay;
                                
                                $str = '[{"book": "'.$curChapter.'", "volume": "'.getVolume($startVolume, $nextVolume).'"}]';
                                $daysArray[$arrayIndex] = $str;
                                $arrayIndex++;
                                $startVolume = $nextVolume;
                                $duplicateCheck = false;
                                //echo ' #1-4 '.$str.' ';
                            }
                            $leftChapterStart = $nextVolume;
        
                            if($volumeLeft != 0) {
                                $nextVolume = ($leftChapterStart + $volumeLeft);
        
                                $str = '[{"book": "'.$curChapter.'", "volume": "'.getVolume($leftChapterStart, $nextVolume).'"}';
                                if(getArrayLast()) {
                                    $str .= ']';
                                    $daysArray[$arrayIndex] = $str;
                                    $duplicateCheck = false;
                                }
                                $volumeLeft = ($chapterForDay - $volumeLeft);
                                //echo ' #1-5 '.$str.' ';
                            }
                        }
                        

                    }
                    else if($curVolume < checkChapterLeft($chapterForDay)) {
                        $nextVolume = $startVolume + $curVolume;

                        $str = '[{"book": "'.$curChapter.'", "volume": "'.getVolume($startVolume, $nextVolume).'"}';
                        $leftChapterStart = ($curVolume + 1);
                        $volumeLeft = (checkChapterLeft($chapterForDay) - $curVolume);
                        if($chapterLeft != 0 && !$duplicateCheck) {
                            $duplicateCheck = true;
                            $chapterLeft--;
                        }

                        if(getArrayLast()) {
                            $str .= ']';
                            $daysArray[$arrayIndex] = $str;
                            $duplicateCheck = false;
                        }
                        //echo ' #1-6 '.$str.' ';
                    }
                }
                else {

                    //echo ' #2 ';
                    $startVolume = 1;

                    if($curVolume == checkChapterLeft($volumeLeft)) {
                        $addVolume = addChapterLeft($volumeLeft);
                        $nextVolume = $startVolume + $addVolume;

                        $str .= ', {"book": "'.$curChapter.'", "volume": "'.getVolume($startVolume, $nextVolume).'"}]';
                        $daysArray[$arrayIndex] = $str;
                        $arrayIndex++;
                        $volumeLeft = 0;
                        $duplicateCheck = false;
                        //echo ' #2-1 '.$str;
                    }
                    else if($curVolume > checkChapterLeft($volumeLeft)) {
                        $addVolume = addChapterLeft($volumeLeft);
                        $nextVolume = $startVolume + $addVolume;

                        $str .= ', {"book": "'.$curChapter.'", "volume": "'.getVolume($startVolume, $nextVolume).'"}]';
                        $daysArray[$arrayIndex] = $str;
                        $arrayIndex++;
                        $duplicateCheck = false;
                        //echo ' #2-2 '.$str;

                        // 그래도 남는 것들 처리
                        $remainVolume = $curVolume - $addVolume;

                        $leftChapterStart = $nextVolume;
                        if($remainVolume == 0) {}
                        else if($remainVolume < checkChapterLeft($chapterForDay)) {
                            $nextVolume = ($leftChapterStart + $remainVolume);

                            $str = '[{"book": "'.$curChapter.'", "volume": "'.getVolume($leftChapterStart, $nextVolume).'"}';
                            if(getArrayLast()) {
                                $str .= ']';
                                $daysArray[$arrayIndex] = $str;
                                $duplicateCheck = false;
                            }
                            $volumeLeft = checkChapterLeft($chapterForDay) - $remainVolume;
                            if($chapterLeft != 0 && !$duplicateCheck) {
                                $duplicateCheck = true;
                                $chapterLeft--;
                            }
                            //echo ' #2-3 '.$str.' ';
                        }
                        else {


                            while($chapterLeft != 0 && $remainVolume >= checkChapterLeft($chapterForDay)) {
                                $addVolume = addChapterLeft($chapterForDay);
                                $nextVolume = $leftChapterStart + $addVolume;
                                
                                $str = '[{"book": "'.$curChapter.'", "volume": "'.getVolume($leftChapterStart, $nextVolume).'"}]';
                                $daysArray[$arrayIndex] = $str;
                                $arrayIndex++;
                                $leftChapterStart = $nextVolume;
                                $duplicateCheck = false;

                                $remainVolume = ($remainVolume - $addVolume);
                                //echo ' #2-4 '.$str.' ';
                            }
                            
                            
                            $volume = ($remainVolume - ($remainVolume % $chapterForDay)) / $chapterForDay; 
                            
                            // for문 다음에 추가
                            $volumeLeft = $remainVolume % $chapterForDay;

                            for($i=0;$i<$volume;$i++) {
                                $nextVolume = ($leftChapterStart + $chapterForDay);
                                
                                $str = '[{"book": "'.$curChapter.'", "volume": "'.getVolume($leftChapterStart, $nextVolume).'"}';

                                if(!getArrayLast() || (getArrayLast() && $last)) {
                                    $str .= ']';
                                    $daysArray[$arrayIndex] = $str;
                                    $arrayIndex++;
                                    $duplicateCheck = false;
                                } 
                                
                                $leftChapterStart = $nextVolume;
                                //echo ' #2-5 '.$str.' ';
                            }
                            $leftChapterStart = $nextVolume;
        
                            if(!getArrayLast() && $volumeLeft != 0) {
                                $nextVolume = ($leftChapterStart + $volumeLeft);

                                $str = '[{"book": "'.$curChapter.'", "volume": "'.getVolume($leftChapterStart, $nextVolume).'"}';
                                if(getArrayLast()) {
                                    $str .= ']';
                                    $daysArray[$arrayIndex] = $str;
                                    $duplicateCheck = false;
                                }
                                $volumeLeft = $chapterForDay - $volumeLeft;
                                //echo ' #2-6 '.$str.' ';
                            }
                        }
        
                        
                    }
                    else if($curVolume < $volumeLeft) {

                        $nextVolume = $startVolume + $curVolume;

                        $str .= ', {"book": "'.$curChapter.'", "volume": "'.getVolume($startVolume, $nextVolume).'"}';
                        $leftChapterStart = ($curVolume + 1);
                        $volumeLeft = ($volumeLeft - $curVolume);
                        if(getArrayLast()) {
                            $str .= ']';
                            $daysArray[$arrayIndex] = $str;
                            $duplicateCheck = false;
                        }
                        //echo ' #2-7 '.$str.' ';

                    }

                }

                
            }

            //print_r($daysArray);

            foreach ($daysArray as $key => $value) {
                $days = ((int)$key)+1;
                $customBiblePlanResult = setUserBibleCustom($newBiblePlanSeqNo, $days, $value);
                if($customBiblePlanResult == 1) {
                    $customBibleStatus = true;
                }
                else {
                    $customBibleStatus = false;
                }
            }

            
        }

        if($goalResultStatus && $statusUpdateStatus && $biblePlanStatus && $customBibleStatus) {
            echo '{"result":"success", "jwt": "'.$jwt.'"}';
        }
        else {
            echo '{"result":"fail", "jwt": "'.$jwt.'", "errorCode": "'.$commonError["code"].'", "errorMessage": "'.$commonError["message"].'"}';
        }

    }
 

}
catch (Exception $e) {
    echo '{"result":"fail", "errorCode": "00", "errorMessage": "'.$e->getMessage().'"}';
}


?>