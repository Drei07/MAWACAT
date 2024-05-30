<?php
include_once '../../database/dbconfig2.php';
require_once 'authentication/admin-class.php';
include_once '../../config/settings-configuration.php';
include_once '../../config/header.php';
include_once '../../config/footer.php';

$config = new SystemConfig();
$header_dashboard = new HeaderDashboard($config);
$footer_dashboard = new FooterDashboard();
$user = new ADMIN();

if(!$user->isUserLoggedIn())
{
 $user->redirect('../../');
}

// retrieve user data
$stmt = $user->runQuery("SELECT * FROM users WHERE id=:uid");
$stmt->execute(array(":uid"=>$_SESSION['adminSession']));
$user_data = $stmt->fetch(PDO::FETCH_ASSOC);

// retrieve profile user and full name
$user_id                = $user_data['id'];
$user_profile           = $user_data['profile'];
$user_fname             = $user_data['first_name'];
$user_mname             = $user_data['middle_name'];
$user_lname             = $user_data['last_name'];
$user_fullname          = $user_data['last_name'] . ", " . $user_data['first_name'];
$user_sex               = $user_data['sex'];
$user_birth_date        = $user_data['date_of_birth'];
$user_age               = $user_data['age'];
$user_civil_status      = $user_data['civil_status'];
$user_phone_number      = $user_data['phone_number'];
$user_email             = $user_data['email'];
$user_last_update       = $user_data['updated_at'];

// Query to fetch data from the water_quality_parameter table
$stmt1 = $user->runQuery("SELECT * FROM water_quality_parameter");
$stmt1->execute();
$parameter_data = $stmt1->fetchAll(PDO::FETCH_ASSOC);

// Initialize variables
$phLow = $phHigh = $tdsLow = $tdsHigh = $turbidityLow = $turbidityHigh = $temperatureLow = $temperatureHigh = null;

// Iterate over the fetched data to assign values based on parameter IDs
foreach ($parameter_data as $data) {
    switch ($data['id']) {
        case 1:
            $phLow = $data['low'];
            $phHigh = $data['high'];
            break;
        case 2:
            $tdsLow = $data['low'];
            $tdsHigh = $data['high'];
            break;
        case 3:
            $turbidityLow = $data['low'];
            $turbidityHigh = $data['high'];
            break;
        case 4:
            $temperatureLow = $data['low'];
            $temperatureHigh = $data['high'];
            break;
    }
}
