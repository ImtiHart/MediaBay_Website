<?php require_once __DIR__ . '/includes/server.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Authenticate — MediaBay</title>
  <link rel="stylesheet" href="assets/css/main.css">
  <style>
    .auth-container{max-width:900px;margin:40px auto;padding:20px;display:flex;gap:20px}
    .auth-panel{flex:1;background:#fff;padding:20px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,.06)}
    .tabs{display:flex;gap:10px;margin-bottom:10px}
    .tabs button{padding:8px 12px;border:none;background:#eee;border-radius:6px;cursor:pointer}
    .tabs button.active{background:#333;color:#fff}
    .error{color:#b00020;font-size:0.9rem;margin-top:6px}
  </style>
</head>
<body>
  <main class="auth-container">
    <div class="auth-panel">
      <?php if(isset($logged_in) && $logged_in): ?>
        <div style="margin-bottom:12px">You are logged in as <strong><?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?></strong>.</div>
        <form method="post" action="auth.php">
          <button type="submit" name="logout" style="padding:8px 12px;background:#e53935;color:#fff;border:none;border-radius:6px;cursor:pointer">Logout</button>
        </form>
      <?php endif; ?>
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
        <h2>Log In</h2>
        <a href="index.php">← Back</a>
      </div>
      <?php if(!empty($login_err)): ?><div class="error"><?php echo $login_err; ?></div><?php endif; ?>
      <form id="login-form" action="auth.php" method="post">
        <input type="hidden" name="login" value="1">
        <label>Username or Email</label>
        <input type="text" name="username_email" value="<?php echo htmlspecialchars($username_email); ?>" required style="width:100%;padding:8px;margin:6px 0;" />
        <?php if(!empty($username_email_err)): ?><div class="error"><?php echo $username_email_err; ?></div><?php endif; ?>
        <label>Password</label>
        <input type="password" name="login_password" required style="width:100%;padding:8px;margin:6px 0;" />
        <?php if(!empty($login_password_err)): ?><div class="error"><?php echo $login_password_err; ?></div><?php endif; ?>
        <button type="submit" style="margin-top:10px;padding:10px 14px;background:#4CAF50;color:#fff;border:none;border-radius:6px;cursor:pointer">Log In</button>
        <div style="margin-top:8px"><a href="forgot.php">Forgot password?</a></div>
      </form>
    </div>

    <div class="auth-panel">
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
        <h2>Sign Up</h2>
        <a href="index.php">← Back</a>
      </div>
      <?php if($register_success): ?><div style="color:green;margin-bottom:8px">Registration successful. Please log in.</div><?php endif; ?>
      <form id="signup-form" action="auth.php" method="post">
        <input type="hidden" name="register" value="1">
        <label>Username</label>
        <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>" required style="width:100%;padding:8px;margin:6px 0;" />
        <?php if(!empty($username_err)): ?><div class="error"><?php echo $username_err; ?></div><?php endif; ?>
        <label>Email</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required style="width:100%;padding:8px;margin:6px 0;" />
        <?php if(!empty($email_err)): ?><div class="error"><?php echo $email_err; ?></div><?php endif; ?>
        <label>Password</label>
        <input type="password" name="password" required style="width:100%;padding:8px;margin:6px 0;" />
        <?php if(!empty($password_err)): ?><div class="error"><?php echo $password_err; ?></div><?php endif; ?>
        <label>Confirm Password</label>
        <input type="password" name="confirm_password" required style="width:100%;padding:8px;margin:6px 0;" />
        <?php if(!empty($confirm_password_err)): ?><div class="error"><?php echo $confirm_password_err; ?></div><?php endif; ?>
        <button type="submit" style="margin-top:10px;padding:10px 14px;background:#4CAF50;color:#fff;border:none;border-radius:6px;cursor:pointer">Sign Up</button>
      </form>
    </div>
  </main>
</body>
</html>
