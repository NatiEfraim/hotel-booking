<?php
require '../layouts/header.php';
require '../../config/config.php';

if (!isset($_SESSION['adminname'])) {
    // If the admin is not logged in, redirect to the login page
    echo "<script>window.location.href = '../admins/login-admins.php';</script>";
    exit(); // Make sure to exit after a header redirect
}

// Handle form - get the id of the hotel
if (isset($_GET['id'])) {
    $id = $_GET['id']; // Grab the id of the hotel.

    try {
        // Fetch the image data from the 'rooms' table
        $getImage = $conn->prepare("SELECT * FROM rooms WHERE id = :id");
        $getImage->bindValue(':id', $id, PDO::PARAM_INT);
        $getImage->execute();
        $fetch = $getImage->fetch(PDO::FETCH_OBJ);

        // Check if an image is found
        if ($fetch) {
            $imageFilePath = "room-images/" . $fetch->image;

            // Check if the image file exists and then remove it
            if (file_exists($imageFilePath)) {
                unlink($imageFilePath); // Remove the image file from the folder.
            }

            // Delete the hotel data from the 'hotels' table
            $deleteQuery = $conn->prepare("DELETE FROM rooms WHERE id = :id");
            $deleteQuery->bindValue(':id', $id, PDO::PARAM_INT);
            $deleteQuery->execute();

            // Hotel deleted successfully
            echo "<script>alert('The room has been deleted')</script>";
            // Redirect to the show-hotels page after successful deletion
            echo "<script>window.location.href = './show-rooms.php';</script>";
            exit();
        } else {
            // Handle the case where no hotel with the specified ID is found
            echo "<script>alert('Room not found')</script>";
            echo "<script>window.location.href = './show-rooms.php';</script>";
            exit();
        }
    } catch (PDOException $e) {
        // Handle database update errors
        die("Connection failed: " . $e->getMessage());
    }
}
