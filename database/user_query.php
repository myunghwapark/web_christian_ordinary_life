<?php
    function getUser($userEmail) {
        global $connection;
		$query = "Select 
			user_seq_no, 
			user_name, 
			user_email, 
			user_grade,
			user_password 
			from tbUser 
			where user_email = '".mysqli_real_escape_string($connection, $userEmail)."' 
			and user_state = 'U001_001' LIMIT 1;";
			
		$result = mysqli_query($connection, $query);

		if($result == false) {
			 echo "error: " . mysqli_error($connection);
		}
		return $result;
    }

	function registerUser($userName, $userEmail, $userPassword, $userGrade) {
		global $connection;
		$query = "Insert into tbUser(
			user_name, 
			user_email, 
			user_grade, 
			user_password, 
			create_date) 
		values(
			'".mysqli_real_escape_string($connection, $userName)."', 
			'".mysqli_real_escape_string($connection, $userEmail)."', 
			'$userGrade', 
			'".mysqli_real_escape_string($connection, $userPassword)."', 
			NOW());";

		$result = mysqli_query($connection, $query);

		if($result == false) {
			 return "error: " . mysqli_error($connection);
		}
		mysqli_close($connection);
		return $result;
    }
    
	function getUserEmail($userEmail) {
		global $connection;
		$query = "Select 
			user_email userEmailCount 
			from tbUser 
			where user_email = '".mysqli_real_escape_string($connection, $userEmail)."';";

		$result = mysqli_query($connection, $query);

		if($result == false) {
			 echo "error: " . mysqli_error($connection);
		}
		return mysqli_num_rows($result);
    }
    
	function getUserNameByEmail($userEmail) {
		global $connection;
		$query = "Select 
			user_name userName 
			from tbUser 
			where user_email = '".mysqli_real_escape_string($connection, $userEmail)."';";

		$result = mysqli_query($connection, $query);

		if($result == false) {
			 echo "error: " . mysqli_error($connection);
		}
		return $result;
    }
    
	function updateUserPassword($userEmail, $userPassword) {
		global $connection;
		$query = "Update tbUser set 
			user_password='".mysqli_real_escape_string($connection, $userPassword)."', update_date=NOW() 
			where user_email='".mysqli_real_escape_string($connection, $userEmail)."';";

		$result = mysqli_query($connection, $query);

		if($result == false) {
			 echo "error: " . mysqli_error($connection);
		}
		return $result;
	}
    
	function updateUserStatus($userSeqNo, $statusCode) {
		global $connection;
		$query = "Update tbUser set 
			user_state='$statusCode', update_date=NOW() 
			where user_seq_no='$userSeqNo';";

		$result = mysqli_query($connection, $query);

		if($result == false) {
			 echo "error: " . mysqli_error($connection);
		}
		return $result;
	}
?>