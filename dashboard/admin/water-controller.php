<?php
include_once 'header.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php echo $header_dashboard->getHeaderDashboard() ?>

    <title>Water Analyzer Dashboard</title>
</head>
<body>
    <div class="wifi_status">
        hl
    </div>
    <div class="gauge_dashboard">
        <div class="gauge phLevel">
            <div class="card gauge_card">
                <p class="card-title">PH Level Gauge</p>
                <div id="pHLevel"></div>
            </div>
            <div class="card graphs_card">
                <div id="phGraph"></div>
            </div>
        </div>

        <div class="gauge tdsLevel">
            <div class="card gauge_card">
                <p class="card-title">TDS Level Gauge</p>
                <div id="TDSLevel"></div>
            </div>
            <div class="card graphs_card">
                c
            </div>
        </div>

        <div class="gauge turbidtyLevel">
            <div class="card gauge_card">
                <p class="card-title">Turbidity Level Gauge</p>
                <div id="turbidity_Level"></div>
            </div>
            <div class="card graphs_card">
                c
            </div>
        </div>
    </div>

	<?php echo $footer_dashboard->getFooterDashboard() ?>
    <?php include_once '../../config/sweetalert.php'; ?>
    <script src="../../src/js/gauge.js"></script>


</body>

</html>