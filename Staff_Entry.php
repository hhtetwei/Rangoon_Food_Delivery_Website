<?php
session_start();
include('connection.php');

if (!isset($_SESSION['Staff_ID'])) {
	echo "<script>alert('Please login to continue')
		window.location='Staff_Login.php'
		</script>";
}

$name = "";
$mobile = "";
$address = "";
$email = "";
$type = "";
$password = "";

if (isset($_GET['Staff_ID'])) {
	$StaffID = $_GET['Staff_ID'];

	$query = "SELECT * FROM staff WHERE Staff_ID='$StaffID'";
	$ret = mysqli_query($connection, $query);
	$data = mysqli_fetch_array($ret);

	$name = $data['Staff_Name'];
	$mobile = $data['Phone'];
	$address = $data['Address'];
	$email = $data['Email'];
	$type = $data['StaffType'];
	$password = $data['Password'];
}

if (isset($_POST['btnCreate'])) {
	$txtStaffName = $_POST['txtStaffName'];
	$txtPassword = $_POST['txtPassword'];
	$txtPhone = $_POST['txtPhone'];
	$txtAddress = $_POST['txtAddress'];
	$txtEmail = $_POST['txtEmail'];
	$txtStaffType = $_POST['txtStaffType'];

	//Check Staff Email Already exist or not
	$check_email = "SELECT * FROM staff WHERE Email='$txtEmail'";
	$result = mysqli_query($connection, $check_email);
	$count = mysqli_num_rows($result);

	if ($count > 0) {
		echo "<script>window.alert('Staff Email $txtEmail aleready exist!')</script>";
		echo "<script>window.location='Staff_Entry.php'</script>";
		exit();
	}


	$insert_data = "INSERT INTO staff
				  (Staff_Name,Password,Phone,Address,Email,StaffType)
				  VALUES
				  ('$txtStaffName','$txtPassword','$txtPhone','$txtAddress','$txtEmail','$txtStaffType')
				  ";
	$result = mysqli_query($connection, $insert_data);

	if ($result) //True
	{
		echo "<script>window.alert('Staff Account Successfully Created!')</script>";
	} else {
		echo "<p>Something went wrong in Staff Entry" . mysqli_error($connection) . "</p>";
	}
}

if (isset($_POST['btnUpdate'])) {
	$StaffID = $_POST['txtStaffID'];
	$txtStaffName = $_POST['txtStaffName'];
	$txtPassword = $_POST['txtPassword'];
	$txtPhone = $_POST['txtPhone'];
	$txtAddress = $_POST['txtAddress'];
	$txtEmail = $_POST['txtEmail'];
	$txtStaffType = $_POST['txtStaffType'];

	$update_data = "UPDATE staff SET Staff_Name='$txtStaffName', Address='$txtAddress', Phone='$txtPhone', Email='$txtEmail', Password='$txtPassword', StaffType='$txtStaffType' WHERE Staff_ID='$StaffID'";
	$result = mysqli_query($connection, $update_data);

	if ($result) {
		echo "<script>window.alert('Staff Account Successfully Updated!')</script>";
	} else {
		echo "<p>Something went wrong in Staff Update" . mysqli_error($connection) . "</p>";
	}
}

?>
<!DOCTYPE html>
<html>

<head>
	<title>Staff Entry</title>

	<style>
		label {
			margin: 0 !important;
		}

		input {
			margin-bottom: 5px !important;
		}

		form {
			border: 1px solid gray;
			padding: 15px;
		}
	</style>
	<script type="text/javascript" src="js/jquery.js"></script>
	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
	<script src="bootstrap/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="DataTables/datatables.min.js"></script>
	<link rel="stylesheet" type="text/css" href="DataTables/datatables.min.css" />
</head>

<body>
	<?php
	include('header.php');
	?>
	<div class="container-fluid">
		<p class="display-4">Staffs</p>
		<div class="row">
			<div class="col-9 p-3">
				<table id="table" style="width:100%">
					<thead>
						<tr>
							<th>Staff_ID</th>
							<th>Staff-Name</th>
							<th>Phone</th>
							<th>Address</th>
							<th>Email</th>
							<th>StaffType</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$staff_select = "SELECT * FROM staff";
						$staff_ret = mysqli_query($connection, $staff_select);
						$staff_count = mysqli_num_rows($staff_ret);

						for ($i = 0; $i < $staff_count; $i++) {
							$rows = mysqli_fetch_array($staff_ret);
							$Staff_ID = $rows['Staff_ID'];

							echo "<tr>";
							echo "<td>" . $rows['Staff_ID'] . "</td>";
							echo "<td>" . $rows['Staff_Name'] . "</td>";
							echo "<td>" . $rows['Phone'] . "</td>";
							echo "<td>" . $rows['Address'] . "</td>";
							echo "<td>" . $rows['Email'] . "</td>";
							echo "<td>" . $rows['StaffType'] . "</td>";
							echo "<td>
									<a class='btn btn-warning btn-sm' href='Staff_Entry.php?Staff_ID=$Staff_ID'>Edit</a>
									<a class='btn btn-danger btn-sm' href='Staff_Delete.php?Staff_ID=$Staff_ID'>Delete</a>
								</td>";
							echo "</tr>";
						}

						?>
					</tbody>
				</table>
			</div>
			<div class="col-3 p-3">
				<form action="Staff_Entry.php" method="post">
					<h4 style="font-weight: 400;" class="mb-3">Enter Staff Information</h4>
					<label>Name</label>
					<input class="form-control" value="<?php echo $name ?>" type="text" name="txtStaffName" required />
					<label>Password</label>
					<input class="form-control" value="<?php echo $password ?>" type="password" name="txtPassword" required />
					<label>Phone</label>
					<input class="form-control" value="<?php echo $mobile ?>" type="text" name="txtPhone" required />
					<label>Email</label>
					<input class="form-control" value="<?php echo $email ?>" type="text" name="txtEmail" required />
					<label>Address</label>
					<textarea class="form-control" name="txtAddress"><?php echo $address ?></textarea>
					<label>Type</label>
					<select class="form-control" value="<?php echo $type ?>" name="txtStaffType">
						<option><?php echo $type ?></option>
						<option>---</option>
						<option>Operation</option>
						<option>Delivery</option>
						<option>Manager</option>
						<option>Customer service</option>
						<option>Finance</option>
						<option>HR</option>
					</select>
					<input type="hidden" name="txtStaffID" value="<?php echo $StaffID ?>" />
					<div class="mt-3 justify-content-end d-flex">
						<input class="btn btn-secondary mr-2" type="submit" value="Create" name="btnCreate" />
						<input class="btn btn-secondary mr-2" type="submit" value="Update" name="btnUpdate" />
						<input class="btn btn-secondary" type="reset" value="Clear" />
					</div>
				</form>
			</div>
		</div>
	</div>
	<script>
		$(document).ready(function() {
			$('#table').DataTable();

		});
	</script>
	<?php
	include('footer.php');
	?>
</body>

</html>