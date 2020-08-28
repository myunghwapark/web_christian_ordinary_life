<?php
    function getUserGoal($userSeqNo) {
        global $connection;
        $query = "Select 
            goal_seq_no goalSeqNo, 
            A.user_seq_no userSeqNo, 
            B.user_bible_plan_seq_no userBiblePlanSeqNo,
            IF(reading_bible, 'true', 'false') as readingBible, 
            IF(thank_diary, 'true', 'false') as thankDiary, 
            IF(qt_record, 'true', 'false') as qtRecord, 
            IF(qt_alarm, 'true', 'false') as qtAlarm,
            qt_time qtTime,
            IF(praying, 'true', 'false') as praying, 
            IF(praying_alarm, 'true', 'false') as prayingAlarm,
            praying_time prayingTime, 
            praying_duration prayingDuration, 
            goal_set_date goalSetDate,
            B.bible_plan_id biblePlanId,
            B.custom_bible customBible,
            B.plan_period planPeriod
        from (select * from tbGoal where user_seq_no = '" .$userSeqNo ."') as A
        LEFT JOIN tbUserBiblePlan as B
        ON A.user_seq_no = B.user_seq_no
        and B.plan_status = 'P002_001';";
        $result = mysqli_query($connection, $query);

        if($result == false) {
            echo "error: " . mysqli_error($connection);
        }
        return $result;
    }


	function setUserGoal($userSeqNo, $readingBible, $thankDiary, $qtRecord, $qtAlarm, $qtTime, $praying, $prayingAlarm, $prayingTime, $prayingDuration) {
		global $connection;
        $query = "Insert into tbGoal(
            user_seq_no, 
            reading_bible, 
            thank_diary, 
            qt_record, 
            qt_alarm,
            qt_time,
            praying, 
            praying_alarm,
            praying_time, 
            praying_duration, 
            goal_set_date, 
            create_date
            ) 
            values(
                '$userSeqNo', 
                '$readingBible', 
                '$thankDiary', 
                '$qtRecord', 
                '$qtAlarm', 
                '$qtTime', 
                '$praying', 
                '$prayingAlarm', 
                '$prayingTime', 
                '$prayingDuration', 
                NOW(), 
                NOW());";
        
		$result = mysqli_query($connection, $query);

		if($result == false) {
			 echo "error: " . mysqli_error($connection);
		}
		return $result;
    }

	function updateUserGoal($userSeqNo, $readingBible, $thankDiary, $qtRecord, $qtAlarm, $qtTime, $praying, $prayingAlarm, $prayingTime, $prayingDuration) {
        try {
            global $connection;
            $query = "Update tbGoal set 
            reading_bible='$readingBible', 
            thank_diary='$thankDiary', 
            qt_record='$qtRecord', 
            qt_time='$qtTime', 
            qt_alarm='$qtAlarm', 
            praying='$praying', 
            praying_alarm='$prayingAlarm', 
            praying_time='$prayingTime', 
            praying_duration='$prayingDuration', 
            update_date=NOW() 
            where user_seq_no='$userSeqNo';";

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

    function getGoalProgress($userSeqNo, $goalDate) {
        global $connection;
        $query = "Select 
            reading_bible as readingBible, 
            thank_diary as thankDiary, 
            qt_record as qtRecord, 
            praying as praying 
        from tbGoalProgress
        where user_seq_no = '$userSeqNo'
        and Date(goal_date) = '$goalDate';";
        $result = mysqli_query($connection, $query);

        if($result == false) {
            echo "error: " . mysqli_error($connection);
        }
        return $result;
    }

    function setGoalProgress($userSeqNo, $goalDate, $readingBible, $thankDiary, $qtRecord, $praying) {
        global $connection;
        $query = "Insert into tbGoalProgress(
            user_seq_no,
            reading_bible, 
            thank_diary, 
            qt_record, 
            praying, 
            goal_date,
            create_date
            ) 
            values(
                '$userSeqNo', 
                '$readingBible', 
                '$thankDiary', 
                '$qtRecord', 
                '$praying',  
                '$goalDate'', 
                NOW());";
        
		$result = mysqli_query($connection, $query);

		if($result == false) {
			 echo "error: " . mysqli_error($connection);
		}
		return $result;
    }

	function updateGoalProgress($userSeqNo, $goalDate, $readingBible, $thankDiary, $qtRecord, $praying) {
        try {
            global $connection;
            $query = "Update 
            tbGoalProgress set 
                reading_bible='$readingBible', 
                thank_diary='$thankDiary', 
                qt_record='$qtRecord', 
                praying='$praying',  
                update_date=NOW() 
            where user_seq_no='$userSeqNo'
            and Date(goal_date)='$goalDate';";

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