<?php
require('../../common/header.php');
require('../../database/user_query.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/PHPMailer/src/Exception.php';
require '../../vendor/PHPMailer/src/PHPMailer.php';
require '../../vendor/PHPMailer/src/SMTP.php';

try {

    $recipientEmail = $obj['recipientEmail'];
    $language = $obj['language'];



    function randPassword($upper = 2, $lower = 3, $numeric = 2, $other = 1) { 

        $pass_order = Array(); 
        $passWord = ''; 

        //Create contents of the password 
        for ($i = 0; $i < $upper; $i++) { 
            $pass_order[] = chr(rand(65, 90)); 
        } 
        for ($i = 0; $i < $lower; $i++) { 
            $pass_order[] = chr(rand(97, 122)); 
        } 
        for ($i = 0; $i < $numeric; $i++) { 
            $pass_order[] = chr(rand(48, 57)); 
        } 
        //$pass_order[] = chr(rand(33, 47)); 
        $chars = '!@#$%^&*~';
        $count = mb_strlen($chars);

        for ($i = 0; $i < $other; $i++) {
            $index = rand(0, $count - 1);
            $pass_order[] =  mb_substr($chars, $index, 1);
        }
        

        //using shuffle() to shuffle the order
        shuffle($pass_order); 

        //Final password string 
        foreach ($pass_order as $char) { 
            $passWord .= $char; 
        } 
        return $passWord; 
    } 
    $getUserEmailResult = getUserEmail($recipientEmail);

    if($getUserEmailResult > 0) {
        $nameResult = getUserNameByEmail($recipientEmail);
        $row = $nameResult->fetch_assoc();
        $recipientName = $row['userName'];

        $randomPassword = randPassword();

        /* Create the new password hash. */
        $hash = password_hash($randomPassword, PASSWORD_DEFAULT, $options);

        // Password update
        $updatePasswordResult = updateUserPassword($recipientEmail, $hash);

        if($updatePasswordResult == 1) {

            $mail = new PHPMailer(true);

            $senderName = '';
            $subject = '';
            $body = '';
            $altBody = '';  // For non HTML mail client
            if($language == 'ko') {
                $senderName = '크리스천의 평범한 삶';
                $subject = '크리스천의 평범한 삶에서 임시 비밀번호를 보내드립니다.';

                $body = '샬롬 '.$recipientName.'님.<br/><br/>'.$recipientName.'님의 임시 비밀번호는 <b>'.$randomPassword.'</b>입니다.<br/>전송드리는 8자리의 비밀번호로 로그인 후 크리스천의 평범한 삶 앱에서 비밀번호 변경이 가능합니다.<br/>감사합니다.<br/><br/>크리스천의 평범한 삶 드림';

                $altBody = '샬롬 '.$recipientName.'님.\n\n'.$recipientName.'님의 임시 비밀번호는  '.$randomPassword.' 입니다.\n전송드리는 8자리의 비밀번호로 로그인 후 크리스천의 평범한 삶 앱에서 비밀번호 변경이 가능합니다.\n감사합니다.\n\n크리스천의 평범한 삶 드림';
            }
            else {
                $senderName = 'Ordinary life of Christian';
                $subject = 'A new password from Ordinary life of Christian.';

                $body = 'Hello '.$recipientName.'.<br/>We send your a new password.<br/>Your password is <b>'.$randomPassword.'</b><br/>After logging in with the 8-digit password we sent, you can change the password in the Ordinary life of Christian app.<br/><br/>Best regards.<br/>Ordinary life of Christian.';

                $altBody = 'Hello '.$recipientName.'.\nWe send your a new password.\nYour password is '.$randomPassword.'\nAfter logging in with the 8-digit password we sent, you can change the password in the Ordinary life of Christian app.\n\nBest regards.\nOrdinary life of Christian.';
            }
            

            try {
                //Server settings
                $mail->SMTPDebug = 0;
                //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
                $mail->CharSet = 'utf-8';   //한글이 안깨지게 CharSet 설정
                $mail->Encoding = 'base64';
                $mail->isSMTP();                                            // Send using SMTP
                $mail->Host       = 'smtp.hostinger.kr';                    // Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
                $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
                $mail->SMTPSecure = 'ssl'; // SSL을 사용함
                $mail->Username   = $smtpUserName;                          // SMTP username
                $mail->Password   = $smtpPassword;                          // SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged

                //Recipients
                $mail->SetFrom($smtpUserName, $senderName);         // 보내는 사람 email 주소와 표시될 이름 (표시될 이름은 생략가능)
                $mail->addAddress($recipientEmail, $recipientName);         // Add a recipient
                $mail->addReplyTo($smtpUserName, $senderName);


                // Content
                $mail->isHTML(true);                                            // Set email format to HTML
                $mail->Subject = $subject;
                $mail->Body    = $body;
                $mail->AltBody = $altBody;

                $mail->send();
                echo '{"result":"success"}';
            } catch (Exception $e) {
                echo '{"result":"fail", "errorCode": "03", "errorMessage": "Message could not be sent. Mailer Error: "'.($mail->ErrorInfo).'"}';
            }
        }
        else {
            echo '{"result":"fail", "errorCode": "02", "errorMessage": "'.$commonError["message"].'"}';
        }


    } else {
        echo '{"result":"fail", "errorCode": "01", "errorMessage": "The email you entered is not registered."}';
    }


}
catch (Exception $e) {
    echo '{"result":"fail", "errorCode": "00", "errorMessage": "'.$e->getMessage().'"}';
}
    
?>