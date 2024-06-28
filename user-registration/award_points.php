<?php
// Database connection details (same as before)
$servername = "localhost";
$username = "root"; // Your MySQL username
$password = "";     // Your MySQL password
$dbname = "usersdb";

// Establish connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get current time
$current_time = time();

// Calculate 24 hours ago
$last_24_hours = $current_time - (24 * 60 * 60); // 24 hours in seconds

// SQL query to select users who are eligible to earn points
$sql = "SELECT id, username, last_points_earned FROM users WHERE UNIX_TIMESTAMP(last_points_earned) <= $last_24_hours";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Users found who are eligible to earn points
    while ($row = $result->fetch_assoc()) {
        $user_id = $row['id'];
        $username = $row['username'];

        // Update last_points_earned to current time
        $update_sql = "UPDATE users SET last_points_earned = CURRENT_TIMESTAMP WHERE id = $user_id";
        $conn->query($update_sql);

        // Award 10 points (or update points in your users table)
        // Example: If you have a 'points' column in your users table, update it like this:
        $award_points_sql = "UPDATE users SET points = points + 10 WHERE id = $user_id";
        $conn->query($award_points_sql);

        echo "Awarded 10 points to user: $username<br>";
    }
} else {
    echo "No users eligible to earn points.";
}

// Close database connection
$conn->close();
?>
