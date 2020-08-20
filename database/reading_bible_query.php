<?php
    function getBiblePlanList($language) {
        global $connection;
        $query = "Select 
            bible_plan_seq_no biblePlanSeqNo,
            bible_plan_id biblePlanId, 
            plan_period planPeriod, 
            (CASE WHEN '$language' = 'ko' THEN plan_volume_ko ELSE plan_volume_en END) as planVolume,
            (CASE WHEN '$language' = 'ko' THEN plan_title_ko ELSE plan_title_en END) as planTitle,
            (CASE WHEN '$language' = 'ko' THEN plan_subtitle_ko ELSE plan_subtitle_en END) as planSubTitle
        from tbBiblePlan 
        where plan_status = 'P001_001' ORDER BY plan_order;";
        $result = mysqli_query($connection, $query);

        if($result == false) {
            echo "error: " . mysqli_error($connection);
        }
        return $result;
    }

    function getBiblePlanDetail($biblePlanId) {
        global $connection;
        $query = "Select 
            days, chapter
        from tbBiblePlanDetail 
        where bible_plan_id = '$biblePlanId' ORDER BY days;";
        
        $result = mysqli_query($connection, $query);

        if($result == false) {
            echo "error: " . mysqli_error($connection);
        }
        return $result;
    }

    function getUserBiblePlanSeqNo($userSeqNo) {
        global $connection;
        $query = "Select 
            user_bible_plan_seq_no userBiblePlanSeqNo
        from tbUserBiblePlan 
        where user_seq_no = '" .$userSeqNo ."'
            and plan_status = 'P002_001';";
        $result = mysqli_query($connection, $query);

        if($result == false) {
            echo "error: " . mysqli_error($connection);
        }
        return $result;
    }


	function setUserBiblePlan($userSeqNo, $biblePlanId, $planPeriod, $customBible, $planEndDate) {
		global $connection;
        $query = "Insert into tbUserBiblePlan(
                user_seq_no, 
                bible_plan_id, 
                plan_period, 
                custom_bible, 
                plan_start_date,
                plan_end_date,
                create_date
            ) 
            values(
                '$userSeqNo', 
                '$biblePlanId', 
                '$planPeriod', 
                '$customBible', 
                NOW(), 
                '$planEndDate', 
                NOW());";
        
		$result = mysqli_query($connection, $query);

		if($result == false) {
			 echo "error: " . mysqli_error($connection);
		}
		return $result;
    }
/* 
	function updateUserBiblePlan($userSeqNo, $userBiblePlanSeqNo, $biblePlanId, $planPeriod, $customBible, $planEndDate, $biblePlanStatus) {
		global $connection;
        $query = "Update tbUserBiblePlan set
            bible_plan_id='$biblePlanId',
            plan_period='$planPeriod',
            custom_bible='$customBible',
            plan_start_date=NOW(),
            plan_end_date='$planEndDate',
            plan_status='$biblePlanStatus',
            update_date=NOW()
            where user_seq_no='$userSeqNo'
            and user_bible_plan_seq_no='$userBiblePlanSeqNo';";
        
		$result = mysqli_query($connection, $query);

		if($result == false) {
			 echo "error: " . mysqli_error($connection);
		}
		return $result;
    } */

	function updateUserBiblePlanStatus($userSeqNo, $biblePlanStatus, $userBiblePlanSeqNo) {
		global $connection;
        $query = "Update tbUserBiblePlan set
            plan_status='$biblePlanStatus',
            update_date=NOW()
            where user_seq_no='$userSeqNo'
            and user_bible_plan_seq_no='$userBiblePlanSeqNo';";
        
		$result = mysqli_query($connection, $query);

		if($result == false) {
			 echo "error: " . mysqli_error($connection);
		}
		return $result;
    }

    function getProgressingBibleCustom($userBiblePlanSeqNo) {
        global $connection;
        $query = "Select 
        user_bible_custom_seq_no userBibleCustomSeqNo,
            user_bible_plan_seq_no tbUserBibleCustom,
            days,
            chapter,
            done_progress doneProgress
        from tbUserBiblePlan 
        where user_bible_plan_seq_no = '" .$userBiblePlanSeqNo ."'
            and read_yn = 'n'
            order by days LIMIT 1;";
        $result = mysqli_query($connection, $query);

        if($result == false) {
            echo "error: " . mysqli_error($connection);
        }
        return $result;
    }


	function setUserBibleCustom($userBiblePlanSeqNo, $days, $chapter) {
		global $connection;
        $query = "Insert into tbUserBibleCustom(
                user_bible_plan_seq_no, 
                days, 
                chapter, 
                create_date
            ) 
            values(
                '$userBiblePlanSeqNo', 
                '$days', 
                '$chapter', 
                NOW());";
        
		$result = mysqli_query($connection, $query);

		if($result == false) {
			 echo "error: " . mysqli_error($connection);
		}
		return $result;
    }

	function updateUserBibleCustom($userBibleCustomSeqNo, $doneProgress, $readYn) {
		global $connection;
        $query = "Update tbUserBibleCustom set
            done_progress='$doneProgress',
            read_yn='$readYn',
            read_date=NOW(),
            update_date=NOW()
            where user_bible_custom_seq_no='$userBibleCustomSeqNo';";
        
		$result = mysqli_query($connection, $query);

		if($result == false) {
			 echo "error: " . mysqli_error($connection);
		}
		return $result;
    }

?>