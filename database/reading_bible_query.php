<?php

    function getBibleBooks($language) {
        global $connection;
        $query = "Select 
            number,
            (CASE WHEN '$language' = 'ko' THEN fullname_ko ELSE fullname_en END) as fullname,
            short,
            section,
            chapters
        from bibleBooks;";
        $result = mysqli_query($connection, $query);

        if($result == false) {
            echo "error: " . mysqli_error($connection);
        }
        return $result;
    }

    function getBibleKJV($book, $chapter) {
        global $connection;
        $query = "Select 
            book, 
            chapter, 
            verse, 
            content 
        from bible_kjv 
        where book = (select number from bibleBooks where short = '$book') and chapter = '$chapter';";
        $result = mysqli_query($connection, $query);

        if($result == false) {
            echo "error: " . mysqli_error($connection);
        }
        return $result;
    }

    function getBibleHRV($book, $chapter) {
        global $connection;
        $query = "Select 
            book, 
            chapter, 
            verse, 
            content 
        from bible_korHRV 
        where book = (select number from bibleBooks where short = '$book') and chapter = '$chapter';";
        $result = mysqli_query($connection, $query);

        if($result == false) {
            echo "error: " . mysqli_error($connection);
        }
        return $result;
    }

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

    function getBiblePlan($language, $biblePlanId) {
        global $connection;
        $query = "Select 
            bible_plan_seq_no biblePlanSeqNo,
            bible_plan_id biblePlanId, 
            plan_period planPeriod, 
            (CASE WHEN '$language' = 'ko' THEN plan_volume_ko ELSE plan_volume_en END) as planVolume,
            (CASE WHEN '$language' = 'ko' THEN plan_title_ko ELSE plan_title_en END) as planTitle,
            (CASE WHEN '$language' = 'ko' THEN plan_subtitle_ko ELSE plan_subtitle_en END) as planSubTitle
        from tbBiblePlan 
        where bible_plan_id = '$biblePlanId';";
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

	function updateAllUserBiblePlanStatus($userSeqNo, $biblePlanStatus) {
		global $connection;
        $query = "Update tbUserBiblePlan set
            plan_status='$biblePlanStatus',
            update_date=NOW()
            where user_seq_no='$userSeqNo';";
        
		$result = mysqli_query($connection, $query);

		if($result == false) {
			 echo "error: " . mysqli_error($connection);
		}
		return $result;
    }

    function getCurrentBiblePlanSeqNo($userSeqNo) {
        global $connection;
        $query = "Select 
            user_bible_plan_seq_no userBiblePlanSeqNo,
            plan_start_date planStartDate,
            plan_end_date planEndDate
        from tbUserBiblePlan 
        where plan_status = 'P002_001'
            and user_seq_no='$userSeqNo'
            order by user_bible_plan_seq_no desc LIMIT 1;";
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

    function checkBibleSelected($userSeqNo) {
        global $connection;
        $query = "Select 
            A.user_seq_no userSeqNo, 
            B.user_bible_plan_seq_no userBiblePlanSeqNo,
            IF(reading_bible, 'true', 'false') as readingBible, 
            B.bible_plan_id biblePlanId
        from (select * from tbGoal where user_seq_no = '" .$userSeqNo ."') as A
        LEFT JOIN tbUserBiblePlan as B
        ON A.user_seq_no = B.user_seq_no
        and B.plan_status = 'P002_001'
        and A.reading_bible = 1;";
        $result = mysqli_query($connection, $query);

        if($result == false) {
            echo "error: " . mysqli_error($connection);
        }
        return $result;
    }

    function getTodaysBible($biblePlanId, $days) {
        global $connection;
        $query = "Select 
        days, chapter
        from tbBiblePlanDetail
        where  bible_plan_id = '$biblePlanId'
        and days = '$days';";

        $result = mysqli_query($connection, $query);

        if($result == false) {
            echo "error: " . mysqli_error($connection);
        }
        return $result;
    }

    function getTodaysBibleCustom($userSeqNo, $biblePlanId, $days) {
        global $connection;
        $query = "Select 
        days, chapter
        from tbUserBibleCustom
        where  user_bible_plan_seq_no = (SELECT user_bible_plan_seq_no FROM tbUserBiblePlan 
            WHERE user_seq_no = '$userSeqNo'
            and plan_status = 'P002_001'
            and bible_plan_id = '$biblePlanId')
        and days = '$days';";
      
        $result = mysqli_query($connection, $query);

        if($result == false) {
            echo "error: " . mysqli_error($connection);
        }
        return $result;
    }

    function getLastDayBible($biblePlanId) {
        global $connection;
        $query = "Select 
        days
        from tbBiblePlanDetail
        where  bible_plan_id = '$biblePlanId'
        order by days desc limit 1;";

        $result = mysqli_query($connection, $query);

        if($result == false) {
            echo "error: " . mysqli_error($connection);
        }
        return $result;
    }

    function getLastDayBibleCustom($userSeqNo, $biblePlanId) {
        global $connection;
        $query = "Select 
        days, chapter
        from tbUserBibleCustom
        where  user_bible_plan_seq_no = (SELECT user_bible_plan_seq_no FROM tbUserBiblePlan 
            WHERE user_seq_no = '$userSeqNo'
            and plan_status = 'P002_001'
            and bible_plan_id = '$biblePlanId')
            order by days desc limit 1;";
      
        $result = mysqli_query($connection, $query);

        if($result == false) {
            echo "error: " . mysqli_error($connection);
        }
        return $result;
    }

    function getLatestBibleGoalProgress($userSeqNo, $userBiblePlanSeqNo) {
        try {
            global $connection;
            $query = "Select 
                bible_progress as bibleProgress,
                bible_progress_done as bibleProgressDone,
                bible_days as bibleDays,
                Date(goal_date) as goalDate
            from tbGoalBibleProgress
            where user_seq_no = '$userSeqNo'
            and user_bible_plan_seq_no = '$userBiblePlanSeqNo'
            order by bible_days desc LIMIT 1;";
            
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

    function insertBibleProgress($userSeqNo, $goalDate, $bibleProgress, $bibleProgressDone, $bibleDays, $userBiblePlanSeqNo) {
        try {
            global $connection;
            $query = "Insert into tbGoalBibleProgress(user_seq_no,
                user_bible_plan_seq_no,
                bible_progress, 
                bible_progress_done, 
                bible_days, 
                goal_date,
                create_date
                ) 
                values(
                    '$userSeqNo', 
                    '$userBiblePlanSeqNo', 
                    '$bibleProgress', 
                    '$bibleProgressDone', 
                    '$bibleDays', 
                    '$goalDate', 
                    NOW());";
            
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
    
    function updateBibleProgress($userSeqNo, $goalDate, $bibleProgress, $bibleProgressDone, $bibleDays, $userBiblePlanSeqNo) {
        try {
            global $connection;
            $query = "Update 
            tbGoalBibleProgress set 
                bible_progress='$bibleProgress', 
                bible_progress_done='$bibleProgressDone', 
                update_date=NOW() 
            where user_seq_no='$userSeqNo'
            and bible_days='$bibleDays'
            and user_bible_plan_seq_no='$userBiblePlanSeqNo';";


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

    function getBibleProgress($userSeqNo, $userBiblePlanSeqNo, $bibleDays) {
        try {
            global $connection;
            $query = "Select 
                goal_date as goalDate, 
                bible_progress as bibleProgress,
                bible_days as bibleDays,
                bible_progress_done as bibleProgressDone,
                bible_days as bibleDays
            from tbGoalBibleProgress
            where bible_days = '$bibleDays'
            and user_bible_plan_seq_no='$userBiblePlanSeqNo'
            and user_seq_no = '$userSeqNo';";

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

    function insertReadingBible($userSeqNo, $goalDate, $readingBible) {
        try {
            global $connection;
            $query = "Insert into tbGoalProgress(user_seq_no,
                reading_bible, 
                goal_date,
                create_date
                ) 
                values(
                    '$userSeqNo', 
                    '$readingBible',
                    '$goalDate', 
                    NOW());";
            
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
    
    function updateReadingBible($userSeqNo, $goalDate, $readingBible) {
        try {
            global $connection;
            $query = "Update 
            tbGoalProgress set 
                reading_bible='$readingBible', 
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