<?php
include_once __DIR__ . '/../../../database/dbconfig.php';
require_once '../authentication/admin-class.php';

class SendEmail
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->dbConnection();
    }

    public function sendAlertEmail($report)
    {
        $user = new ADMIN();
        $smtp_email = $user->smtpEmail();
        $smtp_password = $user->smtpPassword();
        $system_name = $user->systemName();

        // Retrieve user data
        $stmt = $user->runQuery("SELECT * FROM users WHERE id=:uid");
        $stmt->execute(array(":uid" => $_SESSION['adminSession']));
        $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

        $email = $user_data['email'];
        $subject = "Sensor Threshold Alert";

        $message = "
        <html>
        <head><title>Threshold Alert</title></head>
        <body>
            <p>Dear User,</p>
            <p>$report</p>
            <p>Please take the necessary action immediately.</p>
            <p>Thank you,</p>
            <p>$system_name</p>
        </body>
        </html>";

        $user->send_mail($email, $message, $subject, $smtp_email, $smtp_password, $system_name);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['report'])) {
    $email = new SendEmail();
    $email->sendAlertEmail($_POST['report']);
}
?>
