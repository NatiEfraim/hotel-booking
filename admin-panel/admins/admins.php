<?php
// Include the header and configuration files
require '../layouts/header.php';
require '../../config/config.php';

if (!isset($_SESSION['adminname'])) {
  // If the admin is not logged in, redirect to the login page
  echo "<script>window.location.href = './admins/login-admins.php';</script>";
  exit(); // Make sure to exit after a header redirect
}

try {
  // Query the database to select all admins from the "admins" table
  $adminQuery = $conn->prepare("SELECT * FROM admins");
  $adminQuery->execute();

  // Fetch all admin records as an associative array
  $allAdmins = $adminQuery->fetchAll(PDO::FETCH_OBJ);

  if (count($allAdmins) === 0) {
    // Handle the case where no admin records were found
    echo "No admin records found.";
  }
} catch (PDOException $e) {
  // Handle database query errors
  die("Connection failed: " . $e->getMessage());
}

?>
<div class="container-fluid">

  <div class="row">
    <div class="col">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title mb-4 d-inline">Admins</h5>
          <a href="./create-admins.php" class="btn btn-primary mb-4 text-center float-right">Create Admins</a>
          <table class="table table-striped table-hover mt-auto">
            <thead>
              <tr>
                <th scope="row">#</th>
                <th scope="col">Admin-Name</th>
                <th scope="col">Email</th>
                <th scope="col">Date-created</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($allAdmins as $admin) : ?>
                <tr>
                  <th scope="row"><?php echo $admin->id; ?></th>
                  <td><?php echo $admin->adminname; ?></td>
                  <td><?php echo $admin->email; ?></td>
                  <td><?php echo $admin->created_at; ?></td>

                </tr>
                <!-- 
              <tr>
                <th scope="row">2</th>
                <td>Jacob</td>
                <td>Thornton</td>

              </tr>
              <tr>
                <th scope="row">3</th>
                <td>Larry</td>
                <td>the Bird</td>

              </tr> -->
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>



</div>
<?php require '../layouts/footer.php'; ?>