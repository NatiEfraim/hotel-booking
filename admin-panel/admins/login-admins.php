<?php require '../layouts/header.php';  ?>
<?php require '../../config/config.php';  ?>
<?php

if (isset($_SESSION['adminname'])) {
  // The admin is already logged in; redirect to the index page
  echo "<script>window.location.href = '../index.php';</script>";
  exit(); // Make sure to exit after a header redirect
}

if (isset($_POST['submit'])) {
  // Check if the form is submitted

  // Check if any of the required fields are empty
  if (empty($_POST['email']) || empty($_POST['password'])) {
    // Display an error message if any required fields are missing
    echo "<script>alert('Error! Please fill in all required fields')</script>";
  } else {
    // Retrieve user input from the form
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
      // Query the database to fetch admin data based on the provided email
      $login = $conn->prepare("SELECT * FROM admins WHERE email = :email");
      $login->execute([
        ":email" => $email,
      ]);
      $admin = $login->fetch(PDO::FETCH_ASSOC);

      if (!$admin) {
        // No admin found with the provided email
        echo "<script>alert('Error! Wrong email or password')</script>";
      } else {
        // Verify the password
        if (password_verify($password, $admin['mypassword'])) {
          // Password is correct, user is authenticated
          echo "<script>alert('You are loggedin!!!')</script>";

          // Start a session and set session variables
          $_SESSION['adminname'] = $admin['adminname'];
          $_SESSION['id'] = $admin['id'];
          $_SESSION['email'] = $admin['email'];
          // back index.php
          echo "  <script>window.location.href = '../index.php';</script>";

          exit();
        } else {
          // Password is incorrect
          echo "<script>alert('Error! Wrong email or password.')</script>";
        }
      }
    } catch (PDOException $e) {
      // Handle database query errors
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
          <h5 class="card-title mt-5">Login</h5>
          <form method="POST" class="p-auto" action="./login-admins.php">
            <!-- Email input -->
            <div class="form-outline mb-4">
              <input type="email" name="email" id="form2Example1" class="form-control" placeholder="Email" />

            </div>


            <!-- Password input -->
            <div class="form-outline mb-4">
              <input type="password" name="password" id="form2Example2" placeholder="Password" class="form-control" />

            </div>



            <!-- Submit button -->
            <button type="submit" name="submit" class="btn btn-primary  mb-4 text-center">Login</button>


          </form>

        </div>
      </div>
    </div>
  </div>
</div>