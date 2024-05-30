<?php
include_once 'header.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<?php echo $header_dashboard->getHeaderDashboard() ?>

	<title>Quality Parameter</title>
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
			<li>
				<a href="./">
					<i class='bx bxs-dashboard'></i>
					<span class="text">Dashboard</span>
				</a>
			</li>
			<li>
				<a href="data-analysis">
					<i class='bx bxs-tachometer'></i>
					<span class="text">Data Analysis</span>
				</a>
			</li>
			<li class="active">
				<a href="quality_parameter">
					<i class='bx bx-water'></i>
					<span class="text">Quality Parameter</span>
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
					<h1>Parameter</h1>
					<ul class="breadcrumb">
						<li>
							<a class="active" href="./">Home</a>
						</li>
						<li>|</li>
						<li>
							<a href="">Parameter</a>
						</li>
					</ul>
				</div>
			</div>

			<div class="table-data">
				<div class="order">
					<div class="head">
						<h3><i class='bx bxs-cog' ></i> Configuration of Parameter</h3>
					</div>
                    <!-- BODY -->
					<section class="data-form">
						<div class="header"></div>
						<div class="registration">
							<form action="controller/waterQuality-controller.php" method="POST" class="row gx-5 needs-validation" name="form" onsubmit="return validate()"  novalidate style="overflow: hidden;">
								<div class="row gx-5 needs-validation">

									<label class="form-label" style="text-align: left; padding-top: .5rem; padding-bottom: 2rem; font-size: 1rem; font-weight: bold;"><i class='bx bxs-cog'></i> pH Parameter Configuration</label>
									<input type="hidden" name="id" value="1">
									<div class="col-md-6">
										<label for="ph_low" class="form-label">Low<span> *</span></label>
										<input type="text" class="form-control" autocapitalize="on" autocomplete="off" name="low" id="ph_low" value="<?php echo $phLow ?>" required>
										<div class="invalid-feedback">
										Please provide a Low Value.
										</div>
									</div>

									<div class="col-md-6">
										<label for="ph_high" class="form-label">High<span> *</span></label>
										<input type="text" class="form-control" autocapitalize="on" autocomplete="off" name="high" id="ph_high"  value="<?php echo $phHigh ?>" required>
										<div class="invalid-feedback">
										Please provide a High Value.
										</div>
									</div>

								</div>

								<div class="addBtn">
									<button type="submit" class="btn-dark" name="btn-update-parameter" id="btn-update" onclick="return IsEmpty(); sexEmpty();">Update</button>
								</div>
							</form>
						</div>
					</section>
					
					<!-- System Logo  -->

					<section class="data-form">
						<div class="header"></div>
						<div class="registration">
							<form action="controller/waterQuality-controller.php" method="POST" enctype="multipart/form-data" class="row gx-5 needs-validation" name="form" onsubmit="return validate()"  novalidate style="overflow: hidden;">
								<div class="row gx-5 needs-validation">

								<label class="form-label" style="text-align: left; padding-top: .5rem; padding-bottom: 2rem; font-size: 1rem; font-weight: bold;"><i class='bx bxs-cog'></i> TDS Parameters Configuration</label>
								<input type="hidden" name="id" value="2">
								<div class="col-md-6">
									<label for="tds_low" class="form-label">Low<span> *</span></label>
									<input type="text" class="form-control" autocapitalize="on" autocomplete="off" name="low" id="tds_low"  value="<?php echo $tdsLow ?>" required>
									<div class="invalid-feedback">
									Please provide a Low Value.
									</div>
								</div>

								<div class="col-md-6">
									<label for="tds_high" class="form-label">High<span> *</span></label>
									<input type="text" class="form-control" autocapitalize="on" autocomplete="off" name="high" id="tds_high"  value="<?php echo $tdsHigh ?>" required>
									<div class="invalid-feedback">
									Please provide a High Value.
									</div>
								</div>

								</div>

								<div class="addBtn">
									<button type="submit" class="btn-dark" name="btn-update-parameter" id="btn-update" onclick="return IsEmpty(); sexEmpty();">Update</button>
								</div>
							</form>
						</div>
					</section>

					<!-- SMTP MAILER -->

					<section class="data-form">
						<div class="header"></div>
						<div class="registration">
							<form action="controller/waterQuality-controller.php" method="POST" class="row gx-5 needs-validation" name="form" onsubmit="return validate()"  novalidate style="overflow: hidden;">
								<div class="row gx-5 needs-validation">

								<label class="form-label" style="text-align: left; padding-top: .5rem; padding-bottom: 2rem; font-size: 1rem; font-weight: bold;"><i class='bx bxs-edit'></i>Turbidity Parameter Configuration</label>
								<input type="hidden" name="id" value="3">
								<div class="col-md-6">
										<label for="turbidity_low" class="form-label">Low<span> *</span></label>
										<input type="text" class="form-control" autocapitalize="on" autocomplete="off" name="low" id="turbidity_low" value="<?php echo $turbidityLow ?>" required>
										<div class="invalid-feedback">
										Please provide a Low Value.
										</div>
									</div>

									<div class="col-md-6">
										<label for="turbidity_high" class="form-label">High<span> *</span></label>
										<input type="text" class="form-control" autocapitalize="on" autocomplete="off" name="high" id="turbidity_high" value="<?php echo $turbidityHigh ?>" required>
										<div class="invalid-feedback">
										Please provide a High Value.
										</div>
									</div>

								</div>

								<div class="addBtn">
									<button type="submit" class="btn-dark" name="btn-update-parameter" id="btn-update" onclick="return IsEmpty(); sexEmpty();">Update</button>
								</div>
							</form>
						</div>
					</section>

					<!-- Google reCAPTCHA V3  -->

					<section class="data-form">
						<div class="header"></div>
						<div class="registration">
							<form action="controller/waterQuality-controller.php" method="POST" class="row gx-5 needs-validation" name="form" onsubmit="return validate()"  novalidate style="overflow: hidden;">
								<div class="row gx-5 needs-validation">

								<label class="form-label" style="text-align: left; padding-top: .5rem; padding-bottom: 2rem; font-size: 1rem; font-weight: bold;"><i class='bx bxs-edit'></i> Temperature Parameter Configuration</label>
								<input type="hidden" name="id" value="4">
								<div class="col-md-6">
										<label for="temperature_low" class="form-label">Low<span> *</span></label>
										<input type="text" class="form-control" autocapitalize="on" autocomplete="off" name="low" id="temperature_low" value="<?php echo $temperatureLow ?>" required>
										<div class="invalid-feedback">
										Please provide a Low Value.
										</div>
									</div>

									<div class="col-md-6">
										<label for="temperature_high" class="form-label">High<span> *</span></label>
										<input type="text" class="form-control" autocapitalize="on" autocomplete="off" name="high" id="temperature_high" value="<?php echo $temperatureHigh ?>" required>
										<div class="invalid-feedback">
										Please provide a High Value.
										</div>
									</div>

								</div>

								<div class="addBtn">
									<button type="submit" class="btn-dark" name="btn-update-parameter" id="btn-update" onclick="return IsEmpty(); sexEmpty();">Update</button>
								</div>
							</form>
						</div>
					</section>
				</div>
			</div>
				</div>
			</div>
		</main>
		<!-- MAIN -->
	</section>
	<!-- CONTENT -->

	<?php echo $footer_dashboard->getFooterDashboard() ?>
	<?php include_once '../../config/sweetalert.php'; ?>
</body>

</html>