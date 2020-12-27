<?php
    function getThankDiaryList($userSeqNo, $searchKeyword, $searchStartDate, $searchEndDate, $categoryNo, $startPageNum, $rowCount, $language) {
        global $connection;

        $searchQuery = "";
        if($searchKeyword != null && $searchKeyword != '') {
            $searchQuery = " and (title LIKE '%".mysqli_real_escape_string($connection, $searchKeyword)."%' or content LIKE '%".mysqli_real_escape_string($connection, $searchKeyword)."%') ";
        }
        if($searchStartDate != null && $searchStartDate != '') {
            $searchQuery .= " and date(diary_date) between date('$searchStartDate') and date('$searchEndDate')";
        }
        if($categoryNo != null && $categoryNo != '') {
            $searchQuery .= " and thank_category_no = '$categoryNo'";
        }
        $query = "Select 
            thank_diary_seq_no seqNo, 
            title, 
            diary_date diaryDate, 
            content, 
            image_url imageURL,
            thank_category_no categoryNo,
            (select thank_category_image_url from tbThankCategory where thank_category_no = A.thank_category_no) as categoryImageUrl,
            (select CASE WHEN '$language' = 'ko' THEN thank_category_title_ko ELSE thank_category_title_en END from tbThankCategory where thank_category_no = A.thank_category_no) as categoryTitle
            from tbThankDiary A 
            where user_seq_no = '$userSeqNo'  $searchQuery order by diary_date DESC LIMIT $startPageNum, $rowCount;";
            
        $result = mysqli_query($connection, $query);

        if($result == false) {
            echo "error: " . mysqli_error($connection);
        }
        return $result;
    }

    function getThankDiaryTotalCnt($userSeqNo, $searchKeyword, $searchStartDate, $searchEndDate, $categoryNo) {
        global $connection;
        
        $searchQuery = "";
        if($searchKeyword != null && $searchKeyword != '') {
            $searchQuery = " and (title LIKE '%".mysqli_real_escape_string($connection, $searchKeyword)."%' or content LIKE '%".mysqli_real_escape_string($connection, $searchKeyword)."%') ";
        }
        if($searchStartDate != null && $searchStartDate != '') {
            $searchQuery .= " and date(diary_date) between date('$searchStartDate') and date('$searchEndDate')";
        }
        if($categoryNo != null && $categoryNo != '') {
            $searchQuery .= " and thank_category_no = '$categoryNo'";
        }
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


    function getThankDiaryBySeqNo($thankDiarySeqNo, $language) {
        global $connection;
        $query = "Select 
            thank_diary_seq_no seqNo, 
            title, 
            diary_date diaryDate, 
            content,
            create_date createDate, 
            image_url imageURL,
            thank_category_no categoryNo,
            (select thank_category_image_url from tbThankCategory where thank_category_no = A.thank_category_no) as categoryImageUrl,
            (select CASE WHEN '$language' = 'ko' THEN thank_category_title_ko ELSE thank_category_title_en END from tbThankCategory where thank_category_no = A.thank_category_no) as categoryTitle 
            from tbThankDiary A 
            where thank_diary_seq_no = '$thankDiarySeqNo';";
            
        $result = mysqli_query($connection, $query);

        if($result == false) {
            echo "error: " . mysqli_error($connection);
        }
        return $result;
    }


    function getThankDiaryByDiaryDate($diaryDate, $language) {
        global $connection;
        $query = "Select 
            thank_diary_seq_no seqNo, 
            title, 
            diary_date diaryDate, 
            content,
            create_date createDate, 
            image_url imageURL,
            thank_category_no categoryNo,
            (select thank_category_image_url from tbThankCategory where thank_category_no = A.thank_category_no) as categoryImageUrl,
            (select CASE WHEN '$language' = 'ko' THEN thank_category_title_ko ELSE thank_category_title_en END from tbThankCategory where thank_category_no = A.thank_category_no) as categoryTitle 
            from tbThankDiary A 
            where Date(diary_date) = Date('$diaryDate') order by create_date DESC LIMIT 1;";
            
        $result = mysqli_query($connection, $query);

        if($result == false) {
            echo "error: " . mysqli_error($connection);
        }
        return $result;
    }


	function insertThankDiary($thankDiarySeqNo, $userSeqNo, $title, $diaryDate, $content, $imageURL, $categoryNo) {
		global $connection;
        $query = "Insert into tbThankDiary(thank_diary_seq_no, user_seq_no, title, diary_date, content, image_url, thank_category_no, create_date) values('$thankDiarySeqNo', '$userSeqNo', '".mysqli_real_escape_string($connection, $title)."', '".mysqli_real_escape_string($connection, $diaryDate)."', '".mysqli_real_escape_string($connection, $content)."', '".mysqli_real_escape_string($connection, $imageURL)."', '".mysqli_real_escape_string($connection, $categoryNo)."', NOW());";
        
		$result = mysqli_query($connection, $query);

		if($result == false) {
			 echo "error: " . mysqli_error($connection);
		}
		return $result;
    }

	function updateThankDiary($thankDiarySeqNo, $userSeqNo, $title, $diaryDate, $content, $imageURL, $categoryNo) {
        try {
            global $connection;
            $query = "Update tbThankDiary set title='".mysqli_real_escape_string($connection, $title)."', diary_date='".mysqli_real_escape_string($connection, $diaryDate)."', content='".mysqli_real_escape_string($connection, $content)."', image_url='".mysqli_real_escape_string($connection, $imageURL)."', thank_category_no='".mysqli_real_escape_string($connection, $categoryNo)."', update_date=NOW() where thank_diary_seq_no='$thankDiarySeqNo' and user_seq_no='$userSeqNo';";

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
    
    function getThankCategoryList($language) {
        
        global $connection;
        $query = "Select 
            thank_category_no categoryNo, 
            (CASE WHEN '$language' = 'ko' THEN thank_category_title_ko ELSE thank_category_title_en END) as categoryTitle,
            thank_category_image_url categoryImageUrl
        from tbThankCategory
        where active = 'y';";
            
        $result = mysqli_query($connection, $query);

        if($result == false) {
            echo "error: " . mysqli_error($connection);
        }
        return $result;
    }
    
    function getThankDiaryNextSeqNo() {
        
        global $connection;
        $query = "Select 
            IFNULL((MaX(thank_diary_seq_no) + 1), 1) thankDiarySeqNo 
        FROM tbThankDiary";
            
        $result = mysqli_query($connection, $query);

        if($result == false) {
            echo "error: " . mysqli_error($connection);
        }
        return $result;
    }

?>