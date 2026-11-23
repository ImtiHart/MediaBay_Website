<?php require_once __DIR__ . '/includes/server.php';

$message = '';
$token = $_GET['token'] ?? '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if (empty($password) || strlen($password) < 6) {
        $message = 'Password must be at least 6 characters.';
    } elseif ($password !== $confirm) {
        $message = 'Passwords do not match.';
    } else {
        // lookup token
        $sql = "SELECT pr.id, pr.user_id, pr.expires_at, u.email FROM password_resets pr JOIN users u ON u.id = pr.user_id WHERE pr.token = ? LIMIT 1";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, 's', $token);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            if (mysqli_stmt_num_rows($stmt) === 1) {
                mysqli_stmt_bind_result($stmt, $pr_id, $user_id, $expires_at, $user_email);
                if (mysqli_stmt_fetch($stmt)) {
                    mysqli_stmt_close($stmt);

                    if ($expires_at && strtotime($expires_at) < time()) {
                    $message = 'Reset token has expired.';
                } else {
                    // update password
                    $hash = password_hash($password, PASSWORD_DEFAULT);
                    $upd = "UPDATE users SET password = ? WHERE id = ?";
                    if ($ustmt = mysqli_prepare($conn, $upd)) {
                        mysqli_stmt_bind_param($ustmt, 'si', $hash, $user_id);
                        mysqli_stmt_execute($ustmt);
                        mysqli_stmt_close($ustmt);

                        // delete used tokens for this user
                        $del = "DELETE FROM password_resets WHERE user_id = ?";
                        if ($dstmt = mysqli_prepare($conn, $del)) {
                            mysqli_stmt_bind_param($dstmt, 'i', $user_id);
                            mysqli_stmt_execute($dstmt);
                            mysqli_stmt_close($dstmt);
                        }

                        $message = 'Password updated — you can now log in.';
                    } else {
                        $message = 'Database error updating password.';
                    }
                }
            } else {
                $message = 'Invalid reset token.';
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
  <title>Reset password — MediaBay</title>
  <link rel="stylesheet" href="assets/css/main.css">
  <style>.container{max-width:600px;margin:40px auto;padding:18px;background:#fff;border-radius:8px;} .notice{color:#333;margin-bottom:8px}</style>
    <meta property="og:image" content="assets/images/logo/logo.jpeg">
    <link rel="icon" type="image/png" href="assets/images/logo/logo.jpeg"  />
</head>
<body>
  <div class="container">
    <a href="index.php">← Back</a>
    <h2>Reset password</h2>
    <?php if(!empty($message)): ?><div class="notice"><?php echo htmlspecialchars($message); ?></div><?php endif; ?>

    <?php if(empty($message) || strpos($message, 'updated') === false): ?>
      <form method="post" action="reset.php">
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
        <label>New password</label>
        <input type="password" name="password" required style="width:100%;padding:8px;margin:8px 0;" />
        <label>Confirm password</label>
        <input type="password" name="confirm_password" required style="width:100%;padding:8px;margin:8px 0;" />
        <button type="submit" style="padding:8px 12px;background:#4CAF50;color:#fff;border:none;border-radius:6px;cursor:pointer">Update password</button>
      </form>
    <?php endif; ?>

  </div>
</body>
</html>
