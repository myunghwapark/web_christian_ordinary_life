<?php
    function getQtRecordList($userSeqNo, $searchKeyword, $searchStartDate, $searchEndDate, $startPageNum, $rowCount) {
        $searchQuery = "";
        if($searchKeyword != null && $searchKeyword != '') {
            $searchQuery = " and (title LIKE '%$searchKeyword%' or content LIKE '%$searchKeyword%') ";
        }
        if($searchStartDate != null && $searchStartDate != '') {
            $searchQuery .= " and date(qt_date) between date('$searchStartDate') and date('$searchEndDate')";
        }
        global $connection;
        $query = "Select 
            qt_record_seq_no seqNo, 
            title, 
            qt_date qtDate, 
            bible,
            content 
            from tbQtRecord 
            where user_seq_no = '$userSeqNo'  $searchQuery order by qt_date DESC LIMIT $startPageNum, $rowCount;";
            
        $result = mysqli_query($connection, $query);

        if($result == false) {
            echo "error: " . mysqli_error($connection);
        }
        return $result;
    }


    function getQtRecordTotalCnt($userSeqNo, $searchKeyword) {
        $searchQuery = "";
        if($searchKeyword != null && $searchKeyword != '') {
            $searchQuery = " and title LIKE '%$searchKeyword%' or content LIKE '%$searchKeyword%' ";
        }
        global $connection;
        $query = "Select 
            count(qt_record_seq_no) totalCnt
            from tbQtRecord 
            where user_seq_no = '$userSeqNo'  $searchQuery;";
            
        $result = mysqli_query($connection, $query);

        if($result == false) {
            echo "error: " . mysqli_error($connection);
        }
        return $result;
    }


    function getQtRecordBySeqNo($qtRecordSeqNo) {
        global $connection;
        $query = "Select 
            qt_record_seq_no seqNo, 
            title, 
            qt_date qtDate, 
            bible,
            content,
            create_date 
            from tbQtRecord 
            where qt_record_seq_no = '$qtRecordSeqNo';";
            
        $result = mysqli_query($connection, $query);

        if($result == false) {
            echo "error: " . mysqli_error($connection);
        }
        return $result;
    }

    function getQtRecordByQtDate($qtDate) {
        global $connection;
        $query = "Select 
            qt_record_seq_no seqNo, 
            title, 
            qt_date qtDate, 
            bible,
            content,
            create_date 
            from tbQtRecord 
            where Date(qt_date) = Date('$qtDate') LIMIT 1;";
            
        $result = mysqli_query($connection, $query);

        if($result == false) {
            echo "error: " . mysqli_error($connection);
        }
        return $result;
    }


	function insertQtRecord($userSeqNo, $title, $qtDate, $bible, $content) {
		global $connection;
        $query = "Insert into tbQtRecord(user_seq_no, title, qt_date, bible, content, create_date) values('$userSeqNo', '$title', '$qtDate', '$bible', '$content', NOW());";
        
		$result = mysqli_query($connection, $query);

		if($result == false) {
			 echo "error: " . mysqli_error($connection);
		}
		return $result;
    }

	function updateQtRecord($qtRecordSeqNo, $userSeqNo, $title, $qtDate, $bible, $content) {
        try {
            global $connection;
            $query = "Update tbQtRecord set title='$title', qt_date='$qtDate', bible='$bible', content='$content', update_date=NOW() where qt_record_seq_no='$qtRecordSeqNo' and user_seq_no='$userSeqNo';";

            $result = mysqli_query($connection, $query);

            if($result == false) {
                echo "error: " . mysqli_error($connection);
            }
            return $result;
        }
        catch(PDOException $ex) {
            return "Fail : ".$ex->getMessage()."<br>";
        }
    }
    

	function deleteQtRecord($userSeqNo, $qtRecordSeqNo) {
        try {
            global $connection;
            $query = "Delete from tbQtRecord where qt_record_seq_no='$qtRecordSeqNo' and user_seq_no='$userSeqNo';";

            $result = mysqli_query($connection, $query);

            if($result == false) {
                echo "error: " . mysqli_error($connection);
            }
            return $result;
        }
        catch(PDOException $ex) {
            return "Fail : ".$ex->getMessage()."<br>";
        }
	}


?>