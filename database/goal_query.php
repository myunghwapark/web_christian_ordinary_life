<?php
    function getUserGoal($userSeqNo) {
        global $connection;
        $query = "Select goal_seq_no goalSeqNo, user_seq_no userSeqNo, reading_bible readingBible, thank_diary thankDiary, qt_record qtRecord, praying from tbGoal where user_seq_no = '" .$userSeqNo ."';";
        $result = mysqli_query($connection, $query);

        if($result == false) {
            echo "error: " . mysqli_error($connection);
        }
        return $result;
    }


	function setUserGoal($userSeqNo, $readingBible, $thankDiary, $qtRecord, $praying, $prayingTime, $prayingDuration) {
		global $connection;
        $query = "Insert into tbGoal(user_seq_no, reading_bible, thank_diary, qt_record, praying, praying_time, praying_duration, goal_set_date, create_date) values('$userSeqNo', '$readingBible', '$thankDiary', '$qtRecord', '$praying', '$prayingTime', '$prayingDuration', NOW(), NOW());";
        
		$result = mysqli_query($connection, $query);

		if($result == false) {
			 echo "error: " . mysqli_error($connection);
		}
		return $result;
    }

	function updateUserGoal($userSeqNo, $readingBible, $thankDiary, $qtRecord, $praying, $prayingTime, $prayingDuration) {
        try {
            global $connection;
            $query = "Update tbGoal set reading_bible='$readingBible', thank_diary='$thankDiary', qt_record='$qtRecord', praying='$praying', praying_time='$prayingTime', praying_duration='$prayingDuration' where user_seq_no='$userSeqNo';";

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