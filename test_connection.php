<?php
// Test database connection and show existing users
require_once "config.php";

echo "<h2>Database Connection Test</h2>";

// Test connection
if($link) {
    echo "<p style='color: green;'>✅ Database connection successful!</p>";
    echo "<p><strong>Database:</strong> " . DB_NAME . "</p>";
    echo "<p><strong>Host:</strong> " . DB_SERVER . "</p>";
    
    // Check if users table exists
    $result = mysqli_query($link, "SHOW TABLES LIKE 'users'");
    if(mysqli_num_rows($result) > 0) {
        echo "<p style='color: green;'>✅ Users table exists!</p>";
        
        // Show existing users (without passwords)
        $sql = "SELECT id, username, email, created_at FROM users ORDER BY id";
        $result = mysqli_query($link, $sql);
        
        if($result && mysqli_num_rows($result) > 0) {
            echo "<h3>Existing Users:</h3>";
            echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
            echo "<tr><th>ID</th><th>Username</th><th>Email</th><th>Created</th></tr>";
            
            while($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                echo "<td>" . $row['created_at'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p style='color: orange;'>⚠️ No users found in the database.</p>";
        }
    } else {
        echo "<p style='color: red;'>❌ Users table does not exist!</p>";
    }
} else {
    echo "<p style='color: red;'>❌ Database connection failed!</p>";
    echo "<p>Error: " . mysqli_connect_error() . "</p>";
}

echo "<hr>";
echo "<h3>Test Login Credentials:</h3>";
echo "<p>You can test the login system with any of these existing accounts:</p>";
echo "<ul>";
echo "<li><strong>Username:</strong> imtihart, <strong>Email:</strong> chick@gmail.com</li>";
echo "<li><strong>Username:</strong> chip, <strong>Email:</strong> chip@gmail.com</li>";
echo "<li><strong>Username:</strong> champ, <strong>Email:</strong> champ@gmail.com</li>";
echo "<li><strong>Username:</strong> zeus, <strong>Email:</strong> zeus@gmail.com</li>";
echo "<li><strong>Username:</strong> nandos, <strong>Email:</strong> nandos@gmail.com</li>";
echo "</ul>";
echo "<p><em>Note: You'll need to know the passwords for these accounts to test login.</em></p>";

echo "<hr>";
echo "<h3>Next Steps:</h3>";
echo "<ol>";
echo "<li>Go to <a href='index.php'>index.php</a> to test the login system</li>";
echo "<li>Try registering a new account</li>";
echo "<li>Try logging in with existing accounts</li>";
echo "</ol>";
?>
