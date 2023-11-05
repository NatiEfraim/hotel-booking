<?php require "../includes/header.php"; ?>
<?php require "../config/config.php"; ?>
<?php

// cheack the id from the url - and the username
if (isset($_GET['id']) && isset($_SESSION['username'])) {
    // Get the user ID from the URL
    $id = $_GET['id'];
    if ($_SESSION['id'] != $id) {
        // Wrong user-id
        echo "<script>alert('You can not be here')</script>";
        echo "<script>window.location.href = '../index.php';</script>";
        exit();
    }
    try {
        // Define and execute a SQL query to select rooms with the specified hotel_id
        $bookingsQuery = $conn->prepare("SELECT * FROM bookings WHERE user_id = :id");
        $bookingsQuery->bindValue(':id', $id, PDO::PARAM_INT); // Bind the ID as an integer
        $bookingsQuery->execute();

        // Fetch all rooms as an array of objects
        $allBookings = $bookingsQuery->fetchAll(PDO::FETCH_OBJ);

        // Check if any bookings were found
        // if (empty($allBookings)) {
        //     // No rooms have been found with the specified hotel ID
        //     // echo "<script>alert('No bookings have been found!!!')</script>";
        //     // echo "<script>window.location.href = '../index.php';</script>";

        //     // exit();
        // }
    } catch (PDOException $e) {
        // Handle any database query errors
        die("Connection failed: " . $e->getMessage());
    }
} else {
    // No ID provided in the URL
    echo "<script>window.location.href = '../404.php';</script>";
    exit();
}
?>

<!-- start html -->
<div class="container">
    <?php if (empty($allBookings)) : ?>
        <!-- there is no bookings for this user-id -->
        <div class="alert alert-danger" role="alert">
            There is no bookings to show up!.
        </div>
    <?php else : ?>

        <table class="table table-striped table-hover mt-auto">
            <thead>
                <tr>
                    <!-- <th scope="col">#</th> -->
                    <th scope="col">check-in</th>
                    <th scope="col">check-out</th>
                    <th scope="col">Email</th>
                    <th scope="col">phone-number</th>
                    <th scope="col">full-name</th>
                    <th scope="col">hotel-name</th>
                    <th scope="col">room-name</th>
                    <th scope="col">status</th>
                    <th scope="col">payment</th>
                    <th scope="col">created-at</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($allBookings as $booking) : ?>

                    <tr>
                        <!-- <th scope="row">1</th> -->
                        <td><?php echo $booking->check_in ?></td>
                        <td><?php echo $booking->check_out ?></td>
                        <td><?php echo $booking->email ?></td>
                        <td><?php echo $booking->phone_number ?></td>
                        <td><?php echo $booking->full_name ?></td>
                        <td><?php echo $booking->hotel_name ?></td>
                        <td><?php echo $booking->room_name ?></td>
                        <td><?php echo $booking->status ?></td>
                        <td><?php echo $booking->payment ?></td>
                        <td><?php echo $booking->created_at ?></td>
                    </tr>
                <?php endforeach; ?>

            </tbody>
        </table>
    <?php endif; ?>

</div>



<?php require "../includes/footer.php"; ?>