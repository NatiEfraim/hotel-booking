<?php require '../layouts/header.php'; ?>
<?php require '../../config/config.php'; ?>
<?php
if (!isset($_SESSION['adminname'])) {
  // If the admin is not logged in, redirect to the login page
  echo "<script>window.location.href = '../admins/login-admins.php';</script>";
  exit();
}

if (isset($_POST['submit'])) {
  if (empty($_POST['name']) || empty($_POST['description']) || empty($_POST['location'])) {
    // Display an error message if any required fields are missing
    echo "<script>alert('Error! Please fill in all required fields')</script>";
  } else {
    // Retrieve user input from the form
    $name = $_POST['name'];
    $description = $_POST['description'];
    $location = $_POST['location'];
    $image = $_FILES['image']['name'];

    // Define the directory to upload images
    $uploadDir = "hotel-images/";

    try {
      // Insert the hotel data into the 'hotels' table in the database
      $insert = $conn->prepare("INSERT INTO hotels (name, description, location, image) VALUES (:name, :description, :location, :image)");
      $insert->execute([
        ":name" => $name,
        ":description" => $description,
        ":location" => $location,
        ":image" => $image,
      ]);

      if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $image)) {
        // Image uploaded successfully
        echo "<script>alert('The hotel has been added to the database')</script>";
        // Redirect to the show-hotels page after successful creation of a hotel
        echo "<script>window.location.href = './show-hotels.php';</script>";
        exit();
      } else {
        echo "<script>alert('Failed to upload image')</script>";
      }
    } catch (PDOException $e) {
      // Handle database insert errors
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
          <h5 class="card-title mb-5 d-inline">Create Hotels</h5>
          <form method="POST" action="./create-hotels.php" enctype="multipart/form-data">
            <!-- Email input -->
            <div class="form-outline mb-4 mt-4">
              <input type="text" name="name" id="form2Example1" class="form-control" placeholder="name" />

            </div>

            <div class="form-outline mb-4 mt-4">
              <input type="file" name="image" id="form2Example1" class="form-control" />

            </div>

            <div class="form-group">
              <label for="exampleFormControlTextarea1">Description</label>
              <textarea class="form-control" name="description" id="exampleFormControlTextarea1" rows="3"></textarea>
            </div>

            <div class="form-outline mb-4 mt-4">
              <label for="exampleFormControlTextarea1">Location</label>

              <input type="text" name="location" id="form2Example1" class="form-control" />

            </div>


            <!-- Submit button -->
            <button type="submit" name="submit" class="btn btn-primary  mb-4 text-center">create</button>


          </form>

        </div>
      </div>
    </div>
  </div>
</div>
<?php require '../layouts/footer.php'; ?>