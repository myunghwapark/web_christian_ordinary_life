<?php
require_once $_SERVER["DOCUMENT_ROOT"].'/col/common/header.php';
require('../../database/goal_query.php');

try {
    $jwtCls = new Jwt();
	
    $userSeqNo = $obj['userSeqNo'];
    $yearMonth = $obj['yearMonth'];

    $keepLogin = $obj['keepLogin'];
    $jwt = $obj['jwt'];

    $auch = $jwtCls->dehashing($jwt);
    
    if($auch) {
            
        $jwt = $jwtCls->hashing($userSeqNo, $keepLogin);
  
        $monthlyGoalProgressResult = getMonthGoalProgress($userSeqNo, $yearMonth);
        $monthlyGoalProgressNumResult = mysqli_num_rows($monthlyGoalProgressResult);

        $goalHistoryPrevResult = getUserGoalHistoryPrev($userSeqNo, $yearMonth);
        $goalHistoryPrevNumResult = mysqli_num_rows($goalHistoryPrevResult);

        $thankDiaryHistory = '';
        $readingBibleHistory = '';
        $prayingHistory = '';
        $qtRecordHistory = '';
        $biblePlanId = '';
        $day_of_week = '';
        $blank = false;
        if($goalHistoryPrevNumResult > 0) {
            while($row = mysqli_fetch_assoc($goalHistoryPrevResult)){
                // The case of goal reset
                if($row['readingBible'] == null && $row['thankDiary'] == null && $row['qtRecord'] == null && $row['praying'] == null) {
                    $blank = true;
                } else {
                    $biblePlanId = $row['biblePlanId'];
                    $thankDiaryHistory = $row['thankDiary'];
                    $readingBibleHistory = $row['readingBible'];
                    $prayingHistory = $row['praying'];
                    $qtRecordHistory = $row['qtRecord'];
                }
            }
        } else {
            $blank = true;
        }
        

        $goalHistoryResult = getUserGoalHistory($userSeqNo, $yearMonth);
        $goalHistoryNumResult = mysqli_num_rows($goalHistoryResult);
        
        $counter = 0;
        $firstRow = false;

        $now = date('y-m-d');
        $today = new DateTime($now);

        if($monthlyGoalProgressNumResult > 0) {

            $historyList = array();
            $monthlyList = array();


            while($goalProgressRow = mysqli_fetch_assoc($monthlyGoalProgressResult)){
                $monthlyList[] = $goalProgressRow;
            }

            if($goalHistoryNumResult > 0) {
                while($goalHistoryRow = mysqli_fetch_assoc($goalHistoryResult)){
                    $historyList[] = $goalHistoryRow;
                }
            }

            echo '{"result":"success", "jwt": "'.$jwt.'", "goalProgress":[';

            $goalHistoryDate = '';

            foreach($monthlyList as $monthlyItem) {
                $goalProgressDate = new DateTime($monthlyItem['goalDate']);

                foreach($historyList as $historyItem) {
                    $goalHistoryDate = new DateTime($historyItem['goalSetDate']);
                    if($goalProgressDate == $goalHistoryDate) {
                        $biblePlanId = $historyItem['biblePlanId'];
                        $thankDiaryHistory = $historyItem['thankDiary'];
                        $readingBibleHistory = $historyItem['readingBible'];
                        $prayingHistory = $historyItem['praying'];
                        $qtRecordHistory = $historyItem['qtRecord'];
                        break;
                    }
                }
    
                $day_of_week = $goalProgressDate->format('D');

                if(($day_of_week == 'Sat' || $day_of_week == 'Sun') && $biblePlanId == 'bible-52w') {
                    $monthlyItem['readingBible'] = '-';
                }

                if($thankDiaryHistory == 'false') $monthlyItem['thankDiary'] = '-';
                if($readingBibleHistory == 'false') $monthlyItem['readingBible'] = '-';
                if($prayingHistory == 'false') $monthlyItem['praying'] = '-';
                if($qtRecordHistory == 'false') $monthlyItem['qtRecord'] = '-';


                if($goalProgressDate == $goalHistoryDate) $blank = false;
                if($blank == false && $goalProgressDate <= $today) {
                    if($firstRow) echo ",";
                    echo json_encode($monthlyItem);
                    $firstRow = true;
                } 
                
                
            }
            echo ']}';
        }
        else {
            echo '{"result":"fail", "jwt": "'.$jwt.'", "errorCode": "01", "errorMessage": "There are no goals in progress."}';
        }
    }

}
catch (Exception $e) {
    echo '{"result":"fail", "errorCode": "00", "errorMessage": "'.$e->getMessage().'"}';
}
    
?>