<?php
require_once __DIR__ . '/layout.php';
require_once __DIR__ . '/../Control/23_ViewIDPController.php';

class ViewIDPPage
{
    private ?IDP $idp = null;

    public function displayIDPPage(): void
    {
        $p = $this->idp;
        renderHeader($p ? $p->getTitle() : 'View Project');
        if ($p === null) {
            renderMessage('Project record does not exist.', false);
        } else {
            echo '<div class="detail">' . photoTag($p->getPhoto(), $p->getTitle()) . '</div>
                  <p>' . nl2br(e($p->getDescription())) . '</p>
                  <table>
                    <tr><th>Category</th><td>' . e($p->getCategory()) . '</td></tr>
                    <tr><th>Status</th><td>' . statusPill($p->getStatus()) . ' (' . $p->getProgress() . '% complete, ' . e($p->getStage()) . ' project)</td></tr>
                    <tr><th>Designer</th><td>' . e($p->getDesignerName() ?: 'Unknown') . '</td></tr>
                    <tr><th>Company</th><td>' . e($p->getCompanyName() ?: 'Not registered') . '</td></tr>
                  </table>
                  <form method="post" action="24_SaveShortlistPage.php">
                    <input type="hidden" name="projectID" value="' . $p->getProjectID() . '">
                    <button type="submit">Save to my shortlist</button>
                    <a class="btn light" href="22_SearchIDPPage.php">Back to search</a>
                  </form>';
        }
        renderFooter();
    }

    public function requestIDPDetails(int $projectID): void
    {
        $this->idp = (new ViewIDPController())->getIDP($projectID);
        $this->displayIDPPage();
    }
}

startSession('Customer');
(new ViewIDPPage())->requestIDPDetails((int) ($_GET['id'] ?? 0));
