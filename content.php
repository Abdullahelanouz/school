<?php
// Database connection
include 'db_connection.php';

// Get the topic from the URL
$topic = isset($_GET['topic']) ? $_GET['topic'] : '';

// Prepare and execute the query
$stmt = $conn->prepare("SELECT * FROM content WHERE topic = ?");
$stmt->bind_param('s', $topic);
$stmt->execute();
$result = $stmt->get_result();

// Check if any content was found
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<h1>' . htmlspecialchars($row['topic']) . '</h1>';
        echo '<div style="white-space: pre-wrap; word-wrap: break-word;">' . $row['content'] . '</div>'; // Assuming 'content' is the field name
    }
} else {
    echo '<p>No content found for this topic.</p>';
}

// Close the connection
$stmt->close();
$conn->close();
?>
