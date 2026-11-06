<?php
require_once __DIR__ . '/../Entity/Company.php';

class RegisterCompanyController
{
    private Company $company;
    private string $error = '';

    public function __construct(?Company $company = null)
    {
        $this->company = $company ?? new Company();
    }

    public function getError(): string { return $this->error; }

    public function registerCompany(int $designerID, string $companyName, string $regNum, string $description, string $address): bool
    {
        $this->error = '';
        if (trim($companyName) === '') {
            $this->error = 'invalid';
            return false;
        }
        if (!$this->company->createCompany($designerID, trim($companyName), trim($regNum), trim($description), trim($address))) {
            $this->error = 'exists';
            return false;
        }
        return true;
    }
}
