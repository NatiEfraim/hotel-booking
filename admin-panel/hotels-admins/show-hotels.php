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
  $hotelsQuery = $conn->prepare("SELECT * FROM hotels");
  $hotelsQuery->execute();

  // Fetch all admin records as an associative array
  $allHotels = $hotelsQuery->fetchAll(PDO::FETCH_OBJ);

  if (count($allHotels) === 0) {
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
          <h5 class="card-title mb-4 d-inline">Hotels</h5>
          <a href="./create-hotels.php" class="btn btn-primary mb-4 text-center float-right">Create Hotels</a>
          <table class="table table-striped table-hover mt-auto">
            <thead>
              <tr>
                <th scope="col">Id</th>
                <th scope="col">Name</th>
                <th scope="col">Location</th>
                <th scope="col">Status-value</th>
                <th scope="col">Change-Status</th>
                <th scope="col">Update</th>
                <th scope="col">Status</th>
              </tr>
            </thead>
            <tbody>

              <?php foreach ($allHotels as $hotel) : ?>

                <tr>
                  <th scope="row"><?php echo $hotel->id; ?></th>
                  <td><?php echo $hotel->name; ?></td>
                  <td><?php echo $hotel->location; ?></td>
                  <td><?php echo $hotel->status; ?></td>

                  <td><a href="./status-hotels.php?id=<?php echo $hotel->id; ?>" class="btn btn-primary text-white text-center ">status</a></td>
                  <td><a href="./update-hotels.php?id=<?php echo $hotel->id; ?>" class="btn btn-warning text-white text-center ">Update </a></td>
                  <td><a href="./delete-hotels.php?id=<?php echo $hotel->id; ?>" class="btn btn-danger  text-center ">Delete </a></td>
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