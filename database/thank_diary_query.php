<?php
    function getThankDiaryList($userSeqNo, $searchKeyword, $startPageNum, $rowCount) {
        $searchQuery = "";
        if($searchKeyword != null && $searchKeyword != '') {
            $searchQuery = " and title LIKE '%$searchKeyword%' or content LIKE '%$searchKeyword%' ";
        }
        global $connection;
        $query = "Select 
            thank_diary_seq_no seqNo, 
            title, 
            diary_date diaryDate, 
            content 
            from tbThankDiary 
            where user_seq_no = '$userSeqNo'  $searchQuery order by create_date DESC LIMIT $startPageNum, $rowCount;";
            
        $result = mysqli_query($connection, $query);

        if($result == false) {
            echo "error: " . mysqli_error($connection);
        }
        return $result;
    }

    function getThankDiaryTotalCnt($userSeqNo, $searchKeyword) {
        $searchQuery = "";
        if($searchKeyword != null && $searchKeyword != '') {
            $searchQuery = " and title LIKE '%$searchKeyword%' or content LIKE '%$searchKeyword%' ";
        }
        global $connection;
        $query = "Select 
            count(thank_diary_seq_no) totalCnt
            from tbThankDiary 
            where user_seq_no = '$userSeqNo'  $searchQuery;";
            
        $result = mysqli_query($connection, $query);

        if($result == false) {
            echo "error: " . mysqli_error($connection);
        }
        return $result;
    }


    function getThankDiary($thankDiarySeqNo) {
        global $connection;
        $query = "Select 
            thank_diary_seq_no, 
            title, 
            diary_date, 
            content,
            create_date 
            from tbThankDiary 
            where thank_diary_seq_no = '$thankDiarySeqNo';";
            
        $result = mysqli_query($connection, $query);

        if($result == false) {
            echo "error: " . mysqli_error($connection);
        }
        return $result;
    }


	function insertThankDiary($userSeqNo, $title, $diaryDate, $content) {
		global $connection;
        $query = "Insert into tbThankDiary(user_seq_no, title, diary_date, content, create_date) values('$userSeqNo', '$title', '$diaryDate', '$content', NOW());";
        
		$result = mysqli_query($connection, $query);

		if($result == false) {
			 echo "error: " . mysqli_error($connection);
		}
		return $result;
    }

	function updateThankDiary($thankDiarySeqNo, $userSeqNo, $title, $diaryDate, $content) {
        try {
            global $connection;
            $query = "Update tbThankDiary set title='$title', diary_date='$diaryDate', content='$content', update_date=NOW() where thank_diary_seq_no='$thankDiarySeqNo' and user_seq_no='$userSeqNo';";

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

	function deleteThankDiary($userSeqNo, $thankDiarySeqNo) {
        try {
            global $connection;
            $query = "Delete from tbThankDiary where thank_diary_seq_no='$thankDiarySeqNo' and user_seq_no='$userSeqNo';";

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