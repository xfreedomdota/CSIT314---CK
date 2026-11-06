<?php
require_once __DIR__ . '/layout.php';
require_once __DIR__ . '/../Control/13_20_39_LoginController.php';

class LoginPage
{
    private string $message = '';
    private string $username = '';

    public function displayLoginPage(): void
    {
        ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sign in | CK</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Newsreader:ital,wght@0,400;0,500;1,400&family=Figtree:wght@400;500;600&display=swap" rel="stylesheet">
  <style>
    :root { --ink:#1c2321; --pine:#1f3a36; --plaster:#e9ecea; --brass:#a97c2f; --paper:#fafafa; --wall:#cfd8d3;
            --serif:"Newsreader", Georgia, serif; --sans:"Figtree", system-ui, sans-serif; }
    * { box-sizing: border-box; }
    body { margin: 0; font-family: var(--sans); color: var(--ink); background: var(--paper); line-height: 1.6;
           min-height: 100vh; display: flex; flex-direction: column; }
    a { color: inherit; }
    :focus-visible { outline: 3px solid var(--brass); outline-offset: 3px; }
    .wrap { max-width: 1080px; margin: 0 auto; padding: 0 1.5rem; width: 100%; }

    /* Header - same as home.html */
    header { padding: 1.25rem 0; }
    header .wrap { display: flex; flex-wrap: wrap; gap: 1rem; align-items: center; justify-content: space-between; }
    .logo { font-family: var(--serif); font-size: 2rem; text-decoration: none; }
    nav a { margin-left: 1.5rem; text-decoration: none; font-weight: 500; }
    nav a:hover { color: var(--brass); }
    nav a.nav-login { background: var(--pine); color: #fff; padding: .45rem 1.1rem; border-radius: 4px; }

    /* Sign-in box */
    main { flex: 1; display: flex; align-items: flex-start; justify-content: center; padding: 3rem 1.5rem 5rem; }
    .signin { width: 100%; max-width: 760px; background: #fff; border: 1px solid #d6dbd8; border-radius: 6px;
              padding: 2.5rem 2.25rem; }
    .signin h1 { font-family: var(--serif); font-weight: 400; font-size: 2.4rem; line-height: 1.1; margin: 0 0 1rem; }
    label { display: block; font-size: .9rem; margin: 1rem 0 .35rem; }
    input { width: 100%; padding: .75rem .8rem; border: 1px solid #c9cfcb; border-radius: 4px; font: inherit; background: #fff; }
    input:focus { border-color: var(--pine); outline: none; box-shadow: 0 0 0 3px rgba(31,58,54,.15); }
    button { width: 100%; margin-top: 1.75rem; padding: .85rem; border: 0; border-radius: 4px; background: var(--pine);
             color: #fff; font: inherit; font-weight: 600; letter-spacing: .04em; text-transform: uppercase; cursor: pointer; }
    button:hover { background: #162b28; }
    .error { background: #fff5f5; color: #c92a2a; padding: .7rem 1rem; border-radius: 4px; margin-bottom: .5rem; }

    /* Footer - three panels like the reference */
    footer { display: grid; grid-template-columns: repeat(3, 1fr); text-align: center; }
    footer section { padding: 3rem 1.5rem; }
    footer h2 { font-family: var(--serif); font-weight: 400; font-size: 1.8rem; margin: 0 0 .5rem; }
    footer h2::after { content: ""; display: block; width: 36px; height: 3px; margin: .8rem auto 1.2rem; background: currentColor; }
    footer p { margin: .2rem 0; font-size: .95rem; }
    footer a { text-decoration: none; } footer a:hover { color: var(--brass); }
    .f1 { background: var(--pine); color: #fff; }
    .f2 { background: var(--plaster); }
    .f3 { background: var(--wall); }
    .copy { text-align: center; font-size: .85rem; padding: 1rem; color: #5b6560; }

    @media (max-width: 800px) { footer { grid-template-columns: 1fr; } nav a { margin: 0 1rem 0 0; } }
  </style>
</head>
<body>

<header>
  <div class="wrap">
    <a class="logo" href="home.html"><img src="../Assets/CK.jpg" alt="CK" height="125"
       onerror="this.replaceWith(document.createTextNode('CK'))"></a>
    <nav aria-label="Main">
      <a href="home.html#services">Services</a>
      <a href="home.html#projects">Projects</a>
      <a href="home.html#contact">Contact</a>
      <a class="nav-login" href="13_20_39_LoginPage.php" aria-current="page">Login</a>
    </nav>
  </div>
</header>

<main>
  <div class="signin">
    <h1>Sign in</h1>
    <?php if ($this->message !== ''): ?>
      <div class="error" role="alert"><?= e($this->message) ?></div>
    <?php endif; ?>
    <form method="post">
      <label for="username">Username</label>
      <input id="username" name="username" autocomplete="username" required autofocus value="<?= e($this->username) ?>">
      <label for="password">Password</label>
      <input id="password" name="password" type="password" autocomplete="current-password" required>
      <button type="submit">Login</button>
    </form>
  </div>
</main>

<footer>
  <section class="f1">
    <h2>Contact us</h2>
    <p><a href="mailto:CKhelp@outlook.com">CKhelp@outlook.com</a></p>
    <p>We reply within three working days</p>
  </section>
  <section class="f2">
    <h2>Opening hours</h2>
    <p>Monday to Friday</p>
    <p>9am to 6pm</p>
  </section>
  <section class="f3">
    <h2>Visit us</h2>
    <p>CK Interiors Showroom</p>
    <p>10 Anson Road</p>
    <p>Singapore 079903</p>
  </section>
</footer>
<div class="copy">&copy; 2026 CK</div>

</body>
</html>
        <?php
    }

    public function submit(string $username, string $password): void
    {
        if ((new LoginController())->login($username, $password)) {
            header('Location: Dashboard.php');
            exit;
        }
        $this->username = $username;
        $this->message  = 'Invalid username or password, or the account is suspended.';
        $this->displayLoginPage();
    }
}

startSession();
if (!empty($_SESSION['role'])) {
    header('Location: Dashboard.php');
    exit;
}
$page = new LoginPage();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $page->submit($_POST['username'] ?? '', $_POST['password'] ?? '');
} else {
    $page->displayLoginPage();
}
