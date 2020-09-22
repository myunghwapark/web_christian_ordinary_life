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
            content, 
            image_url imageURL,
            thank_category_no categoryNo,
            (select thank_category_image_url from tbThankCategory where thank_category_no = A.thank_category_no) as categoryImageUrl
            from tbThankDiary A 
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


    function getThankDiaryBySeqNo($thankDiarySeqNo) {
        global $connection;
        $query = "Select 
            thank_diary_seq_no thankDiarySeqNo, 
            title, 
            diary_date diaryDate, 
            content,
            create_date createDate, 
            image_url imageURL,
            thank_category_no categoryNo,
            (select thank_category_image_url from tbThankCategory where thank_category_no = A.thank_category_no) as categoryImageUrl 
            from tbThankDiary A 
            where thank_diary_seq_no = '$thankDiarySeqNo';";
            
        $result = mysqli_query($connection, $query);

        if($result == false) {
            echo "error: " . mysqli_error($connection);
        }
        return $result;
    }


    function getThankDiaryByDiaryDate($diaryDate) {
        global $connection;
        $query = "Select 
            thank_diary_seq_no thankDiarySeqNo, 
            title, 
            diary_date diaryDate, 
            content,
            create_date createDate, 
            image_url imageURL,
            thank_category_no categoryNo,
            (select thank_category_image_url from tbThankCategory where thank_category_no = A.thank_category_no) as categoryImageUrl 
            from tbThankDiary A 
            where Date(diary_date) = '$diaryDate';";
            
        $result = mysqli_query($connection, $query);

        if($result == false) {
            echo "error: " . mysqli_error($connection);
        }
        return $result;
    }


	function insertThankDiary($userSeqNo, $title, $diaryDate, $content, $imageURL, $categoryNo) {
		global $connection;
        $query = "Insert into tbThankDiary(user_seq_no, title, diary_date, content, image_url, thank_category_no, create_date) values('$userSeqNo', '$title', '$diaryDate', '$content', '$imageURL', '$categoryNo', NOW());";
        
		$result = mysqli_query($connection, $query);

		if($result == false) {
			 echo "error: " . mysqli_error($connection);
		}
		return $result;
    }

	function updateThankDiary($thankDiarySeqNo, $userSeqNo, $title, $diaryDate, $content, $imageURL, $categoryNo) {
        try {
            global $connection;
            $query = "Update tbThankDiary set title='$title', diary_date='$diaryDate', content='$content', image_url='$imageURL', thank_category_no='$categoryNo', update_date=NOW() where thank_diary_seq_no='$thankDiarySeqNo' and user_seq_no='$userSeqNo';";

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

?>