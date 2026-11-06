<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../Control/27_CreateCurrentIDPController.php';
require_once __DIR__ . '/../Control/28_ViewCurrentIDPController.php';
require_once __DIR__ . '/../Control/29_UpdateCurrentIDPController.php';
require_once __DIR__ . '/../Control/30_DeleteCurrentIDPController.php';
require_once __DIR__ . '/../Control/31_SearchCurrentIDPController.php';
require_once __DIR__ . '/../Control/32_AddPastIDPController.php';
require_once __DIR__ . '/../Control/33_ViewPastIDPController.php';
require_once __DIR__ . '/../Control/34_UpdatePastIDPController.php';
require_once __DIR__ . '/../Control/35_DeletePastIDPController.php';
require_once __DIR__ . '/../Control/36_SearchPastIDPController.php';
require_once __DIR__ . '/../Entity/UserAccount.php';
require_once __DIR__ . '/../Entity/UserProfile.php';
require_once __DIR__ . '/TestDatabase.php';

class IDPTest extends TestCase
{
    private IDP $idp;
    private int $designer;
    private int $otherDesigner;

    protected function setUp(): void
    {
        $db = TestDatabase::create();
        $this->idp           = new IDP($db);
        $this->designer      = TestDatabase::addUser($db, 'designer', 'pw', 'Dee Signer', 'Interior Designer');
        $this->otherDesigner = TestDatabase::addUser($db, 'other', 'pw', 'Other', 'Interior Designer');
    }

    private function createCurrent(string $title = 'Family Home', string $status = 'In Progress'): int
    {
        (new CreateCurrentIDPController($this->idp))->createIDP($this->designer, $title, 'Residential', 'Oak and light', $status, 40);
        return $this->idp->searchCurrentIDPByDesigner($this->designer)[0]->getProjectID();
    }

    private function addPast(string $title = 'Old Cafe', string $category = 'Hospitality'): int
    {
        (new AddPastIDPController($this->idp))->addPastIDP($this->designer, $title, $category, 'Done');
        return $this->idp->searchPastIDPsByDesigner($this->designer)[0]->getProjectID();
    }

    public function testCreateCurrentIDP(): void
    {
        $this->assertSame('Current', $this->idp->searchIDPByID($this->createCurrent())->getStage());
    }

    public function testCreateCurrentIDPInvalidProgress(): void
    {
        $controller = new CreateCurrentIDPController($this->idp);
        $this->assertFalse($controller->createIDP($this->designer, 'X', 'Residential', '', 'Planning', 150));
        $this->assertNotSame('', $controller->getError());
    }

    public function testCreateCurrentIDPMissingTitle(): void
    {
        $this->assertFalse((new CreateCurrentIDPController($this->idp))->createIDP($this->designer, ' ', 'Residential', '', 'Planning', 10));
    }

    public function testViewCurrentIDPOnlyShowsMine(): void
    {
        $this->createCurrent();
        $this->idp->createIDP($this->otherDesigner, 'Not mine', 'Commercial', '', '', 'Planning', 0);
        $this->assertCount(1, (new ViewCurrentIDPController($this->idp))->getCurrentIDP($this->designer));
    }

    public function testUpdateIDP(): void
    {
        $id = $this->createCurrent();
        $this->assertTrue((new UpdateCurrentIDPController($this->idp))
            ->UpdateIDP($id, $this->designer, 'Family Home v2', 'Residential', 'Updated', 'Completed', 100));
        $this->assertSame(100, $this->idp->searchIDPByID($id)->getProgress());
    }

    public function testCannotUpdateAnotherDesignersIDP(): void
    {
        $id = $this->createCurrent();
        $this->assertFalse((new UpdateCurrentIDPController($this->idp))
            ->UpdateIDP($id, $this->otherDesigner, 'Hack', 'Residential', '', 'Planning', 0));
    }

    public function testDeleteIDP(): void
    {
        $id = $this->createCurrent();
        $this->assertTrue((new DeleteCurrentIDPController($this->idp))->DeleteIDP($id, $this->designer));
        $this->assertNull($this->idp->searchIDPByID($id));
    }

    public function testDeleteIDPRejectsPastProject(): void
    {
        $this->assertFalse((new DeleteCurrentIDPController($this->idp))->DeleteIDP($this->addPast(), $this->designer));
    }

    public function testSearchCurrentIDPByKeywordAndStatus(): void
    {
        $this->createCurrent('Family Home', 'In Progress');
        $this->createCurrent('City Office', 'Planning');
        $controller = new SearchCurrentIDPController($this->idp);
        $this->assertCount(1, $controller->searchIDP($this->designer, 'office', ''));
        $this->assertCount(1, $controller->searchIDP($this->designer, '', 'In Progress'));
        $this->assertCount(0, $controller->searchIDP($this->designer, 'zzz', ''));
    }

    public function testAddPastIDPIsCompleted(): void
    {
        $past = $this->idp->searchIDPByID($this->addPast());
        $this->assertSame('Past', $past->getStage());
        $this->assertSame('Completed', $past->getStatus());
        $this->assertSame(100, $past->getProgress());
    }

    public function testViewPastIDPs(): void
    {
        $this->addPast();
        $this->createCurrent();
        $this->assertCount(1, (new ViewPastIDPController($this->idp))->getPastIDPs($this->designer));
    }

    public function testUpdatePastIDP(): void
    {
        $id = $this->addPast();
        $this->assertTrue((new UpdatePastIDPController($this->idp))->updatePastIDP($id, $this->designer, 'Renamed Cafe', 'Hospitality', 'Better'));
        $this->assertSame('Renamed Cafe', $this->idp->searchIDPByID($id)->getTitle());
    }

    public function testDeletePastIDP(): void
    {
        $id = $this->addPast();
        $this->assertTrue((new DeletePastIDPController($this->idp))->deletePastIDP($id, $this->designer));
        $this->assertFalse((new DeletePastIDPController($this->idp))->deletePastIDP($id, $this->designer));
    }

    public function testSearchPastIDPsByStyle(): void
    {
        $this->addPast('Old Cafe', 'Hospitality');
        $this->addPast('Old Flat', 'Scandinavian');
        $controller = new SearchPastIDPController($this->idp);
        $this->assertCount(1, $controller->searchPastIDPs($this->designer, '', 'Scandinavian'));
        $this->assertCount(2, $controller->searchPastIDPs($this->designer, 'old', ''));
    }
}
