<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../Control/22_SearchIDPController.php';
require_once __DIR__ . '/../Control/23_ViewIDPController.php';
require_once __DIR__ . '/../Control/24_SaveShortlistController.php';
require_once __DIR__ . '/../Control/25_ShortlistSearchController.php';
require_once __DIR__ . '/../Control/26_ViewShortlistController.php';
require_once __DIR__ . '/../Control/37_ProjectAnalyticsController.php';
require_once __DIR__ . '/../Control/38_IDPAnalyticsController.php';
require_once __DIR__ . '/../Entity/UserAccount.php';
require_once __DIR__ . '/../Entity/UserProfile.php';
require_once __DIR__ . '/TestDatabase.php';

class CustomerTest extends TestCase
{
    private IDP $idp;
    private Shortlist $shortlist;
    private int $designer;
    private int $customer;
    private int $homeID;
    private int $officeID;

    protected function setUp(): void
    {
        $db = TestDatabase::create();
        $this->idp       = new IDP($db);
        $this->shortlist = new Shortlist($db);
        $this->designer  = TestDatabase::addUser($db, 'designer', 'pw', 'Dee Signer', 'Interior Designer');
        $this->customer  = TestDatabase::addUser($db, 'customer', 'pw', 'Cus Tomer', 'Customer');

        $this->idp->createIDP($this->designer, 'Scandi Home', 'Scandinavian', 'Oak and light', '', 'In Progress', 50);
        $this->idp->createIDP($this->designer, 'City Office', 'Commercial', 'Open desks', '', 'Planning', 10);
        [$office, $home] = $this->idp->searchIDP('', '');
        $this->homeID   = $home->getProjectID();
        $this->officeID = $office->getProjectID();
    }

    private function save(int $projectID): bool
    {
        return (new SaveShortlistController($this->shortlist, $this->idp))->saveIDPToShortlist($this->customer, $projectID);
    }

    public function testSearchIDPByKeywordAndCategory(): void
    {
        $controller = new SearchIDPController($this->idp);
        $this->assertCount(2, $controller->searchIDP('', ''));
        $this->assertCount(1, $controller->searchIDP('oak', ''));
        $this->assertCount(1, $controller->searchIDP('', 'Commercial'));
        $this->assertCount(0, $controller->searchIDP('zzz', ''));
    }

    public function testViewIDPRecordsAView(): void
    {
        $found = (new ViewIDPController($this->idp))->getIDP($this->homeID);
        $this->assertSame('Dee Signer', $found->getDesignerName());
        $this->assertSame(1, $this->idp->fetchViewCount($this->designer)[0]->getViewCount());
    }

    public function testViewMissingIDP(): void
    {
        $this->assertNull((new ViewIDPController($this->idp))->getIDP(999));
    }

    public function testSaveIDPToShortlist(): void
    {
        $this->assertTrue($this->save($this->homeID));
    }

    public function testSaveSameIDPTwice(): void
    {
        $controller = new SaveShortlistController($this->shortlist, $this->idp);
        $controller->saveIDPToShortlist($this->customer, $this->homeID);
        $this->assertFalse($controller->saveIDPToShortlist($this->customer, $this->homeID));
        $this->assertSame('exists', $controller->getError());
    }

    public function testSaveMissingIDP(): void
    {
        $controller = new SaveShortlistController($this->shortlist, $this->idp);
        $this->assertFalse($controller->saveIDPToShortlist($this->customer, 999));
        $this->assertSame('not_found', $controller->getError());
    }

    public function testSearchShortlistOnlyReturnsSavedIDP(): void
    {
        $this->save($this->homeID);
        $controller = new ShortlistSearchController($this->shortlist);
        $this->assertCount(1, $controller->searchShortlist($this->customer, '', ''));
        $this->assertCount(0, $controller->searchShortlist($this->customer, '', 'Commercial'));
    }

    public function testGetShortlistedIDP(): void
    {
        $controller = new ViewShortlistController($this->shortlist);
        $this->assertSame([], $controller->getShortlistedIDP($this->customer));
        $this->save($this->homeID);
        $this->save($this->officeID);
        $this->assertCount(2, $controller->getShortlistedIDP($this->customer));
    }

    public function testIDPViewCountMostViewedFirst(): void
    {
        $this->idp->recordView($this->officeID);
        $this->idp->recordView($this->officeID);
        $this->idp->recordView($this->homeID);
        $list = (new ProjectAnalyticsController($this->idp))->getIDPViewCount($this->designer);
        $this->assertSame('City Office', $list[0]->getTitle());
        $this->assertSame(2, $list[0]->getViewCount());
    }

    public function testShortlistCount(): void
    {
        $this->save($this->homeID);
        $list = (new IDPAnalyticsController($this->idp))->getShortlistCount($this->designer);
        $this->assertSame('Scandi Home', $list[0]->getTitle());
        $this->assertSame(1, $list[0]->getShortlistCount());
        $this->assertSame(0, $list[1]->getShortlistCount());
    }
}
