<?php
function json($data) {
    header('Content-Type: application/json');
    echo json_encode($data);
}
$con = mysqli_connect("localhost","root","","jajalan");
if (mysqli_connect_errno()){
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  exit();
}
if(isset($_GET['aksi']) && $_GET['aksi'] == 'cek'){
	$status = (int) $_GET['status'];
	$id = (int) $_GET['id'];
	if($status === 1) {
		$sts = (int) 0;
	} else {
		$sts = (int) 1;
	}
	$sql = "UPDATE makanan SET status = " . $sts . " WHERE id = " . $id;
	$result = $con->query($sql);
	
	if($result) {
		$sql = "SELECT * FROM makanan WHERE id = " . $id;
		$result = $con->query($sql);
		$row = $result->fetch_assoc();	
		json(array('data' => $row));
	}
} else {
	?>
	<!DOCTYPE html>
	<html>
	<head>
	<title>Simple Checkbox Realtime</title>
	<meta name="author" value="Tomo Hadi Sutomo" />
	<style>
		input[type=checkbox]:checked + label.strikethrough{
		text-decoration: line-through;
	}
	</style>
	</head>
	<html>
	<body style="background-color:lime;">
	<div style="width:200px; margin: 0px auto;padding:50px auto;margin-top:200px;">
	<?php
	$sql = "SELECT * FROM makanan";
	$result = $con->query($sql);
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			echo "<input id='m" . $row['id'] . "' type='checkbox' name='makanan' value='".$row['id']."' ";
			if($row['status']) {
				echo " disabled checked status='1' ";
			} else {
				echo " status='0' ";
			}
			echo " onclick='myFunction(this)' />";
			echo '<label class="strikethrough">' . $row['nama'] .'</label></br>';	
		}
	} else {
		echo "Tidak ada Data Makanan";
	}
	?>
	</div>
	<script>
	function myFunction(e) {
		var idw = e.value;
		var sts = e.getAttribute('status');
		//alert(sts);
		fetch('http://makanan.yes/index.php?aksi=cek&id='+idw+'&status='+sts)
			.then(function (response) {
			return response.json();
		}).then(function (out) {			
			if(out.data.status == 1) {
				//e.checked = true;
				e.disabled = true;
			} else {
				//e.checked = false;
				e.disabled = false;
			}
			
		}).catch(function (err) {
			console.log('Something went wrong!', err);
		});
	}
	</script>
	</body>
	</html>

	<?php	
}
$con->close();
?>

