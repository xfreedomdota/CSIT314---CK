<?php
require_once __DIR__ . '/layout.php';
require_once __DIR__ . '/../Control/30_DeleteCurrentIDPController.php';

class DeleteCurrentIDPPage
{
    private string $message = '';
    private bool $success = false;

    public function displayDeletePage(int $projectID): void
    {
        renderHeader('Delete Current Project');
        renderMessage($this->message, $this->success);
        if (!$this->success) {
            $idp = (new DeleteCurrentIDPController())->getOwnIDP($projectID, currentUserId(), 'Current');
            if ($idp === null) {
                renderMessage('Project not found.', false);
            } else {
                echo '<p>Delete <strong>' . e($idp->getTitle()) . '</strong>? Its views and shortlist entries are removed too.</p>
                      <form method="post"><input type="hidden" name="projectID" value="' . $projectID . '">
                      <button type="submit" class="danger">Delete project</button></form>';
            }
        }
        echo '<p><a href="28_ViewCurrentIDPPage.php">&larr; Back to my current projects</a></p>';
        renderFooter();
    }

    public function DeleteIDP(int $projectID): void
    {
        $this->success = (new DeleteCurrentIDPController())->DeleteIDP($projectID, currentUserId());
        $this->message = $this->success ? 'Project deleted.' : 'Project not found.';
        $this->displayDeletePage($projectID);
    }
}

startSession('Interior Designer');
$page = new DeleteCurrentIDPPage();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $page->DeleteIDP((int) ($_POST['projectID'] ?? 0));
} else {
    $page->displayDeletePage((int) ($_GET['id'] ?? 0));
}
