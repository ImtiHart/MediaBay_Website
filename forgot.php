<?php require_once __DIR__ . '/includes/server.php';
// uses DB from config.php (included by server.php)

$message = '';
$reset_link = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Please enter a valid email.';
    } else {
        // find user
        $sql = "SELECT id, email FROM users WHERE email = ? LIMIT 1";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, 's', $email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            if (mysqli_stmt_num_rows($stmt) === 1) {
                mysqli_stmt_bind_result($stmt, $user_id, $user_email);
                mysqli_stmt_fetch($stmt);
                mysqli_stmt_close($stmt);

                // ensure password_resets table
                $create = "CREATE TABLE IF NOT EXISTS password_resets (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    user_id INT NOT NULL,
                    token VARCHAR(128) NOT NULL,
                    expires_at DATETIME NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
                mysqli_query($conn, $create);

                // generate token
                $token = bin2hex(random_bytes(16));
                $expires = date('Y-m-d H:i:s', time() + 3600); // 1 hour

                $ins = "INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, ?)";
                if ($istmt = mysqli_prepare($conn, $ins)) {
                    mysqli_stmt_bind_param($istmt, 'iss', $user_id, $token, $expires);
                    mysqli_stmt_execute($istmt);
                    mysqli_stmt_close($istmt);

                    $reset_link = sprintf('%s://%s%s/reset.php?token=%s',
                        isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http',
                        $_SERVER['HTTP_HOST'],
                        rtrim(dirname($_SERVER['PHP_SELF']), '/\\'),
                        $token
                    );

                    // attempt to email (may not work on local dev)
                    $subject = 'MediaBay Password Reset';
                    $body = "You requested a password reset. Use the link below (valid 1 hour):\n\n" . $reset_link;
                    $headers = 'From: no-reply@mediabay.co.za' . "\r\n";
                    @mail($user_email, $subject, $body, $headers);

                    $message = 'If that email exists in our system, a reset link was sent. For testing the link is shown below.';
                } else {
                    $message = 'Unable to create reset request.';
                }

            } else {
                // do not reveal existence
                $message = 'If that email exists in our system, a reset link was sent.';
                mysqli_stmt_close($stmt);
            }
        } else {
            $message = 'Database error.';
        }
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Forgot password — MediaBay</title>
  <link rel="stylesheet" href="assets/css/main.css">
  <style>.container{max-width:600px;margin:40px auto;padding:18px;background:#fff;border-radius:8px;}
  .notice{color:#333;margin-bottom:8px}</style>
</head>
<body>
  <div class="container">
    <a href="index.php">← Back</a>
    <h2>Forgot your password?</h2>
    <?php if(!empty($message)): ?><div class="notice"><?php echo htmlspecialchars($message); ?></div><?php endif; ?>
    <form method="post" action="forgot.php">
      <label>Email</label>
      <input type="email" name="email" required style="width:100%;padding:8px;margin:8px 0;" />
      <button type="submit" style="padding:8px 12px;background:#4CAF50;color:#fff;border:none;border-radius:6px;cursor:pointer">Send reset link</button>
    </form>

    <?php if(!empty($reset_link)): ?>
      <div style="margin-top:12px">Test reset link (visible for development):<br>
        <a href="<?php echo htmlspecialchars($reset_link); ?>"><?php echo htmlspecialchars($reset_link); ?></a>
      </div>
    <?php endif; ?>
  </div>
</body>
</html>
