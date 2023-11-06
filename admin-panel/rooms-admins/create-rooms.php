<?php
require '../layouts/header.php';
require '../../config/config.php';

if (!isset($_SESSION['adminname'])) {
  // If the admin is not logged in, redirect to the login page
  echo "<script>window.location.href = '../admins/login-admins.php';</script>";
  exit(); // Make sure to exit after a header redirect
}

// Query the database to select all hotels from the "hotels" table
$hotelsQuery = $conn->prepare("SELECT * FROM hotels");
$hotelsQuery->execute();

// Fetch all hotels as an associative array
$allHotels = $hotelsQuery->fetchAll(PDO::FETCH_OBJ);

// Handle the form
if (isset($_POST['submit'])) {
  if (
    empty($_POST['name']) || empty($_POST['price'])
    || empty($_POST['num_persons']) || empty($_POST['num_beds'])
  ) {
    // Display an error message if any required fields are missing
    echo "<script>alert('Error! Please fill in all required fields')</script>";
  } else {
    // Retrieve user input from the form
    $name = $_POST['name'];
    $price = $_POST['price'];
    $num_persons = $_POST['num_persons'];
    $num_beds = $_POST['num_beds'];
    $size = $_POST['size'];
    $view = $_POST['view'];
    $hotel_name = $_POST['hotel_name'];
    $hotel_id = $_POST['hotel_id'];
    $image = $_FILES['image']['name'];

    // Define the directory to upload images
    $uploadDir = "room-images/";

    try {
      // Insert the room data into the 'rooms' table in the database
      $insert = $conn->prepare("INSERT INTO rooms (name, image, price, num_persons, size, view, num_beds, hotel_id, hotel_name) VALUES (:name, :image, :price, :num_persons, :size, :view, :num_beds, :hotel_id, :hotel_name)");
      $insert->execute([
        ":name" => $name,
        ":image" => $image,
        ":price" => $price,
        ":num_persons" => $num_persons,
        ":size" => $size,
        ":view" => $view,
        ":num_beds" => $num_beds,
        ":hotel_id" => $hotel_id,
        ":hotel_name" => $hotel_name,
      ]);

      if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $image)) {
        // Image uploaded successfully
        echo "<script>alert('The room has been added to the database')</script>";
        // Redirect to the show-rooms page after successful creation of a room
        echo "<script>window.location.href = './show-rooms.php';</script>";
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
          <h5 class="card-title mb-5 d-inline">Create Rooms</h5>
          <form method="POST" action="" enctype="multipart/form-data">
            <!-- Email input -->
            <div class="form-outline mb-4 mt-4">
              <input type="text" name="name" id="form2Example1" class="form-control" placeholder="name" />

            </div>
            <div class="form-outline mb-4 mt-4">
              <input type="file" name="image" id="form2Example1" class="form-control" />

            </div>
            <div class="form-outline mb-4 mt-4">
              <input type="text" name="price" id="form2Example1" class="form-control" placeholder="price" />

            </div>
            <div class="form-outline mb-4 mt-4">
              <input type="text" name="num_persons" id="form2Example1" class="form-control" placeholder="num_persons" />

            </div>
            <div class="form-outline mb-4 mt-4">
              <input type="text" name="num_beds" id="form2Example1" class="form-control" placeholder="num_beds" />

            </div>
            <div class="form-outline mb-4 mt-4">
              <input type="text" name="size" id="form2Example1" class="form-control" placeholder="size" />

            </div>
            <div class="form-outline mb-4 mt-4">
              <input type="text" name="view" id="form2Example1" class="form-control" placeholder="view" />

            </div>
            <select name="hotel_name" class="form-control">
              <option>Choose Hotel Name</option>

              <?php foreach ($allHotels as $hotel) : ?>

                <option value="<?php echo $hotel->name ?>"><?php echo $hotel->name ?></option>

                <!-- <option>The Plaza Hotel</option>
            
              <option>The Ritz</option> -->
              <?php endforeach; ?>

            </select>
            <br>

            <select name="hotel_id" class="form-control">
              <option>Choose Same Hotel Once Again</option>
              <?php foreach ($allHotels as $hotel) : ?>

                <option value="<?php echo $hotel->id; ?>"><?php echo $hotel->name; ?></option>
              <?php endforeach; ?>

              <!-- <option>The Plaza Hotel</option>
              <option>The Ritz</option> -->
            </select>
            <br>

            <!-- Submit button -->
            <button type="submit" name="submit" class="btn btn-primary  mb-4 text-center">create</button>


          </form>

        </div>
      </div>
    </div>
  </div>
</div>

<?php require '../layouts/footer.php'; ?>