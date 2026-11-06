<?php
require_once __DIR__ . '/layout.php';
require_once __DIR__ . '/stories.php';

startSession();
renderHeader('All 48 User Stories');
echo '<p class="muted">Each story has one Boundary page, one Control class and the Entity class(es) it uses.
      Pages ask you to log in as the right role first.</p>
      <table><tr><th>#</th><th>Role</th><th>Story</th><th>Boundary</th><th>Control</th><th>Entity</th></tr>';
foreach (STORIES as $number => [$role, $story, $page, $control, $entity]) {
    $file = storyFile($number, $page);
    $link = in_array($number, NEEDS_SELECTION, true) ? e($file) : '<a href="' . $file . '">' . e($file) . '</a>';
    echo '<tr><td class="tag">' . $number . '</td><td>' . e($role) . '</td><td>' . e($story) . '</td><td>' . $link
       . '</td><td>' . e(storyFile($number, $control)) . '</td><td>' . e($entity) . '</td></tr>';
}
echo '</table>';
renderFooter();
