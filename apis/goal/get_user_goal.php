<?php
require('../../common/header.php');
require('../../database/goal_query.php');

//use \Firebase\JWT\JWT;

try {

    $userSeqNo = $obj['userSeqNo'];
    $keepLogin = $obj['keepLogin'];
    $jwt = $obj['jwt'];

    $jwtCls = new Jwt();

    $auch = $jwtCls->dehashing($jwt);
    if($auch) {
            
        $jwt = $jwtCls->hashing($userSeqNo, $keepLogin);

        $result = getUserGoal($userSeqNo);
        $numResults = mysqli_num_rows($result);
        
        $counter = 0;

        if($numResults > 0) {

            echo '{"result":"success", "jwt": "'.$jwt.'", "goalInfo":[';

            $goalSeqNo = "";
            $readingBible = "";
            while($row = mysqli_fetch_assoc($result)){
                echo json_encode($row);
                if (++$counter != $numResults) {
                    echo",";
                }
            }
            echo ']}';
        }
        else {
            echo '{"result":"fail", "jwt": "'.$jwt.'", "errorCode": "01", "errorMessage": "There are no goals set."}';
        }

    }

}
catch (Exception $e) {
    echo '{"result":"fail", "errorCode": "00", "errorMessage": "'.$e->getMessage().'"}';
}
    
?>