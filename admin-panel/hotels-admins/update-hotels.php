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
  try {
    // pull out all data from 'hotels' table were id id equal to id.
    $hotelQuery = $conn->query("SELECT * FROM hotels WHERE id='$id'");
    $hotelQuery->execute();
    $hotelSingle = $hotelQuery->fetch(PDO::FETCH_OBJ);
  } catch (PDOException $e) {
    // Handle database update errors
    die("Connection failed: " . $e->getMessage());
  }
  // Handle with the form
  if (isset($_POST['submit'])) {

    if (empty($_POST['name']) || empty($_POST['description']) || empty($_POST['location'])) {
      // Display an error message if any required fields are missing
      echo "<script>alert('Error! Please fill in all required fields')</script>";
    } else {
      // Retrieve user input from the form
      $name = $_POST['name'];
      $description = $_POST['description'];
      $location = $_POST['location'];
      try {
        // Change the status data in the 'hotels' table
        $update = $conn->prepare("UPDATE hotels SET name = :name,description = :description,location=:location WHERE id = :id");
        $update->execute([
          ":name" => $name,
          ":description" => $description,
          ":location" => $location,
          ":id" => $id, // Bind the ID parameter here
        ]);

        // Hotel status updated successfully
        echo "<script>alert('The hotel changes has been changed')</script>";
        // Redirect to the show-hotels page after successful update
        echo "<script>window.location.href = './show-hotels.php';</script>";
        exit();
      } catch (PDOException $e) {
        // Handle database update errors
        die("Connection failed: " . $e->getMessage());
      }
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
          <h5 class="card-title mb-5 d-inline">Update Hotel</h5>
          <form method="POST" action="./update-hotels.php?id=<?php echo $id; ?>" enctype="multipart/form-data">
            <!-- Email input -->
            <div class="form-outline mb-4 mt-4">
              <input value="<?php echo $hotelSingle->name; ?>" type="text" name="name" id="form2Example1" class="form-control" placeholder="name" />

            </div>
            <div class="form-group">
              <label for="exampleFormControlTextarea1">Description</label>
              <textarea class="form-control" placeholder="description" name="description" id="exampleFormControlTextarea1" rows="3"><?php echo $hotelSingle->description; ?></textarea>
            </div>

            <div class="form-outline mb-4 mt-4">
              <label for="exampleFormControlTextarea1">Location</label>

              <input type="text" value="<?php echo $hotelSingle->location; ?>" placeholder="location" name="location" id="form2Example1" class="form-control" />

            </div>


            <!-- Submit button -->
            <button type="submit" name="submit" class="btn btn-primary  mb-4 text-center">update</button>


          </form>

        </div>
      </div>
    </div>
  </div>
</div>
<?php require '../layouts/footer.php'; ?>