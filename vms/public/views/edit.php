<?php

require_once "../../app/classes/VehicleManager.php";

// Initialize VehicleManager and get vehicle ID
$vehicleManager = new VehicleManager('', '', '', '');
$id = $_GET['id'] ?? null;

// Redirect if no ID is provided or vehicle doesn't exist
if ($id === null || !($vehicle = $vehicleManager->getVehicles()[$id] ?? null)) {
    header("Location: ../index.php");
    exit;
}

// Update vehicle details if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $vehicleManager->editVehicle($id, [
        'name' => $_POST['name'],
        'type' => $_POST['type'],
        'price' => $_POST['price'],
        'image' => $_POST['image']
    ]);
    header("Location: ../index.php");
    exit;
}

include './header.php';
?>

<div class="container my-4">
    <h1>Edit Vehicle</h1>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Vehicle Name</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($vehicle['name']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Vehicle Type</label>
            <input type="text" name="type" class="form-control" value="<?= htmlspecialchars($vehicle['type']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Price</label>
            <input type="number" name="price" class="form-control" value="<?= htmlspecialchars($vehicle['price']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Image URL</label>
            <input type="text" name="image" class="form-control" value="<?= htmlspecialchars($vehicle['image']) ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Vehicle</button>
        <a href="../index.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

</body>
</html>
