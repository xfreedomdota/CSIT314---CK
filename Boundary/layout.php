<?php

const LOGOUT_PAGES = [
    'User Admin'        => '02_UserLogOutPage.php',
    'Interior Designer' => '14_21_40_LogOutPage.php',
    'Customer'          => '14_21_40_LogOutPage.php',
    'Platform Manager'  => '14_21_40_LogOutPage.php',
];

function startSession(?string $role = null): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    if ($role !== null && ($_SESSION['role'] ?? '') !== $role) {
        header('Location: 13_20_39_LoginPage.php');
        exit;
    }
}

function currentUserId(): int
{
    return (int) ($_SESSION['userId'] ?? 0);
}

function e(?string $text): string
{
    return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
}

function renderHeader(string $title, bool $showNav = true, bool $plain = false): void
{
    echo '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8">';
    echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
    echo '<title>' . e($title) . ' | CK Interior Design</title>';
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">'
       . '<link href="https://fonts.googleapis.com/css2?family=Newsreader:ital,wght@0,400;0,500;1,400&family=Figtree:wght@400;500;600&display=swap" rel="stylesheet">';
    echo '<style>
        :root{--ink:#1c2321;--pine:#1f3a36;--plaster:#e9ecea;--brass:#a97c2f;--paper:#fafafa;--wall:#cfd8d3;--line:#d6dbd8;
              --muted:#5b6560;--serif:"Newsreader",Georgia,serif;--sans:"Figtree",system-ui,sans-serif}
        *{box-sizing:border-box} body{font-family:var(--sans);background:var(--paper);margin:0;color:var(--ink);line-height:1.6}
        a{color:var(--pine)} :focus-visible{outline:3px solid var(--brass);outline-offset:3px}
        header.top{max-width:1080px;margin:0 auto;padding:1.25rem 1.5rem;display:flex;justify-content:space-between;align-items:center;gap:1rem;flex-wrap:wrap}
        header.top .logo{font-family:var(--serif);font-size:2rem;text-decoration:none;color:var(--ink)} header.top .logo img{height:60px;display:block}
        header.top .user{display:flex;align-items:center;gap:1.2rem;font-size:.95rem} header.top .user span{color:var(--muted)}
        header.top .user a,header.top .nav-btn{background:var(--pine);color:#fff;padding:.45rem 1.1rem;border-radius:4px;text-decoration:none;font-weight:500}
        .card{background:#fff;max-width:1032px;margin:1rem auto 3rem;padding:2rem 2.25rem;border:1px solid var(--line);border-radius:6px}
        .card.plain{background:none;border:0;padding-top:.5rem}
        h1,h2,h3{font-family:var(--serif);font-weight:400} h2{margin:0 0 1rem;font-size:2.2rem;line-height:1.1} h3{font-size:1.35rem;margin:1.5rem 0 .6rem}
        .tag{color:var(--brass);font-weight:600;margin-right:.4rem}
        label{display:block;margin:1rem 0 .35rem;font-size:.9rem}
        input,select,textarea{width:100%;padding:.7rem .8rem;border:1px solid #c9cfcb;border-radius:4px;font:inherit;background:#fff}
        input:focus,select:focus,textarea:focus{border-color:var(--pine);outline:none;box-shadow:0 0 0 3px rgba(31,58,54,.15)}
        button,.btn{display:inline-block;margin-top:1.2rem;padding:.7rem 1.4rem;border:0;border-radius:4px;background:var(--pine);color:#fff;font:inherit;font-weight:600;cursor:pointer;text-decoration:none}
        button:hover,.btn:hover{background:#162b28} button.danger,.btn.danger{background:#c92a2a} .btn.light{background:var(--plaster);color:var(--pine)}
        .msg{padding:.7rem 1rem;border-radius:4px;margin-bottom:1rem} .ok{background:#ebfbee;color:#2b8a3e} .err{background:#fff5f5;color:#c92a2a}
        table{width:100%;border-collapse:collapse;margin-top:1rem} th,td{text-align:left;padding:.6rem;border-bottom:1px solid #eceeec;vertical-align:top} th{font-weight:500}
        .row{display:flex;gap:1rem;flex-wrap:wrap} .row>*{flex:1;min-width:180px}
        .grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(250px,1fr));gap:1rem;margin-top:1rem}
        .project{background:#fff;border:1px solid var(--line);border-radius:4px;overflow:hidden}
        .project img,.nophoto{width:100%;height:170px;object-fit:cover;display:block;background:var(--plaster)}
        .project div.body{padding:1rem} .project h3{margin:.1rem 0 .3rem;font-size:1.25rem} .muted{color:var(--muted);font-size:.88rem}
        .bar{height:6px;background:var(--plaster);border-radius:6px;overflow:hidden;margin:.5rem 0} .bar span{display:block;height:100%;background:var(--brass)}
        .detail img{width:100%;max-height:440px;object-fit:cover;border-radius:4px}
        .pill{display:inline-block;font-size:.75rem;font-weight:500;padding:.1rem .6rem;border-radius:20px;background:var(--plaster);color:var(--pine)}
        .pill.active,.pill.completed{background:#e3f1e8;color:#215c3a} .pill.in-progress{background:#fdf3e1;color:#7a5a20}
        .pill.suspended,.pill.on-hold{background:#fdeceb;color:#9b2c2c} .pill.planning{background:#e7eef6;color:#2c4a6b}
        .tiles{display:grid;grid-template-columns:repeat(auto-fill,minmax(230px,1fr));gap:.8rem}
        .tile{display:block;background:#fff;border:1px solid var(--line);border-radius:4px;padding:1rem 1.1rem;text-decoration:none;color:var(--ink)}
        .tile:hover{border-color:var(--pine)} .tile strong{display:block;font-weight:500} .tile span{color:var(--muted);font-size:.88rem}
        ul.menu{line-height:2} @media print{header.top,form,.noprint{display:none}}
    </style></head><body>';
    if ($showNav) {
        $role    = $_SESSION['role'] ?? '';
        $logoUrl = $role !== '' ? 'Dashboard.php' : 'home.html';
        echo '<header class="top"><a class="logo" href="' . $logoUrl . '"><img src="../Assets/CK.jpg" alt="CK" '
           . 'onerror="this.replaceWith(document.createTextNode(\'CK\'))"></a>';
        if ($role !== '') {
            $name = $_SESSION['name'] ?? '';
            $who  = $name === $role ? e($name) : e($name) . ' &middot; ' . e($role);
            echo '<div class="user"><span>' . $who . '</span><a href="' . LOGOUT_PAGES[$role] . '">Logout</a></div>';
        } else {
            echo '<a class="nav-btn" href="home.html">Home</a>';
        }
        echo '</header>';
    }
    echo '<div class="card' . ($plain ? ' plain' : '') . '"><h2>' . e($title) . '</h2>';
}

function statusPill(string $status): string
{
    $class = strtolower(str_replace(' ', '-', $status));
    return '<span class="pill ' . e($class) . '">' . e($status) . '</span>';
}

function renderMessage(string $message, bool $success): void
{
    if ($message !== '') {
        echo '<div class="msg ' . ($success ? 'ok' : 'err') . '">' . e($message) . '</div>';
    }
}

function renderFooter(): void
{
    echo '</div></body></html>';
}

function photoTag(string $photo, string $alt): string
{
    return $photo !== ''
        ? '<img src="../Assets/' . e($photo) . '" alt="' . e($alt) . '">'
        : '<div class="nophoto"></div>';
}

function renderProjectCards(array $projects, ?callable $actions = null): void
{
    echo '<div class="grid">';
    foreach ($projects as $p) {
        echo '<article class="project">' . photoTag($p->getPhoto(), $p->getTitle()) . '<div class="body">'
           . '<h3>' . e($p->getTitle()) . '</h3>'
           . '<div class="muted">' . e($p->getCategory()) . ' &middot; ' . e($p->getStage()) . ' ' . statusPill($p->getStatus()) . '</div>'
           . '<p>' . e(mb_strimwidth($p->getDescription(), 0, 110, '...')) . '</p>'
           . '<div class="muted">Designer: ' . e($p->getDesignerName() ?: 'Unknown') . '</div>'
           . '<div class="bar"><span style="width:' . $p->getProgress() . '%"></span></div>'
           . '<div class="muted">' . $p->getProgress() . '% complete</div>'
           . ($actions ? '<p>' . $actions($p) . '</p>' : '')
           . '</div></article>';
    }
    echo '</div>';
}

function renderProjectForm(?IDP $p, array $categories, bool $isCurrent, string $buttonText): void
{
    echo '<form method="post" enctype="multipart/form-data">';
    if ($p !== null) {
        echo '<input type="hidden" name="projectID" value="' . $p->getProjectID() . '">';
    }
    echo '<label>Project title</label><input name="title" required value="' . e($p?->getTitle()) . '">';
    echo '<label>Category / style</label><select name="category">';
    foreach ($categories as $c) {
        $selected = $p !== null && $p->getCategory() === $c->getCategoryName() ? ' selected' : '';
        echo '<option' . $selected . '>' . e($c->getCategoryName()) . '</option>';
    }
    echo '</select><label>Description</label><textarea name="description" rows="4">' . e($p?->getDescription()) . '</textarea>';
    if ($isCurrent) {
        echo '<div class="row"><div><label>Status</label><select name="status">';
        foreach (IDP::STATUSES as $s) {
            $selected = ($p?->getStatus() ?? 'Planning') === $s ? ' selected' : '';
            echo '<option' . $selected . '>' . e($s) . '</option>';
        }
        echo '</select></div><div><label>Progress (%)</label><input type="number" name="progress" min="0" max="100" value="'
           . ($p?->getProgress() ?? 0) . '"></div></div>';
    }
    echo '<label>Photo (JPG, PNG, WEBP or GIF, max 5 MB)' . ($p ? ' - leave empty to keep current photo' : '') . '</label>'
       . '<input type="file" name="photo" accept="image/jpeg,image/png,image/webp,image/gif">'
       . '<button type="submit">' . e($buttonText) . '</button></form>';
}

function renderCategoryOptions(array $categories, string $selected): string
{
    $html = '<option value="">All categories</option>';
    foreach ($categories as $c) {
        $name  = $c->getCategoryName();
        $html .= '<option' . ($name === $selected ? ' selected' : '') . '>' . e($name) . '</option>';
    }
    return $html;
}

function uploadedPhoto(): ?array
{
    return isset($_FILES['photo']) && $_FILES['photo']['error'] !== UPLOAD_ERR_NO_FILE ? $_FILES['photo'] : null;
}

function renderReport(Report $report): void
{
    echo '<h3>' . e(ucfirst($report->getReportType())) . ' report &middot; generated ' . e($report->getGenerationDate()) . '</h3><table>';
    foreach ($report->getSummary() as $label => $value) {
        echo '<tr><th>' . e($label) . '</th><td>' . e((string) $value) . '</td></tr>';
    }
    echo '</table><button type="button" class="noprint" onclick="window.print()">Print report</button>';
}

function renderPreviousReports(array $reports): void
{
    echo '<h3 class="noprint">Previous reports</h3>';
    if (count($reports) === 0) {
        echo '<p class="muted noprint">None yet.</p>';
        return;
    }
    echo '<table class="noprint"><tr><th>Generated</th><th>Views</th><th>Shortlisted</th><th>New projects</th><th>Top category</th></tr>';
    foreach ($reports as $r) {
        $s = $r->getSummary();
        echo '<tr><td>' . e($r->getGenerationDate()) . '</td><td>' . e((string) ($s['Project views'] ?? '')) . '</td><td>'
           . e((string) ($s['Projects shortlisted'] ?? '')) . '</td><td>' . e((string) ($s['New projects'] ?? '')) . '</td><td>'
           . e((string) ($s['Most viewed category'] ?? '')) . '</td></tr>';
    }
    echo '</table>';
}
