<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../Control/41_CreateCategoryController.php';
require_once __DIR__ . '/../Control/42_ViewCategoryController.php';
require_once __DIR__ . '/../Control/43_UpdateCategoryController.php';
require_once __DIR__ . '/../Control/44_DeleteCategoryController.php';
require_once __DIR__ . '/../Control/45_SearchCategoryController.php';
require_once __DIR__ . '/../Control/46_DailyReportController.php';
require_once __DIR__ . '/../Control/47_WeeklyReportController.php';
require_once __DIR__ . '/../Control/48_MonthlyReportController.php';
require_once __DIR__ . '/../Entity/IDP.php';
require_once __DIR__ . '/TestDatabase.php';

class PlatformTest extends TestCase
{
    private PDO $db;
    private Category $category;

    protected function setUp(): void
    {
        $this->db       = TestDatabase::create();
        $this->category = new Category($this->db);
        $this->category->createCategory('Residential', 'Homes');
    }

    public function testCreateCategory(): void
    {
        $this->assertTrue((new CreateCategoryController($this->category))->createCategory('Minimalist', 'Simple'));
    }

    public function testCreateDuplicateCategory(): void
    {
        $controller = new CreateCategoryController($this->category);
        $this->assertFalse($controller->createCategory('residential', 'same name, other case'));
        $this->assertSame('exists', $controller->getError());
    }

    public function testCreateCategoryWithoutName(): void
    {
        $controller = new CreateCategoryController($this->category);
        $this->assertFalse($controller->createCategory('', 'x'));
        $this->assertSame('invalid', $controller->getError());
    }

    public function testViewCategory(): void
    {
        $controller = new ViewCategoryController($this->category);
        $this->assertSame('Residential', $controller->getCategoryDetails(1)->getCategoryName());
        $this->assertNull($controller->getCategoryDetails(99));
    }

    public function testUpdateCategory(): void
    {
        $this->assertTrue((new UpdateCategoryController($this->category))->updateCategory(1, 'Homes', 'Updated'));
        $this->assertSame('Homes', $this->category->searchCategoryByID(1)->getCategoryName());
    }

    public function testUpdateCategoryToExistingName(): void
    {
        $this->category->createCategory('Commercial', 'Offices');
        $this->assertFalse((new UpdateCategoryController($this->category))->updateCategory(2, 'Residential', ''));
    }

    public function testDeleteUnusedCategory(): void
    {
        $this->assertTrue((new DeleteCategoryController($this->category))->deleteCategory(1));
    }

    public function testCannotDeleteCategoryInUse(): void
    {
        (new IDP($this->db))->createIDP(1, 'Home', 'Residential', '', '', 'Planning', 0);
        $controller = new DeleteCategoryController($this->category);
        $this->assertFalse($controller->deleteCategory(1));
        $this->assertSame('in_use', $controller->getError());
    }

    public function testDeleteMissingCategory(): void
    {
        $controller = new DeleteCategoryController($this->category);
        $this->assertFalse($controller->deleteCategory(99));
        $this->assertSame('not_found', $controller->getError());
    }

    public function testSearchCategories(): void
    {
        $this->category->createCategory('Commercial', 'Offices');
        $controller = new SearchCategoryController($this->category);
        $this->assertCount(2, $controller->searchCategories(''));
        $this->assertCount(1, $controller->searchCategories('office'));
        $this->assertCount(0, $controller->searchCategories('zzz'));
    }

    public function testReportsCountActivityInTheirWindow(): void
    {
        $idp = new IDP($this->db);
        $idp->createIDP(1, 'Home', 'Residential', '', '', 'Planning', 0);
        $idp->recordView(1);
        $this->db->exec("INSERT INTO project_views (projectID, viewedAt) VALUES (1, '" . date('Y-m-d H:i:s', strtotime('-10 days')) . "')");

        $daily   = (new DailyReportController(new Report($this->db)))->generateDailyReport();
        $weekly  = (new WeeklyReportController(new WeeklyReport($this->db)))->generateWeeklyReport();
        $monthly = (new MonthlyReportController(new MonthlyReport($this->db)))->generateMonthlyReport();

        $this->assertSame(1, $daily->getSummary()['Project views']);
        $this->assertSame(1, $weekly->getSummary()['Project views']);
        $this->assertSame(2, $monthly->getSummary()['Project views']);
        $this->assertSame('Residential (2 views)', $monthly->getSummary()['Most viewed category']);
    }

    public function testGeneratedReportsAreSaved(): void
    {
        $controller = new DailyReportController(new Report($this->db));
        $controller->generateDailyReport();
        $controller->generateDailyReport();
        $this->assertCount(2, $controller->getPreviousReports());
    }
}
