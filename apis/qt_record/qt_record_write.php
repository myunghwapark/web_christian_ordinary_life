<?php
require_once('../../common/header.php');
require_once('../../database/qt_record_query.php');
require_once('../../database/goal_query.php');

try {
    $jwtCls = new Jwt();
    
    $qtRecordSeqNo = $obj['qtRecordSeqNo'];
    $userSeqNo = $obj['userSeqNo'];
    $title = $obj['title'];
    $bible = $obj['bible'];
    $qtDate = $obj['qtDate'];
    $content = $obj['content'];
    $qtRecord = $obj['qtRecord'];
    $goalDate = $obj['goalDate'];
    $keepLogin = $obj['keepLogin'];
    $jwt = $obj['jwt'];

    $auch = $jwtCls->dehashing($jwt, $userSeqNo);
    
    if($auch) {
            
        $jwt = $jwtCls->hashing($userSeqNo, $keepLogin);

        $setResult;
        if($userSeqNo != null && $userSeqNo != '') {
            if($qtRecordSeqNo == null || $qtRecordSeqNo == '') {
                $setResult = insertQtRecord($userSeqNo, $title, $qtDate, $bible, $content);
                
            }
            else {
                $setResult = updateQtRecord($qtRecordSeqNo, $userSeqNo, $title, $qtDate, $bible, $content);
            }
        }
        else {
            echo '{"result":"fail", "jwt": "'.$jwt.'", "errorCode": "02", "There is no user logged in."}';
        }

        if($setResult == 1) {

            $getGoalResult = getGoalProgress($userSeqNo, $goalDate);
            $numGetGoalResults = mysqli_num_rows($getGoalResult);
            
            $counter = 0;
        
            if($numGetGoalResults > 0) {
        
                $result = updateQtRecordProgress($userSeqNo, $goalDate, $qtRecord);
        
                if($result == 1) {
                    echo '{"result":"success", "jwt": "'.$jwt.'"}';
                }
                else {
                    echo '{"result":"fail", "jwt": "'.$jwt.'", "errorCode": "'.$commonError["code"].'", "errorMessage": "'.$commonError["message"].'"}';
                }
            }
            else {
                
                $userGoalResult = getUserGoal($userSeqNo);
                $numUserGoalResult = mysqli_num_rows($userGoalResult);    

                $praying = "";
                $thankDiary = "";
                $readingBible = "";
                if($numUserGoalResult > 0) {
                    while($row = mysqli_fetch_array($userGoalResult)){
                        $praying = $row['praying'] != 'true' ? '-' : 'n';
                        $thankDiary = $row['thankDiary'] != 'true' ? '-' : 'n';
                        $readingBible = $row['readingBible'] != 'true' ? '-' : 'n';
                    }
                }

                $result = setGoalProgress($userSeqNo, $goalDate, $readingBible, $thankDiary, $qtRecord, $praying);

        
                if($result == 1) {
                    echo '{"result":"success", "jwt": "'.$jwt.'"}';
                }
                else {
                    echo '{"result":"fail", "jwt": "'.$jwt.'", "errorCode": "02", "errorMessage": "'.$commonError["message"].'"}';
                }
            }
        }
        else {
            echo '{"result":"fail", "errorCode": "01", "errorMessage": "'.$e->getMessage().'"}';
        }
    }

}
catch (Exception $e) {
    echo '{"result":"fail", "errorCode": "00", "errorMessage": "'.$e->getMessage().'"}';
}
    
?>