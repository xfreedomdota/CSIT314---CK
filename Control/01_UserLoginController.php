<?php
require_once __DIR__ . '/13_20_39_LoginController.php';

class UserLoginController extends LoginController
{
    protected ?string $requiredRole = 'User Admin';
}
