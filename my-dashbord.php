<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>My Dashbord — MediaBay</title>
  <link rel="stylesheet" href="assets/css/main.css">
  <link rel="stylesheet" href="assets/css/dashboard.css">
  <link rel="stylesheet" href="assets/css/my-dashbord.css">
    <link rel="icon" type="image/png" href="assets/images/logo/logo.jpeg"  />
    <meta property="og:image" content="assets/images/logo/logo.jpeg">
</head>
<body>
  <header class="dash-header">
    <a class="back" href="index.php">← Home</a>
    <h1>My Dashbord</h1>
    <div class="user" id="user-name">User ID</div>
  </header>

  <main class="dash-main">
    <section class="left-col">
      <div class="card" id="subscriptions">
        <h2>Subscriptions</h2>
        <p class="muted">Active plans & renewal dates</p>
        <div id="subscriptions-list">Loading…</div>
      </div>

      <div class="card" id="projects">
        <h2>Projects</h2>
        <p class="muted">Your web projects and statuses</p>
        <div id="projects-list">Loading…</div>
      </div>

      <div class="card" id="orders">
        <h2>Purchases</h2>
        <p class="muted">Orders, invoices & receipts</p>
        <div id="orders-list">Loading…</div>
      </div>

      <div class="card" id="support">
        <h2>Support</h2>
        <p class="muted">Open tickets & messages</p>
        <div id="support-list">Loading…</div>
      </div>
    </section>

    <aside class="right-col">
      <div class="card" id="billing">
        <h3>Billing Summary</h3>
        <p class="muted">Next payment: <span id="next-payment">—</span></p>
        <button id="manage-billing">Manage</button>
      </div>

  <div class="card" id="subscriptions">
  <h2>Subscription</h2>
  <p class="muted">Website service & billing status</p>

  <div class="subscription-row">
    <div>
      <strong>Starter Website Plan</strong>
      <div class="muted">Started: <span id="sub-start">—</span></div>
      <div class="muted">Next renewal: <span id="sub-renew">—</span></div>
    </div>

    <span id="sub-status" class="status-badge">—</span>
  </div>

  <div id="sub-message" class="muted" style="margin-top:10px">
    —
  </div>

  <div style="margin-top:14px">
    <button id="renew-subscription">Renew Subscription</button>
  </div>
</div>

      <div class="card" id="account-settings">
        <h3>Account</h3>
        <a href="#">Profile & Security</a><br>
        <a href="#">Team / Collaborators</a>
      </div>
    </aside>
  </main>

  <footer class="dash-footer">
    <small>MediaBay — Manage your services</small>
  </footer>

  <script src="assets/js/components.js" defer></script>
  <script src="assets/js/sw-registration.js" defer></script>
  <script src="assets/js/my-dashbord.js" defer></script>
</body>
</html>
