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
  $roomsQuery = $conn->prepare("SELECT * FROM rooms");
  $roomsQuery->execute();

  // Fetch all admin records as an associative array
  $allRooms = $roomsQuery->fetchAll(PDO::FETCH_OBJ);

  if (count($allRooms) === 0) {
    // Handle the case where no admin records were found
    echo "No rooms records found.";
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
          <h5 class="card-title mb-4 d-inline">Rooms</h5>
          <a href="./create-rooms.php" class="btn btn-primary mb-4 text-center float-right">Create Room</a>
          <table class="table table-striped table-hover mt-auto">
            <thead>
              <tr>
                <th scope="col">Id</th>
                <th scope="col">Name</th>
                <th scope="col">Price</th>
                <th scope="col">num-of-persons</th>
                <th scope="col">Size</th>
                <th scope="col">View</th>
                <th scope="col">Num-of-beds</th>
                <th scope="col">Hotel-name</th>
                <th scope="col">Status-value</th>
                <th scope="col">Change-status</th>
                <th scope="col">Delete</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($allRooms as $room) : ?>

                <tr>
                  <th scope="row"><?php echo $room->id; ?></th>
                  <td><?php echo $room->name; ?></td>
                  <td>$<?php echo $room->price; ?></td>
                  <td><?php echo $room->num_persons; ?></td>
                  <td><?php echo $room->size; ?> mt</td>
                  <td><?php echo $room->view; ?></td>
                  <td><?php echo $room->num_beds; ?></td>
                  <td><?php echo $room->hotel_name; ?></td>
                  <td><?php echo $room->status; ?></td>

                  <td><a href="./status-rooms.php?id=<?php echo $room->id; ?>" class="btn btn-warning text-center ">status</a></td>
                  <td><a href="./delete-rooms.php?id=<?php echo $room->id; ?>" class="btn btn-danger  text-center ">Delete</a></td>
                </tr>
              <?php endforeach; ?>
              <!-- 
              <tr>
                <th scope="row">1</th>
                <td>Suite Room</td>
                <td>image</td>
                <td>$100</td>
                <td>3</td>
                <td>30</td>
                <td>Sea View</td>
                <td>3</td>
                <td>Sheraton</td>
                <td>1</td>

                <td><a href="status.html" class="btn btn-danger  text-center ">status</a></td>
                <td><a href="delete-country.html" class="btn btn-danger  text-center ">Delete</a></td>
              </tr>
              <tr>
                <th scope="row">1</th>
                <td>Suite Room</td>
                <td>image</td>
                <td>$100</td>
                <td>3</td>
                <td>30</td>
                <td>Sea View</td>
                <td>3</td>
                <td>Sheraton</td>
                <td>1</td>
                <td><a href="status.html" class="btn btn-danger  text-center ">status</a></td>
                <td><a href="delete-country.html" class="btn btn-danger  text-center ">Delete</a></td>
              </tr> -->


            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>



</div>
<?php require '../layouts/footer.php'; ?>