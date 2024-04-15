<?php
require_once '../authentication/admin-class.php';


class WaterQualityController
{
    private $admin;
    private $conn;


    public function __construct()
    {
        $this->admin = new ADMIN();


        $database = new Database();
        $db = $database->dbConnection();
        $this->conn = $db;
    }

    public function setAnalyzingTime($user_id, $analyzingTime){
        $stmt = $this->admin->runQuery('UPDATE analyzing_time SET time=:time WHERE user_id=:user_id');
        $exec = $stmt->execute(array(
            ":user_id"    => $user_id,
            ":time"       => $analyzingTime,
        ));

        if ($exec) {
            $_SESSION['status_title'] = 'Success!';
            $_SESSION['status'] = 'Analyzing time set succesfully!';
            $_SESSION['status_code'] = 'success';
            $_SESSION['status_timer'] = 40000;
        } else {
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'Something went wrong, please try again!';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 100000;
        }

        header('Location: ../');
        exit();
    }

    public function sensosValue($user_id, $temperatureValue, $phValue, $TDSValue, $turbidityValue){
        $stmt = $this->admin->runQuery('INSERT INTO water_metrics (user_id, temperature_level, ph_level, tds_level, turbidity_level) VALUES (:user_id, :temperature_level, :ph_level, :tds_level, :turbidity_level)');
        $exec = $stmt->execute(array(
            ":user_id"           => $user_id,
            ":temperature_level" => $temperatureValue,
            ":ph_level"          => $phValue,
            ":tds_level"         => $TDSValue,
            ":turbidity_level"   => $turbidityValue,
        ));
        if ($exec) {
            $_SESSION['status_title'] = "Success!";
            $_SESSION['status'] = "Water Quality Metrics Succesfully Saved!";
            $_SESSION['status_code'] = "success";
            $_SESSION['status_timer'] = 40000;
        } else {
            $_SESSION['status_title'] = "Oops!";
            $_SESSION['status'] = 'Something went wrong, please try again!';
            $_SESSION['status_code'] = "error";
            $_SESSION['status_timer'] = 100000;
        }
        header('Location: ../');
        exit(); // Exit after redirect
    }
}

if(isset($_POST['btn-set-time'])){
    $user_id                  = trim($_POST['user_id']);
    $analyzingTime            = trim($_POST['analyzingTime']);

    $set_analyzingTime = new WaterQualityController();
    $set_analyzingTime->setAnalyzingTime($user_id, $analyzingTime);

}

if(isset($_POST['btn-sensor-value'])){
    $user_id                  = trim($_POST['user_id']);
    $temperatureValue         = trim($_POST['temperatureValue']);
    $phValue                  = trim($_POST['phValue']);
    $TDSValue                 = trim($_POST['TDSValue']);
    $turbidityValue           = trim($_POST['turbidityValue']);

    $sensors_value = new WaterQualityController();
    $sensors_value->sensosValue($user_id, $temperatureValue, $phValue, $TDSValue, $turbidityValue);
}
?>