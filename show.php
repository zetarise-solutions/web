<?php
// include 'includes/db.php';

// $sql = 'select * from subscribers order by id desc limit 1';
// $result = $conn->query($sql);

// while ($row = $result->fetch_assoc()) {
//     echo '<div class="subscriber">';
//     echo '<p>Name: ' . htmlspecialchars($row['name']) . '</p>';
//     echo '<p>Email: ' . htmlspecialchars($row['email']) . '</p>';
//     echo '<p>Subscribed on: ' . htmlspecialchars($row['created_at']) . '</p>';
//     echo '</div>';
// }
// $conn->close();

// Generating hash for password 'admin123'
echo password_hash('admin123', PASSWORD_DEFAULT);

?>