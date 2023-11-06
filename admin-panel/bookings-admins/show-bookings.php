<?php require '../layouts/header.php'; ?>
<?php require '../../config/config.php'; ?>

<?php
if (!isset($_SESSION['adminname'])) {
  // If the admin is not logged in, redirect to the login page
  echo "<script>window.location.href = '../admins/login-admins.php';</script>";
  exit(); // Make sure to exit after a header redirect
}

try {
  // Query the database to select all hotels from the "hotel" table
  $bookingsQuery = $conn->prepare("SELECT * FROM bookings");
  $bookingsQuery->execute();

  // Fetch all admin records as an associative array
  $allBookings = $bookingsQuery->fetchAll(PDO::FETCH_OBJ);

  if (count($allBookings) === 0) {
    // Handle the case where no admin records were found
    echo "No hotels records found.";
  }
} catch (PDOException $e) {
  // Handle database query errors
  die("Connection failed: " . $e->getMessage());
}

?>

<!-- html part -->
<div class="container-fluid">

  <div class="row">
    <div class="col">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title mb-4 d-inline">Bookings</h5>

          <table class="table table-striped table-hover mt-auto">
            <thead>
              <tr>
                <th scope="col">Id</th>
                <th scope="col">check-in</th>
                <th scope="col">check-out</th>
                <th scope="col">Email</th>
                <th scope="col">Phone-number</th>
                <th scope="col">Full-name</th>
                <th scope="col">Hotel-name</th>
                <th scope="col">Room-name</th>
                <th scope="col">Status</th>
                <th scope="col">Payment</th>
                <th scope="col">Date-careate</th>
                <th scope="col">Delete</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($allBookings as $booking) : ?>

                <tr>
                  <th scope="row"><?php echo $booking->id; ?></th>
                  <td><?php echo $booking->check_in; ?></td>
                  <td><?php echo $booking->check_out; ?></td>
                  <td><?php echo $booking->email; ?></td>
                  <td><?php echo $booking->phone_number; ?></td>
                  <td><?php echo $booking->full_name; ?></td>
                  <td><?php echo $booking->hotel_name; ?></td>
                  <td><?php echo $booking->room_name; ?></td>
                  <td><?php echo $booking->status; ?></td>
                  <td>$<?php echo $booking->payment; ?></td>
                  <td><?php echo $booking->created_at; ?></td>

                  <td><a href="status-bookings.php?id=<?php echo $booking->id; ?>" class="btn btn-warning  text-center ">status</a></td>
                </tr>
              <?php endforeach; ?>

            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>



</div>
<?php require '../layouts/footer.php'; ?>