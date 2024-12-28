<?php
require_once "../../app/classes/VehicleManager.php";

// Initialize VehicleManager and get the vehicle ID from the query string
$vehicleManager = new VehicleManager('', '', '', '');
$id = $_GET['id'] ?? null;

// Redirect if no ID is provided
if ($id === null) {
    header("Location: ../index.php");
    exit;
}

// Retrieve the vehicle details
$vehicles = $vehicleManager->getVehicles();
$vehicle = $vehicles[$id] ?? null;

// Redirect if the vehicle does not exist
if (!$vehicle) {
    header("Location: ../index.php");
    exit;
}

// Handle the deletion if the form is submitted and confirmed
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if (isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
        $vehicleManager->deleteVehicle($id);
    }
    header("Location: ../index.php");
    exit;
}

include './header.php';
?>

<div class="container my-4">
    <h1>Delete Vehicle</h1>
    <p>Are you sure you want to delete <strong></strong>?</p>
    <form method="POST">
        <button type="submit" name="confirm" value="yes" class="btn btn-danger">Yes, Delete</button>
        <a href="../index.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

</body>
</html>
