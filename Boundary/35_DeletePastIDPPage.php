<?php
require_once __DIR__ . '/layout.php';
require_once __DIR__ . '/../Control/35_DeletePastIDPController.php';

class DeletePastIDPPage
{
    private string $message = '';
    private bool $success = false;

    public function displayDeletePage(int $projectID): void
    {
        renderHeader('Delete Past Project');
        renderMessage($this->message, $this->success);
        if (!$this->success) {
            $idp = (new DeletePastIDPController())->getOwnIDP($projectID, currentUserId(), 'Past');
            if ($idp === null) {
                renderMessage('Project not found.', false);
            } else {
                echo '<p>Remove <strong>' . e($idp->getTitle()) . '</strong> from your portfolio?</p>
                      <form method="post"><input type="hidden" name="projectID" value="' . $projectID . '">
                      <button type="submit" class="danger">Delete project</button></form>';
            }
        }
        echo '<p><a href="33_ViewPastIDPPage.php">&larr; Back to my portfolio</a></p>';
        renderFooter();
    }

    public function deletePastIDP(int $projectID): void
    {
        $this->success = (new DeletePastIDPController())->deletePastIDP($projectID, currentUserId());
        $this->message = $this->success ? 'Project removed from your portfolio.' : 'Project not found.';
        $this->displayDeletePage($projectID);
    }
}

startSession('Interior Designer');
$page = new DeletePastIDPPage();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $page->deletePastIDP((int) ($_POST['projectID'] ?? 0));
} else {
    $page->displayDeletePage((int) ($_GET['id'] ?? 0));
}
