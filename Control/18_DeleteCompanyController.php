<?php
require_once __DIR__ . '/../Entity/Company.php';

class DeleteCompanyController
{
    private Company $company;

    public function __construct(?Company $company = null)
    {
        $this->company = $company ?? new Company();
    }

    public function deleteCompany(int $designerID): bool
    {
        return $this->company->deleteCompany($designerID);
    }
}
