<?php
require_once __DIR__ . '/layout.php';
require_once __DIR__ . '/../Control/33_ViewPastIDPController.php';

class ViewPastIDPPage
{
    public function displayPastIDPsPage(int $designerID): void
    {
        $projects = (new ViewPastIDPController())->getPastIDPs($designerID);

        renderHeader('My Past Portfolio');
        echo '<a class="btn" href="32_AddPastIDPPage.php">+ Add past project</a>';
        if (count($projects) === 0) {
            echo '<br><br>';
            renderMessage('No past projects exist.', false);
        } else {
            renderProjectCards($projects, fn($p) =>
                '<a href="34_UpdatePastIDPPage.php?id=' . $p->getProjectID() . '">Update</a> &middot; '
              . '<a href="35_DeletePastIDPPage.php?id=' . $p->getProjectID() . '">Delete</a>');
        }
        renderFooter();
    }
}

startSession('Interior Designer');
(new ViewPastIDPPage())->displayPastIDPsPage(currentUserId());
