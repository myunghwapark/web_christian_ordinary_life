<?php
require_once('../../common/header.php');
require_once('../../database/thank_diary_query.php');
require_once('../../database/goal_query.php');

try {
    $jwtCls = new Jwt();

    $thankDiarySeqNo = $obj['thankDiarySeqNo'];
    $userSeqNo = $obj['userSeqNo'];
    $title = $obj['title'];
    $diaryDate = $obj['diaryDate'];
    $content = $obj['content'];
    $thankDiary = $obj['thankDiary'];
    $goalDate = $obj['goalDate'];
    $image = $obj['image'];
    $imageURL = $obj['imageURL'];
    $fileExtension = $obj['fileExtension'];
    $categoryNo = $obj['categoryNo'];
    $imageStatus = $obj['imageStatus'];
    $keepLogin = $obj['keepLogin'];
    $jwt = $obj['jwt'];

    $auch = $jwtCls->dehashing($jwt);
    
    if($auch) {
            
        $jwt = $jwtCls->hashing($userSeqNo, $keepLogin);
     
    
        $realImage;
        $setResult;
        $fileName = '';
        $success = true;
        $newDiary = false;

        if($userSeqNo != null && $userSeqNo != '') {

            if($thankDiarySeqNo == null || $thankDiarySeqNo == '') {
                $newDiary = true;
            }
            
            
            if($imageStatus == 'replace' || $imageStatus == 'delete') {
                $saveFileName = $_SERVER['DOCUMENT_ROOT'] . '/col/images/diary/' . $imageURL;
            
                if(file_exists($saveFileName)) unlink($saveFileName);
            }
            else if($imageStatus == 'noChange') {
                $fileName = $imageURL;
            }

            if($newDiary) {

                $thankDiarySeqNoResult = getThankDiaryNextSeqNo();
                $numThankDiarySeqNoResult = mysqli_num_rows($thankDiarySeqNoResult);
                while($row = mysqli_fetch_array($thankDiarySeqNoResult)){
                    $thankDiarySeqNo = $row['thankDiarySeqNo'];
                }
            }

            if($image != null) {

                $date = date("YmdHis" ,time());
                $fileNameStr = $thankDiarySeqNo . '_' . $userSeqNo. '_' . $date;
                $saveFileName = $_SERVER['DOCUMENT_ROOT'] . '/col/images/diary/' . $fileNameStr;
            
                $fileName = $fileNameStr;

                if($fileExtension != null && $fileExtension != '') {
                    $saveFileName = $saveFileName.'.'.$fileExtension;
                    $fileName = $fileName.'.'.$fileExtension;
                }
                if(file_exists($saveFileName)) unlink($saveFileName);
                $realImage = base64_decode($image);
                file_put_contents($saveFileName, $realImage);

            } 

            if($newDiary) {
                $setResult = insertThankDiary($thankDiarySeqNo, $userSeqNo, $title, $diaryDate, $content, $fileName, $categoryNo);
            }
            else {
                $setResult = updateThankDiary($thankDiarySeqNo, $userSeqNo, $title, $diaryDate, $content, $fileName, $categoryNo);
            }
        }
        else {
            echo '{"result":"fail", "jwt": "'.$jwt.'", "errorCode": "02", "errorMessage": "There is no user logged in."}';
        }


        if($success && $setResult == 1) {

            $getGoalResult = getGoalProgress($userSeqNo, $goalDate);
            $numGetGoalResults = mysqli_num_rows($getGoalResult);
            
            $counter = 0;
        
            if($numGetGoalResults > 0) {
        
                $result = updateThankDiaryProgress($userSeqNo, $goalDate, $thankDiary);
        
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
                $qtRecord = "";
                $readingBible = "";
                if($numUserGoalResult > 0) {
                    while($row = mysqli_fetch_array($userGoalResult)){
                        $readingBible = $row['praying'] != 'true' ? '-' : 'n';
                        $qtRecord = $row['qtRecord'] != 'true' ? '-' : 'n';
                        $readingBible = $row['readingBible'] != 'true' ? '-' : 'n';
                    }
                }

                $result = setGoalProgress($userSeqNo, $goalDate, $readingBible, $thankDiary, $qtRecord, $praying);
        
        
                if($result == 1) {
                    echo '{"result":"success", "jwt": "'.$jwt.'"}';
                }
                else {
                    echo '{"result":"fail", "jwt": "'.$jwt.'", "errorCode": "'.$commonError["code"].'", "errorMessage": "'.$commonError["message"].'"}';
                }
            }
        }
    }

}
catch (Exception $e) {
    echo '{"result":"fail", "errorCode": "00", "errorMessage": "'.$e->getMessage().'"}';
}
    
?>