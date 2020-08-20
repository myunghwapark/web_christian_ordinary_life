<?php
    function getUser($userEmail, $userPassword) {
        global $connection;
		$query = "Select 
			user_seq_no, 
			user_name, 
			user_email, 
			user_grade 
			from tbUser 
			where user_email = '$userEmail' 
			and user_password = SHA1(UNHEX(SHA1('$userPassword'))) 
			and user_state = 'U001_001';";
			
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
			'$userName', 
			'$userEmail', 
			'$userGrade', 
			SHA1(UNHEX(SHA1('$userPassword'))), 
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
			where user_email = '$userEmail';";

		$result = mysqli_query($connection, $query);

		if($result == false) {
			 echo "error: " . mysqli_error($connection);
		}
		return mysqli_num_rows($result);
    }
    
	function updateUserPassword($userSeqNo, $userPassword) {
		global $connection;
		$query = "Update tbUser set 
			user_password='SHA1(UNHEX(SHA1('$userPassword'))), update_date=NOW() 
			where user_seq_no='$userSeqNo';";

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