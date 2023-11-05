<?php
// Include the header and configuration files
require '../layouts/header.php';
require '../../config/config.php';

if (!isset($_SESSION['adminname'])) {
  // If the admin is not logged in, redirect to the login page
  echo "<script>window.location.href = './admins/login-admins.php';</script>";
  exit(); // Make sure to exit after a header redirect
}

// Handle the form 
if (isset($_POST['submit'])) {
  // Check if the form is submitted

  // Check if any of the required fields are empty
  if (empty($_POST['adminname']) || empty($_POST['email']) || empty($_POST['password'])) {
    // Display an error message if any required fields are missing
    echo "<script>alert('Error! Please fill in all required fields')</script>";
  } else {
    // Retrieve user input from the form
    $adminname = $_POST['adminname'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
      // Insert the admin data into the 'admins' table in the database
      $insert = $conn->prepare("INSERT INTO admins (adminname, email, mypassword) VALUES (:adminname, :email, :mypassword)");
      $insert->execute([
        ":adminname" => $adminname,
        ":email" => $email,
        ":mypassword" => $password,
      ]);
      // print secusses messge
      echo "<script>alert('The user has been added to database')</script>";
      // Redirect to the login page after successful registration
      echo "<script>window.location.href = '../index.php';</script>";
      exit(); // Make sure to exit after a header redirect

    } catch (PDOException $e) {
      // Handle database insert errors
      die("Connection failed: " . $e->getMessage());
    }
  }
}
?>

<div class="container-fluid">
  <div class="row">
    <div class="col">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title mb-5 d-inline">Create Admins</h5>
          <form method="POST" action="./create-admins.php" enctype="multipart/form-data">
            <!-- Email input -->
            <div class="form-outline mb-4 mt-4">
              <input type="email" name="email" id="form2Example1" class="form-control" placeholder="email" />

            </div>

            <div class="form-outline mb-4">
              <input type="text" name="adminname" id="form2Example1" class="form-control" placeholder="adminname" />
            </div>
            <div class="form-outline mb-4">
              <input type="password" name="password" id="form2Example1" class="form-control" placeholder="password" />
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