
<?php
	try {

    // Check if the server is running on localhost
    if ($_SERVER['SERVER_NAME'] === 'localhost' || $_SERVER['SERVER_ADDR'] === '127.0.0.1' || $_SERVER['SERVER_ADDR'] === '192.168.1.72') {
        // Localhost connection
        $pdoConnect = new PDO("mysql:host=localhost;dbname=mawacat", "root", "");
    } else {
        // Live server connection
		$pdoConnect = new PDO("mysql:host=localhost;dbname=u607408406_aquasense", "u607408406_aquasense", "SkkPd9!K|Er8");
    }
		$pdoConnect->setAttribute(PDO:: ATTR_ERRMODE, PDO:: ERRMODE_EXCEPTION);

	}
	catch (PDOException $exc){
		echo $exc -> getMessage();
	}
    catch (PDOException $exc){
        echo $exc -> getMessage();
    exit();
    }
?>