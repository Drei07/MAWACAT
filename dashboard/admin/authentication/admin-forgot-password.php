<?php
require_once 'admin-class.php';
//URL
$user = new ADMIN();
$main_url = $user->mainUrl();
$smtp_email = $user->smtpEmail();
$smtp_password = $user->smtpPassword();
$system_name = $user->systemName();

if(isset($_POST['btn-forgot-password']))
{
 $email = $_POST['email'];
 
 $stmt = $user->runQuery("SELECT id, tokencode FROM users WHERE email=:email AND user_type = :user_type LIMIT 1");
 $stmt->execute(array(":email"=>$email, "user_type" => 1));
 $row = $stmt->fetch(PDO::FETCH_ASSOC); 
 if($stmt->rowCount() == 1)
 {
  $id = base64_encode($row['id']);
  $code = ($row['tokencode']);
  
  $message= "
  <!DOCTYPE html>
  <html>
  <head>
      <meta charset='UTF-8'>
      <title>Password Reset</title>
      <style>
          body {
              font-family: Arial, sans-serif;
              background-color: #f5f5f5;
              margin: 0;
              padding: 0;
          }
          
          .container {
              max-width: 600px;
              margin: 0 auto;
              padding: 30px;
              background-color: #ffffff;
              border-radius: 4px;
              box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
          }
          
          h1 {
              color: #333333;
              font-size: 24px;
              margin-bottom: 20px;
          }
          
          p {
              color: #666666;
              font-size: 16px;
              margin-bottom: 10px;
          }
          
          .button {
              display: inline-block;
              padding: 12px 24px;
              background-color: #0088cc;
              color: #ffffff;
              text-decoration: none;
              border-radius: 4px;
              font-size: 16px;
              margin-top: 20px;
          }
          .logo {
            display: block;
            text-align: `center`;
            margin-bottom: 30px;
        }
          
      </style>
  </head>
  <body>
      <div class='container'>
      <div class='logo'>
      <img src='cid:logo' alt='Logo' width='150'>
      </div>
          <h1>Password Reset</h1>
          <p>Hello, $email</p>
          <p>We have received a request to reset your password. If you made this request, please click the following link to reset your password:</p>
          <p><a class='button' href='$main_url/reset-password?id=$id&code=$code'>Reset Password</a></p>
          <p>If you didn't make this request, you can safely ignore this email.</p>
          <p>Thank you!</p>
      </div>
  </body>
  </html>
       ";
  $subject = "Password Reset";
  
  $user->send_mail($email,$message,$subject,$smtp_email,$smtp_password,$system_name);
  
  $_SESSION['status_title'] = "Success !";
  $_SESSION['status'] = "We've sent the password reset link to $email, kindly check your spam folder and 'Report not spam' to click the link.";
  $_SESSION['status_code'] = "success";
  header('Location: ../../../');
 }
 else
 {
    $_SESSION['status_title'] = "Oops !";
    $_SESSION['status'] = "Entered email not found";
    $_SESSION['status_code'] = "error";
    header('Location: ../../../forgot-password');
 }
}
?>