<?php
require_once __DIR__ . '/../Entity/Company.php';

class UpdateCompanyController
{
    private Company $company;

    public function __construct(?Company $company = null)
    {
        $this->company = $company ?? new Company();
    }

    public function updateCompany(int $designerID, string $companyName, string $regNum, string $description, string $address): bool
    {
        if ($companyName === '') {
            return false;
        }
        return $this->company->updateCompany($designerID, $companyName, $regNum, $description, $address);
    }
}
