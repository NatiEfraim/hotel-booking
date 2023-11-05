<?php require '../includes/header.php'; ?>
<?php require '../config/config.php'; ?>
<?php
// Check if the 'id' parameter is set in the URL
if (isset($_GET['id']) && isset($_SESSION['username'])) {
	// Get the room ID from the URL
	$id = $_GET['id'];

	try {
		// Define and execute a SQL query to select a room with "status" = 1 and the specified ID
		$roomQuery = $conn->prepare("SELECT * FROM rooms WHERE status = 1 AND id = :id");
		$roomQuery->bindParam(':id', $id, PDO::PARAM_INT); // Bind the ID as an integer
		$roomQuery->execute();

		// Fetch the single room as an object
		$singleRoom = $roomQuery->fetch(PDO::FETCH_OBJ);

		// Check if a room was found
		if ($singleRoom) {
			// Display room information

		} else {
			echo "No room found with the specified ID or status = 1.";
		}
		// grap all utilities the this room has
		$utilitiesQuery = $conn->query("SELECT * FROM utilities WHERE room_id='$id'");
		$utilitiesQuery->execute();
		$allUtilities = $utilitiesQuery->fetchAll(PDO::FETCH_OBJ);
		// Check if a utilities was found
		if ($allUtilities) {
			// Display room information

		} else {
			echo "No room found with the specified ID or status = 1.";
		}
	} catch (PDOException $e) {
		// Handle any database query errors
		die("Connection failed: " . $e->getMessage());
	}
	// Handle room reservation form submission
	if (isset($_POST['submit'])) {
		// Check if the form is submitted

		// Check if any of the required fields are empty
		if (empty($_POST['email']) || empty($_POST['phone_number']) || empty($_POST['full_name']) || empty($_POST['check_in']) || empty($_POST['check_out'])) {
			// Display an error message if any required fields are missing
			echo "<script>alert('Error! Please fill in all required fields')</script>";
		} else {
			// Retrieve form input data
			$check_in_date = date_create($_POST['check_in']); //convert to date
			$check_out_date = date_create($_POST['check_out']); //convert to date
			$check_in = $_POST['check_in']; //date as string
			$check_out = $_POST['check_out']; //date as string
			$email = $_POST['email'];
			$full_name = $_POST['full_name'];
			$phone_number = $_POST['phone_number'];
			// Retrieve data from the room query
			$room_name = $singleRoom->name;
			$room_id = $singleRoom->id;
			$hotel_name = $singleRoom->hotel_name;
			$hotel_id = $singleRoom->hotel_id;
			// calcullate the diffrence
			$days = date_diff($check_in_date, $check_out_date); //diffrence between 2 dates
			// for the pay.php - for pharchemnt
			$status = "Pending";
			// old price.
			// $payment = $singleRoom->price;
			// update price
			$payment = $singleRoom->price * intval($days->format('%R%a'));
			// Retrieve data from the session
			$user_id = $_SESSION['id'];
			$user_name = $_SESSION['username'];
			// grabing and save the price for each room*days
			// echo intval($days->format('%R%a'));
			$_SESSION['price'] = $singleRoom->price * intval($days->format('%R%a')); //convert to int.intval($days->format('%R%a')); //convert to int.
			// Convert the date strings to DateTime objects for comparison
			$check_in_date = new DateTime($check_in);
			$check_out_date = new DateTime($check_out);

			// Compare dates
			if ($check_out_date <= $check_in_date) {
				echo "<script>alert('Error! Check-out date should be after check-in date.')</script>";
			} else {
				// Insert data into the 'booking' table in the database
				try {
					// Prepare the INSERT statement
					$bookingInsert = $conn->prepare("INSERT INTO bookings (check_in, check_out, email, phone_number, full_name, hotel_name, room_name,status,payment, user_id )
			    VALUES (:check_in, :check_out, :email, :phone_number, :full_name, :hotel_name, :room_name,:status,:payment, :user_id)");

					// Execute the prepared statement with provided values
					$bookingInsert->execute([
						":check_in" => $check_in,
						":check_out" => $check_out,
						":email" => $email,
						":phone_number" => $phone_number,
						":full_name" => $full_name,
						":hotel_name" => $hotel_name,
						":room_name" => $room_name,
						":status" => $status,
						":payment" => $payment,
						":user_id" => $user_id,
					]);

					// Display a success message
					echo "<script>alert('Reservation successfully added')</script>";
					// redirrect to pay.php.
					echo "  <script>window.location.href = './pay.php';</script>";
				} catch (PDOException $e) {
					// Handle any database query errors
					die("Connection failed: " . $e->getMessage());
				}
			}
		}
	}
} else {
	// back to 404.php
	echo "  <script>window.location.href = '../404.php';</script>";
	// header("Location: ../index.php");
	exit(); // Make sure to exit after a header redirect
}

?>

<div class="hero-wrap js-fullheight" style="background-image: url('<?php echo APPURL; ?>images/<?php echo $singleRoom->image; ?> ');" data-stellar-background-ratio="0.5">
	<div class="overlay"></div>
	<div class="container">
		<div class="row no-gutters slider-text js-fullheight align-items-center justify-content-start" data-scrollax-parent="true">
			<div class="col-md-7 ftco-animate">
				<h2 class="subheading">Welcome to Vacation Rental</h2>
				<h1 class="mb-4"><?php echo $singleRoom->name; ?></h1>
				<!-- <p><a href="#" class="btn btn-primary">Learn more</a> <a href="#" class="btn btn-white">Contact us</a></p> -->
			</div>
		</div>
	</div>
</div>

<section class="ftco-section ftco-book ftco-no-pt ftco-no-pb">
	<div class="container">
		<div class="row justify-content-end">
			<div class="col-lg-4">
				<form action="./room-single.php?id=<?php echo $singleRoom->id; ?>" method="POST" class="appointment-form" style="margin-top: -568px;">
					<h3 class="mb-3">Book this room</h3>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<input type="text" name="email" class="form-control" placeholder="Email">
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<input type="text" name="full_name" class="form-control" placeholder="Full Name">
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<input type="text" name="phone_number" class="form-control" placeholder="Phone Number">
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<div class="input-wrap">
									<div class="icon"><span class="ion-md-calendar"></span></div>
									<input type="text" name="check_in" class="form-control appointment_date-check-in" placeholder="Check-In">
								</div>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<div class="icon"><span class="ion-md-calendar"></span></div>
								<input type="text" name="check_out" class="form-control appointment_date-check-out" placeholder="Check-Out">
							</div>
						</div>



						<div class="col-md-12">
							<div class="form-group">
								<input type="submit" name="submit" value="Book and Pay Now" class="btn btn-primary py-3 px-4">
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</section>






<section class="ftco-section bg-light">
	<div class="container">
		<div class="row no-gutters">
			<div class="col-md-6 wrap-about">
				<div class="img img-2 mb-4" style="background-image: url(<?php echo APPURL; ?>images/image_2.jpg);">
				</div>
				<h2>The most recommended vacation rental</h2>
				<p>A small river named Duden flows by their place and supplies it with the necessary regelialia. It is a paradisematic country, in which roasted parts of sentences fly into your mouth. Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life One day however a small line of blind text by the name of Lorem Ipsum decided to leave for the far World of Grammar.</p>
			</div>
			<div class="col-md-6 wrap-about ftco-animate">
				<div class="heading-section">
					<div class="pl-md-5">
						<h2 class="mb-2">What we offer</h2>
					</div>
				</div>
				<div class="pl-md-5">
					<p>A small river named Duden flows by their place and supplies it with the necessary regelialia. It is a paradisematic country, in which roasted parts of sentences fly into your mouth.</p>
					<div class="row">
						<?php foreach ($allUtilities as $utility) : ?>

							<div class="services-2 col-lg-6 d-flex w-100">
								<div class="icon d-flex justify-content-center align-items-center">
									<span class="<?php echo $utility->icon  ?>"></span>
								</div>
								<div class="media-body pl-3">
									<h3 class="heading"><?php echo $utility->name  ?></h3>
									<p><?php echo $utility->description  ?></p>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="ftco-intro" style="background-image: url(images/image_2.jpg);" data-stellar-background-ratio="0.5">
	<div class="overlay"></div>
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-9 text-center">
				<h2>Ready to get started</h2>
				<p class="mb-4">It’s safe to book online with us! Get your dream stay in clicks or drop us a line with your questions.</p>
				<p class="mb-0"><a href="#" class="btn btn-primary px-4 py-3">Learn More</a> <a href="#" class="btn btn-white px-4 py-3">Contact us</a></p>
			</div>
		</div>
	</div>
</section>

<?php require '../includes/footer.php'; ?>