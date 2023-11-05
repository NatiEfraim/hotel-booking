<?php
require '../config/config.php';
if (isset($_SESSION['username'])) {
  // The user is already logged in; redirect to the index page
  echo "  <script>window.location.href = '../index.php';</script>";
  // header("Location: ../index.php");
  exit(); // Make sure to exit after a header redirect
}

if (isset($_POST['submit']) && !isset($_SESSION['username'])) {
  // Check if the form is submitted

  // Check if any of the required fields are empty
  if (empty($_POST['username']) || empty($_POST['email']) || empty($_POST['password'])) {
    // Display an error message if any required fields are missing
    echo "<script>alert('Error! Please fill in all required fields')</script>";

    // echo "<div class='alert alert-danger'>
    //       <strong>Error!</strong> Please fill in all required fields.
    //   </div>";
  } else {
    // Retrieve user input from the form
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
      // Insert the user data into the 'users' table in the database
      $insert = $conn->prepare("INSERT INTO users (username, email, mypassword) VALUES (:username, :email, :mypassword)");
      $insert->execute([
        ":username" => $username,
        ":email" => $email,
        ":mypassword" => $password,
      ]);
      // print secusses messge
      echo "<script>alert('The user has been added to database')</script>";
      // echo "<div class='alert alert-success'>
      // <strong>Success!</strong> user has been added to database.
      // </div>";
      // Redirect to the login page after successful registration
      header("Location: login.php");
      exit(); // Make sure to exit after a header redirect

    } catch (PDOException $e) {
      // Display an error message if any required fields are missing
      echo "<div class='alert alert-danger'>
            <strong>Error!</strong> Somting goes wrong.
            </div>";
      // Handle database insert errors
      die("Connection failed: " . $e->getMessage());
    }
  }
}
// else {
//   // The user is already logged in; redirect to the index page
//   echo "  <script>window.location.href = '../index.php';</script>";
//   // header("Location: ../index.php");
//   exit(); // Make sure to exit after a header redirect
// }












require '../includes/header.php';
?>

<!-- html part -->
<div class="hero-wrap js-fullheight" style="background-image: url('<?php echo APPURL; ?>images/image_2.jpg');" data-stellar-background-ratio="0.5">
  <div class="overlay"></div>
  <div class="container">
    <div class="row no-gutters slider-text js-fullheight align-items-center justify-content-start" data-scrollax-parent="true">
      <div class="col-md-7 ftco-animate">
        <!-- <h2 class="subheading">Welcome to Vacation Rental</h2>
          	<h1 class="mb-4">Rent an appartment for your vacation</h1>
            <p><a href="#" class="btn btn-primary">Learn more</a> <a href="#" class="btn btn-white">Contact us</a></p> -->
      </div>
    </div>
  </div>
</div>
<section class="ftco-section ftco-book ftco-no-pt ftco-no-pb">
  <div class="container">
    <div class="row justify-content-middle" style="margin-left: 397px;">
      <div class="col-md-6 mt-5">
        <form action="./register.php" method="POST" class="appointment-form" style="margin-top: -568px;">
          <h3 class="mb-3">Register</h3>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <input type="text" name="username" class="form-control" placeholder="Username">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <input type="text" name="email" class="form-control" placeholder="Email">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <input type="password" name="password" class="form-control" placeholder="Password">
              </div>
            </div>



            <div class="col-md-12">
              <div class="form-group">
                <input type="submit" name="submit" value="Register" class="btn btn-primary py-3 px-4">
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>
<?php require '../includes/footer.php'; ?>