<?php
session_start();
//error_reporting(0);
include('include/config.php');
include('include/checklogin.php');
check_login();

if (isset($_POST['submit'])) {
	$specilization = $_POST['Doctorspecialization'];
	$doctorid = $_POST['doctor'];
	$userid = $_SESSION['id'];
	$fees = $_POST['fees'];
	$request_date = $_POST['request_date'];
	$request_time = $_POST['request_time'];
	$userstatus = 1;
	$docstatus = 1;
	$query = mysqli_query($con, "insert into appointment(doctorSpecialization,doctorId,userId,consultancyFees,appointmentDate,appointmentTime,userStatus,doctorStatus) values('$specilization','$doctorid','$userid','$fees','$request_date','$request_time','$userstatus','$docstatus')");
	if ($query) {
		echo "<script>alert('Your appointment successfully booked');</script>";
	}
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>User | Book Appointment</title>

	<link href="http://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="vendor/themify-icons/themify-icons.min.css">
	<link href="vendor/animate.css/animate.min.css" rel="stylesheet" media="screen">
	<link href="vendor/perfect-scrollbar/perfect-scrollbar.min.css" rel="stylesheet" media="screen">
	<link href="vendor/switchery/switchery.min.css" rel="stylesheet" media="screen">
	<link href="vendor/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css" rel="stylesheet" media="screen">
	<link href="vendor/select2/select2.min.css" rel="stylesheet" media="screen">
	<link href="vendor/bootstrap-datepicker/bootstrap-datepicker3.standalone.min.css" rel="stylesheet" media="screen">
	<link href="vendor/bootstrap-timepicker/bootstrap-timepicker.min.css" rel="stylesheet" media="screen">
	<link rel="stylesheet" href="assets/css/styles.css">
	<link rel="stylesheet" href="assets/css/plugins.css">
	<link rel="stylesheet" href="assets/css/themes/theme-1.css" id="skin_color" />
	<script>
		function getdoctor(val) {
			$.ajax({
				type: "POST",
				url: "get_doctor.php",
				data: 'specilizationid=' + val,
				success: function(data) {
					$("#doctor").html(data);
				}
			});
		}
	</script>
	<script>
		$duration = 15;
		$cleanup = 0;
		$start = "09:00";
		$end = "15:00";
	</script>
	<script>
		function timeslots($duration, $cleanup, $start, $end) {
			$start = new DateTime($start);
			$end = new DateTime($end);
			$interval = new DateInterval("PT".$duration.
				"M");
			$cleanupInterval = new DateInterval("PT".$cleanup.
				"M");
			$slots = array();

			for ($intStart = $start; $intStart < $end; $intStart - > add($interval) - > add($cleanupInterval)) {
				$endPeriod = clone $intStart;
				$endPeriod - > add($interval);
				if ($endPeriod > $end) {
					break;
				}

				$slots[] = $intStart - > format("H:iA").
				" - ".$endPeriod - > format("H:iA");

			}

			return $slots;
		}
	</script>
	<script>
		function getfee(val) {
			$.ajax({
				type: "POST",
				url: "get_doctor.php",
				data: 'doctor=' + val,
				success: function(data) {
					$("#fees").html(data);
				}
			});
		}
	</script>




</head>

<body>
	<div id="app">
		<?php include('include/sidebar.php'); ?>
		<div class="app-content">

			<?php include('include/header.php'); ?>

			<!-- end: TOP NAVBAR -->
			<div class="main-content">
				<div class="wrap-content container" id="container">
					<!-- start: PAGE TITLE -->
					<section id="page-title">
						<div class="row">
							<div class="col-sm-8">
								<h1 class="mainTitle">User | Book Appointment</h1>http://localhost/hospital/hms/manage-medhistory.php
							</div>
							<ol class="breadcrumb">
								<li>
									<span>User</span>
								</li>
								<li class="active">
									<span>Book Appointment</span>
								</li>
							</ol>
					</section>
					<!-- end: PAGE TITLE -->
					<!-- start: BASIC EXAMPLE -->
					<div class="container-fluid container-fullw bg-white">
						<div class="row">
							<div class="col-md-12">

								<div class="row margin-top-30">
									<div class="col-lg-8 col-md-12">
										<div class="panel panel-white">
											<div class="panel-heading">
												<h5 class="panel-title">Book Appointment</h5>
											</div>
											<div class="panel-body">
												<p style="color:red;"><?php echo htmlentities($_SESSION['msg1']); ?>
													<?php echo htmlentities($_SESSION['msg1'] = ""); ?></p>
												<form role="form" name="book" method="post">



													<div class="form-group">
														<label for="DoctorSpecialization">
															Doctor Specialization
														</label>
														<select name="Doctorspecialization" class="form-control" onChange="getdoctor(this.value);" required="required">
															<option value="">Select Specialization</option>
															<?php $ret = mysqli_query($con, "select * from doctorspecilization");
															while ($row = mysqli_fetch_array($ret)) {
															?>
																<option value="<?php echo htmlentities($row['specilization']); ?>">
																	<?php echo htmlentities($row['specilization']); ?>
																</option>
															<?php } ?>

														</select>
													</div>




													<div class="form-group">
														<label for="doctor">
															Doctors
														</label>
														<select name="doctor" class="form-control" id="doctor" onChange="getfee(this.value);" required="required">
															<option value="">Select Doctor</option>
														</select>
													</div>





													<div class="form-group">
														<label for="consultancyfees">
															Consultancy Fees
														</label>
														<select name="fees" class="form-control" id="fees" readonly>

														</select>
													</div>

													<div class="form-group">
														<label for="AppointmentDate">
															Date
														</label>
														<input type="text" name="request_date" id="request_date" value="<?= $request_date ?>" onChange="loadSlot();" class="datepicker form-control-static">
														Days range<select name="range" id="range_id" onChange="loadSlot();">
															<option value="1">1</option>
															<option value="2">2</option>
															<option value="3">3</option>
															<option value="4">4</option>
															<option value="5" selected>5</option>
															<option value="6">6</option>
															<option value="7">7</option>
															<option value="8">8</option>
															<option value="9">9</option>
															<option value="10">10</option>
														</select>


													</div>

													<div class="form-group">
														<label for="Appointmenttime">

															Time

														</label>
														<input type="time" name="request_time" id="request_time" value="<?= $request_time ?>" class="form-control-static" required>
														<br><br>

														<div id="div_slot">
														</div>

													</div>
													<?php
													/*  unset($info);
                                                  unset($data);
                                                $info["table"] = "slot";
                                                $info["fields"] = array("slot.*"); 
                                                $info["where"]   = "1   $whrstr ORDER BY display_order_no ASC";
                                                $arr =  $db->select($info);
                                                
                                                for($i=0;$i<count($arr);$i++)
                                                {*/
													?>
													<!--<button onClick="setSlot(event,'<?= $arr[$i]['slot_time'] ?>');" class="btn"><?= $arr[$i]['slot_time'] ?></button>-->

													<?php
													//}
													?>
											</div>

											<button type="submit" name="submit" class="btn btn-o btn-primary">
												Submit
											</button>
											</form>
										</div>
									</div>
								</div>

							</div>
						</div>

					</div>
				</div>

				<!-- end: BASIC EXAMPLE -->






				<!-- end: SELECT BOXES -->

			</div>
		</div>
	</div>
	<!-- start: FOOTER -->
	<?php include('include/footer.php'); ?>
	<!-- end: FOOTER -->

	<!-- start: SETTINGS -->
	<?php include('include/setting.php'); ?>

	<!-- end: SETTINGS -->
	</div>
	<!-- start: MAIN JAVASCRIPTS -->
	<script src="vendor/jquery/jquery.min.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
	<script src="vendor/modernizr/modernizr.js"></script>
	<script src="vendor/jquery-cookie/jquery.cookie.js"></script>
	<script src="vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
	<script src="vendor/switchery/switchery.min.js"></script>
	<!-- end: MAIN JAVASCRIPTS -->
	<!-- start: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
	<script src="vendor/maskedinput/jquery.maskedinput.min.js"></script>
	<script src="vendor/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js"></script>
	<script src="vendor/autosize/autosize.min.js"></script>
	<script src="vendor/selectFx/classie.js"></script>
	<script src="vendor/selectFx/selectFx.js"></script>
	<script src="vendor/select2/select2.min.js"></script>
	<script src="vendor/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
	<script src="vendor/bootstrap-timepicker/bootstrap-timepicker.min.js"></script>
	<!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
	<!-- start: CLIP-TWO JAVASCRIPTS -->
	<script src="assets/js/main.js"></script>
	<!-- start: JavaScript Event Handlers for this page -->
	<script src="assets/js/form-elements.js"></script>
	<script>
		jQuery(document).ready(function() {
			Main.init();
			FormElements.init();
		});

		$('.datepicker').datepicker({
			format: 'mm-dd-yyyy',
			startDate: '-0d'
		});
	</script>
	<script type="text/javascript">
		$('#timepicker1').timepicker();
	</script>
	<!-- end: JavaScript Event Handlers for this page -->
	<!-- end: CLIP-TWO JAVASCRIPTS -->
	<script>
		function setSlot(e, slot, request_date) {
			e.preventDefault();
			$("#request_time").val(slot);
			$("#request_date").val(request_date);

			return false;
		}

		function loadSlot() {

			if ($("#department_id").val() == "") {
				alert("Department is a required field");
				return;
			}
			if ($("#doctor_users_id").val() == "") {
				alert("Doctor is a required field");
				return;
			}

			request_date = $("#request_date").val();

			$("#div_slot").html("");
			$.ajax({
				url: 'index.php?cmd=load_slot&department_id=' + $("#department_id").val() + '&doctor_users_id=' + $("#doctor_users_id").val() + '&request_date=' + request_date + '&range=' + $("#range_id").val(),
				success: function(html) {
					$("#div_slot").html(html);
					$("#spinner3").html('');
				},
				error: function() {
					callback();
				}
			})

		}

		$(".datepicker").datepicker({
			dateFormat: "yy-mm-dd",
			changeYear: true,
			changeMonth: true,
			showOn: 'button',
			buttonText: 'Show Date',
			buttonImageOnly: true,
			buttonImage: '../../images/calendar.gif',
		});
	</script>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>

</body>

</html>