<?php
if (!defined('PHELON_LOGIN_ENTRY')) {
    http_response_code(404);
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex, nofollow">
  <title>Staff Sign In | The PHELONPRENEUR Network</title>
  <link rel="icon" href="../images/favicon.ico">
  <link rel="stylesheet" href="frontend/login.css">
  <script src="frontend/login.js" defer></script>
</head>
<body>
<main class="portal" data-portal data-endpoint="backend/api/session.php">
  <section class="portal-brand" aria-label="The PHELONPRENEUR Network">
    <a class="brand-link" href="../index.php">
      <img src="../images/logo.png" width="60" height="60" alt="">
      <span>The PHELONPRENEUR Network</span>
    </a>
    <div>
      <p class="eyebrow">Private staff portal</p>
      <p class="brand-statement">A quiet space for the work behind the call.</p>
    </div>
    <a class="back-link" href="../index.php">Return to the website</a>
  </section>
  <section class="portal-content" aria-labelledby="portal-title">
    <div class="portal-inner">
      <div data-signed-out>
        <p class="eyebrow">Staff access</p>
        <h1 id="portal-title">Sign in</h1>
        <p class="intro">Use your Phelonpreneur staff account.</p>
        <form method="post" action="backend/api/session.php">
          <label for="staff-email">Work email</label>
          <input id="staff-email" name="email" type="email" autocomplete="username" maxlength="254" required>
          <label for="staff-password">Password</label>
          <input id="staff-password" name="password" type="password" autocomplete="current-password" maxlength="72" required>
          <button type="submit" disabled>Sign in</button>
        </form>
        <noscript><p>JavaScript is required to sign in.</p></noscript>
      </div>
      <div data-signed-in hidden>
        <p class="eyebrow">Staff access</p>
        <h2 tabindex="-1">You’re signed in</h2>
        <p class="intro" data-user-name></p>
        <button type="button" data-sign-out>Sign out</button>
      </div>
      <p role="status" data-login-status hidden></p>
    </div>
  </section>
</main>
</body>
</html>
