<?php
require '../config/config.php';
require '../includes/header.php';
if (isset($_SESSION['username'])) {
  // The user is already logged in; redirect to the index page
  echo "  <script>window.location.href = '../index.php';</script>";
  // header("Location: ../index.php");
  exit(); // Make sure to exit after a header redirect
}
if (isset($_POST['submit']) && !isset($_SESSION['username'])) {
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
      // Query the database to fetch user data based on the provided email
      $login = $conn->prepare("SELECT * FROM users WHERE email = :email");
      $login->execute([
        ":email" => $email,
      ]);
      $user = $login->fetch(PDO::FETCH_ASSOC);

      if (!$user) {
        // No user found with the provided email
        echo "<script>alert('Error! Wrong email or password')</script>";
      } else {
        // Verify the password
        if (password_verify($password, $user['mypassword'])) {
          // Password is correct, user is authenticated

          // Start a session and set session variables
          $_SESSION['username'] = $user['username'];
          $_SESSION['id'] = $user['id'];
          $_SESSION['email'] = $user['email'];
          // echo $_SESSION['email'];
          // header("Location: ../index.php");
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
// else {
//   // The user is already logged in; redirect to the index page
//   echo "  <script>window.location.href = '../index.php';</script>";
//   // header("Location: ../index.php");
//   exit(); // Make sure to exit after a header redirect
// }


?>
<!-- Your HTML form for login goes here -->




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
        <form action="./login.php" method="POST" class="appointment-form" style="margin-top: -568px;">
          <h3 class="mb-3">Login</h3>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <input type="text" class="form-control" name="email" placeholder="Email">
              </div>
            </div>

            <div class="col-md-12">
              <div class="form-group">
                <input type="password" name="password" class="form-control" placeholder="Password">
              </div>
            </div>



            <div class="col-md-12">
              <div class="form-group">
                <input type="submit" name="submit" value="Login" class="btn btn-primary py-3 px-4">
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>
<?php require '../includes/footer.php'; ?>