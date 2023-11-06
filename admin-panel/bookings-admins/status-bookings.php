<?php require '../layouts/header.php'; ?>
<?php require '../../config/config.php'; ?>
<?php
if (!isset($_SESSION['adminname'])) {
    // If the admin is not logged in, redirect to the login page
    echo "<script>window.location.href = '../admins/login-admins.php';</script>";
    exit(); // Make sure to exit after a header redirect
}

// Handle form - get the id of the hotel
if (isset($_GET['id'])) {
    $id = $_GET['id']; // Grab the id of the hotel.

    if (isset($_POST['submit'])) {

        $status = $_POST['status']; // Grab the status.
        try {
            // Change the status data in the 'bookings' table
            $update = $conn->prepare("UPDATE bookings SET status = :status WHERE id = :id");
            $update->execute([
                ":status" => $status,
                ":id" => $id, // Bind the ID parameter here
            ]);

            // Hotel status updated successfully
            echo "<script>alert('The bookings status has been changed')</script>";
            // Redirect to the show-hotels page after successful update
            echo "<script>window.location.href = './show-bookings.php';</script>";
            exit();
        } catch (PDOException $e) {
            // Handle database update errors
            die("Connection failed: " . $e->getMessage());
        }
    }
}

?>

<!-- html part -->
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-5 d-inline">Update Status</h5>
                    <form method="POST" action="./status-bookings.php?id=<?php echo $id; ?>" enctype="multipart/form-data">
                        <!-- Email input -->
                        <select name="status" style="margin-top: 15px;" class="form-control">
                            <option>Choose Status</option>
                            <option value="Pending">Pending</option>
                            <option value="Confirmed">Confirmed</option>
                            <option value="Done">Done</option>
                        </select>


                        <!-- Submit button -->
                        <button style="margin-top: 10px;" type="submit" name="submit" class="btn btn-primary  mb-4 text-center">update</button>


                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
<?php require '../layouts/footer.php'; ?>