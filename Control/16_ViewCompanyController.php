<?php
require_once __DIR__ . '/../Entity/Company.php';

class ViewCompanyController
{
    private Company $company;

    public function __construct(?Company $company = null)
    {
        $this->company = $company ?? new Company();
    }

    public function getCompany(int $designerID): ?Company
    {
        return $this->company->searchCompany($designerID);
    }
}
