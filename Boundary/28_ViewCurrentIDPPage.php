<?php
require_once __DIR__ . '/layout.php';
require_once __DIR__ . '/../Control/28_ViewCurrentIDPController.php';

class ViewCurrentIDPPage
{
    public function displayIDPPage(int $designerID): void
    {
        $projects = (new ViewCurrentIDPController())->getCurrentIDP($designerID);

        renderHeader('My Current Projects');
        echo '<a class="btn" href="27_CreateCurrentIDPPage.php">+ New current project</a>';
        if (count($projects) === 0) {
            echo '<br><br>';
            renderMessage('No active projects exist.', false);
        } else {
            renderProjectCards($projects, fn($p) =>
                '<a href="29_UpdateCurrentIDPPage.php?id=' . $p->getProjectID() . '">Update</a> &middot; '
              . '<a href="30_DeleteCurrentIDPPage.php?id=' . $p->getProjectID() . '">Delete</a>');
        }
        renderFooter();
    }
}

startSession('Interior Designer');
(new ViewCurrentIDPPage())->displayIDPPage(currentUserId());
