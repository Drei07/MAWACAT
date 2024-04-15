<?php
include_once 'header.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<?php echo $header_dashboard->getHeaderDashboard() ?>
	<link href='https://fonts.googleapis.com/css?family=Antonio' rel='stylesheet'>

	<title>Dashboard</title>
</head>

<body>

	<!-- Loader -->
	<div class="loader"></div>

	<!-- SIDEBAR -->
	<section id="sidebar">
		<a href="" class="brand">
			<img src="../../src/img/<?php echo $config->getSystemLogo() ?>" alt="logo">
			<span class="text">MAWACAT<br>
				<p>AQUA SENSE</p>
			</span>
		</a>
		<ul class="side-menu top">
			<li class="active">
				<a href="./">
					<i class='bx bxs-dashboard'></i>
					<span class="text">Dashboard</span>
				</a>
			</li>

			<li>
				<a href="metrics">
					<i class='bx bxs-tachometer'></i>
					<span class="text">Metrics</span>
				</a>
			</li>
		</ul>
		<ul class="side-menu top">
			<li>
				<a href="settings">
					<i class='bx bxs-cog'></i>
					<span class="text">Settings</span>
				</a>
			</li>
			<li>
				<a href="audit-trail">
					<i class='bx bxl-blogger'></i>
					<span class="text">Audit Trail</span>
				</a>
			</li>
			<li>
				<a href="authentication/admin-signout" class="btn-signout">
					<i class='bx bxs-log-out-circle'></i>
					<span class="text">Signout</span>
				</a>
			</li>
		</ul>
	</section>
	<!-- SIDEBAR -->
	<!-- CONTENT -->
	<section id="content">
		<!-- NAVBAR -->
		<nav>
			<i class='bx bx-menu'></i>
			<form action="#">
				<div class="form-input">
					<button type="submit" class="search-btn"><i class='bx bx-search'></i></button>
				</div>
			</form>
			<div class="username">
				<span>Hello, <label for=""><?php echo $user_fname ?></label></span>
			</div>
			<a href="profile" class="profile" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Profile">
				<img src="../../src/img/<?php echo $user_profile ?>">
			</a>
		</nav>
		<!-- NAVBAR -->

		<!-- MAIN -->
		<main>
			<div class="head-title">
				<div class="left">
					<h1>Dashboard</h1>
					<ul class="breadcrumb">
						<li>
							<a class="active" href="./">Home</a>
						</li>
						<li>|</li>
						<li>
							<a href="">Dashboard</a>
						</li>
					</ul>
				</div>
			</div>

			<ul class="dashboard_data">

				<div class="gauge_dashboard">
					<div class="status">
						<div class="card arduino">
							<h1>SENSOR STATUS</h1>
							<div class="sensor-data">
								<div class="status-item">
									<span class="indicator light-green"></span>
									<span class="sensor-label">Device :</span>
									<span class="sensor-value" id="wifi_status">Connecting....</span>
								</div>

								<div class="status-item">
									<span class="indicator light-green"></span>
									<span class="sensor-label">Temperature :</span>
									<span class="sensor-value" id="temperatureValue1">0°C</span>
								</div>

								<div class="status-item">
									<span class="indicator light-green"></span>
									<span class="sensor-label">pH Level :</span>
									<span class="sensor-value" id="pHlevel1">0 pH</span>
								</div>

								<div class="status-item">
									<span class="indicator light-green"></span>
									<span class="sensor-label">TDS Level :</span>
									<span class="sensor-value" id="TDSValue1">0 ppm</span>
								</div>


								<div class="status-item">
									<span class="indicator light-green"></span>
									<span class="sensor-label">Turbidity Level :</span>
									<span class="sensor-value" id="turbidityValue1">0 NTU</span>
								</div>
							</div>
						</div>
						<div class="card time">
							<h1 id="countdownTimer"><?php echo $config->getanalyzingTime() / 1000; ?><span>s</span></h1>
							<button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#setTimeModals">SET</button>
						</div>
						<div class="card buttons">
							<input type="hidden" id="analyzingTime" value="<?php echo $config->getanalyzingTime() ?>" />

							<button type="button" style="display:none;" id="startBtn" onclick="startMonitoring()" class="btn btn-warning">START</button>
							<button type="button" style="display:none;" id="restartBtn" onclick="restartMonitoring()" class="btn btn-primary">RESTART</button>
							<img src="../../src/img/wifi_load.gif" id="wifiLoadIcon" alt="" width="250px">
							<img src="../../src/img/analyzing.gif" style="display:none;" id="analyzingIcon" alt="" width="250px">

							<form action="controller/waterQuality-controller.php" method="POST" name="form" onsubmit="return validate()" novalidate style="overflow: hidden;">
								<input type="hidden" name="user_id" value="<?php echo $user_id?>">
								<input type="hidden" id="temperatureValue" name="temperatureValue">
								<input type="hidden" id="phValue" name="phValue">
								<input type="hidden" id="TDSValue" name="TDSValue">
								<input type="hidden" id="turbidityValue" name="turbidityValue">
								<button type="submit" name="btn-sensor-value" style="display:none;" id="saveBtn" class="btn btn-success">SAVE</button>
							</form>
						</div>
					</div>
					<div class="gauge phLevel">
						<div class="card gauge_card">
							<p class="card-title">Temperature Level Gauge</p>
							<div id="temperature_Level"></div>
						</div>
						<div class="card gauge_card">
							<p class="card-title">pH Level Gauge</p>
							<div id="pHLevel"></div>
						</div>
						<div class="card gauge_card">
							<p class="card-title">TDS Level Gauge</p>
							<div id="TDSLevel"></div>
						</div>
						<div class="card gauge_card">
							<p class="card-title">Turbidity Level Gauge</p>
							<div id="turbidity_Level"></div>
						</div>
					</div>

				</div>
			</ul>
		</main>
		<!-- MAIN -->
		<!-- MODALS -->
		<div class="class-modal">
			<div class="modal fade" id="setTimeModals" tabindex="-1" aria-labelledby="classModalLabel" aria-hidden="true" data-bs-backdrop="static">
				<div class="modal-dialog modal-dialog-centered modal-lg">
					<div class="modal-content">
						<div class="header"></div>
						<div class="modal-header">
							<h5 class="modal-title" id="classModalLabel"><i class='bx bxs-timer' ></i> Set Analyzing Time</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeButton"></button>
						</div>
						<div class="modal-body">
							<section class="data-form-modals">
								<div class="registration">
									<form action="controller/waterQuality-controller.php" method="POST" class="row gx-5 needs-validation" name="form" onsubmit="return validate()" novalidate style="overflow: hidden;">
										<div class="row gx-5 needs-validation">
											<input type="hidden" name="user_id" value="<?php echo $user_id?>">

											<div class="col-md-12">
												<label for="analyzingTime" class="form-label">Analyzing Time<span> *</span></label>
												<input type="text" class="form-control numbers"  autocapitalize="off" inputmode="numeric" autocomplete="off" name="analyzingTime" id="analyzingTime" minlength="4" maxlength="7" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" required placeholder="ex.3000">
											</div>
											<div class="invalid-feedback">
												Please set the analyzing time .
											</div>

										</div>

										<div class="addBtn">
											<button type="submit" class="btn-dark" name="btn-set-time" id="btn-add" onclick="return IsEmpty(); sexEmpty();">SET</button>
										</div>
									</form>
								</div>
							</section>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- CONTENT -->

	<?php echo $footer_dashboard->getFooterDashboard() ?>
	<?php include_once '../../config/sweetalert.php'; ?>
	<script src="../../src/js/gauge.js"></script>
	<script src="../../src/js/analyzingTime.js"></script>
</body>

</html>