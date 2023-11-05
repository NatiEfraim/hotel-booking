<?php require './layouts/header.php'; ?>
<?php require '../config/config.php'; ?>
<?php
if (!isset($_SESSION['adminname'])) {
  // The admin is already logged in; redirect to the index page
  echo "<script>window.location.href = './admins/login-admins.php';</script>";
  exit(); // Make sure to exit after a header redirect
}

// Hotel count
$hotels = $conn->query("SELECT COUNT(*) AS count_hotels FROM hotels");
$hotels->execute();
$allHotels = $hotels->fetch(PDO::FETCH_OBJ);
// Rooms count
$rooms = $conn->query("SELECT COUNT(*) AS count_rooms FROM rooms");
$rooms->execute();
$allRooms = $rooms->fetch(PDO::FETCH_OBJ);
// Admins count
$admins = $conn->query("SELECT COUNT(*) AS count_admins FROM admins");
$admins->execute();
$allAdmins = $admins->fetch(PDO::FETCH_OBJ);
// Bookings count
$bookings = $conn->query("SELECT COUNT(*) AS count_bookings FROM bookings");
$bookings->execute();
$allBookings = $bookings->fetch(PDO::FETCH_OBJ);
?>
<div class="container-fluid">

  <div class="row">
    <!-- part of the hotels -->
    <div class="col-md-3">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Hotels</h5>
          <!-- <h6 class="card-subtitle mb-2 text-muted">Bootstrap 4.0.0 Snippet by pradeep330</h6> -->
          <p class="card-text">number of hotels: <?php echo $allHotels->count_hotels; ?></p>

        </div>
      </div>
    </div>
    <!-- part of the rooms -->
    <div class="col-md-3">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Rooms</h5>

          <p class="card-text">number of rooms: <?php echo $allRooms->count_rooms; ?></p>

        </div>
      </div>
    </div>

    <!-- part of the admins -->
    <div class="col-md-3">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Admins</h5>

          <p class="card-text">number of admins: <?php echo $allAdmins->count_admins; ?></p>

        </div>
      </div>
    </div>
    <!-- part of the bookings -->
    <div class="col-md-3">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Bookings</h5>

          <p class="card-text">number of bookings: <?php echo $allBookings->count_bookings; ?></p>

        </div>
      </div>
    </div>

  </div>

</div>
<?php require './layouts/footer.php'; ?>