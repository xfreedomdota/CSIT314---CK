<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../Control/15_RegisterCompanyController.php';
require_once __DIR__ . '/../Control/16_ViewCompanyController.php';
require_once __DIR__ . '/../Control/17_UpdateCompanyController.php';
require_once __DIR__ . '/../Control/18_DeleteCompanyController.php';
require_once __DIR__ . '/../Control/19_SearchCompanyController.php';
require_once __DIR__ . '/../Entity/UserAccount.php';
require_once __DIR__ . '/../Entity/UserProfile.php';
require_once __DIR__ . '/TestDatabase.php';

class CompanyTest extends TestCase
{
    private Company $company;
    private int $designer;

    protected function setUp(): void
    {
        $db = TestDatabase::create();
        $this->company  = new Company($db);
        $this->designer = TestDatabase::addUser($db, 'designer', 'pw', 'Dee Signer', 'Interior Designer');
    }

    private function register(): bool
    {
        return (new RegisterCompanyController($this->company))
            ->registerCompany($this->designer, 'CK Interiors', 'REG1', 'Homes and offices', 'Singapore');
    }

    public function testRegisterCompany(): void
    {
        $this->assertTrue($this->register());
    }

    public function testRegisterCompanyWithoutName(): void
    {
        $controller = new RegisterCompanyController($this->company);
        $this->assertFalse($controller->registerCompany($this->designer, '', 'R', 'D', 'A'));
        $this->assertSame('invalid', $controller->getError());
    }

    public function testRegisterSecondCompanyFails(): void
    {
        $this->register();
        $controller = new RegisterCompanyController($this->company);
        $this->assertFalse($controller->registerCompany($this->designer, 'Another', 'R', 'D', 'A'));
        $this->assertSame('exists', $controller->getError());
    }

    public function testViewCompany(): void
    {
        $this->register();
        $found = (new ViewCompanyController($this->company))->getCompany($this->designer);
        $this->assertSame('CK Interiors', $found->getCompanyName());
        $this->assertSame('Dee Signer', $found->getDesignerName());
    }

    public function testViewCompanyNotRegistered(): void
    {
        $this->assertNull((new ViewCompanyController($this->company))->getCompany($this->designer));
    }

    public function testUpdateCompany(): void
    {
        $this->register();
        $this->assertTrue((new UpdateCompanyController($this->company))->updateCompany($this->designer, 'CK Design', 'REG1', 'New', 'Jurong'));
        $this->assertSame('CK Design', $this->company->searchCompany($this->designer)->getCompanyName());
    }

    public function testUpdateCompanyWithoutName(): void
    {
        $this->register();
        $this->assertFalse((new UpdateCompanyController($this->company))->updateCompany($this->designer, '', '', '', ''));
    }

    public function testDeleteCompany(): void
    {
        $this->register();
        $this->assertTrue((new DeleteCompanyController($this->company))->deleteCompany($this->designer));
        $this->assertNull($this->company->searchCompany($this->designer));
    }

    public function testDeleteMissingCompany(): void
    {
        $this->assertFalse((new DeleteCompanyController($this->company))->deleteCompany($this->designer));
    }

    public function testSearchCompanies(): void
    {
        $this->register();
        $controller = new SearchCompanyController($this->company);
        $this->assertCount(1, $controller->searchCompanies('offices'));
        $this->assertCount(0, $controller->searchCompanies('zzz'));
    }
}
