<?php
require_once __DIR__ . '/layout.php';
require_once __DIR__ . '/stories.php';

const DASHBOARD = [
    'User Admin' => [
        'User profiles' => [
            3  => ['Create profile', 'Set up a role-based profile'],
            4  => ['View profile', "Check a user's profile data"],
            5  => ['Update profile', 'Keep profiles accurate'],
            6  => ['Suspend profile', 'Remove invalid profiles'],
            7  => ['Search profiles', 'Find profiles by filter'],
        ],
        'User accounts' => [
            8  => ['Create account', 'Give a new user access'],
            9  => ['View account', 'Check account details'],
            10 => ['Update account', 'Change email, password or status'],
            11 => ['Suspend account', 'Block unauthorised access'],
            12 => ['Search accounts', 'Find by ID or keyword'],
        ],
    ],
    'Interior Designer' => [
        'Company' => [
            15 => ['Register company', 'Tell customers about your company'],
            16 => ['View company', 'See what customers see'],
            17 => ['Update company', 'Keep details accurate'],
            18 => ['Delete company', 'Remove your company listing'],
            19 => ['Search companies', 'Browse all company listings'],
        ],
        'Current projects' => [
            27 => ['New current project', 'Show clients work in progress'],
            28 => ['My current projects', 'Track active work and progress'],
            31 => ['Search current projects', 'Find by keyword or status'],
        ],
        'Past portfolio' => [
            32 => ['Add past project', 'Show finished work'],
            33 => ['My past portfolio', 'Review completed work'],
            36 => ['Search past projects', 'Find by keyword or style'],
        ],
        'Customer interest' => [
            37 => ['Project views', 'See which projects get attention'],
            38 => ['Times shortlisted', 'See real customer interest'],
        ],
    ],
    'Customer' => [
        'Discover' => [
            22 => ['Search projects', 'Find designs by keyword or category'],
        ],
        'My shortlist' => [
            26 => ['View my shortlist', 'Compare saved designs side by side'],
            25 => ['Search my shortlist', 'Filter your saved designs'],
        ],
    ],
    'Platform Manager' => [
        'IDP categories' => [
            41 => ['Create category', 'Add a new design style'],
            45 => ['All categories', 'View, update or delete categories'],
        ],
        'Reports' => [
            46 => ['Daily report', 'Activity in the last 24 hours'],
            47 => ['Weekly report', 'Performance over the last 7 days'],
            48 => ['Monthly report', 'Analytics for the last 30 days'],
        ],
    ],
];

startSession();
$role = $_SESSION['role'] ?? '';
if ($role === '') {
    header('Location: 13_20_39_LoginPage.php');
    exit;
}

$firstName = explode(' ', $_SESSION['name'] ?? '')[0];
renderHeader('Welcome back, ' . $firstName, true, true);
echo '<p class="muted" style="margin-top:-.5rem">Signed in as ' . e($role) . '.</p>';

foreach (DASHBOARD[$role] as $group => $tiles) {
    echo '<h3>' . e($group) . '</h3><div class="tiles">';
    foreach ($tiles as $number => [$title, $description]) {
        echo '<a class="tile" href="' . storyFile($number, STORIES[$number][2]) . '"><strong>' . e($title)
           . '</strong><span>' . e($description) . '</span></a>';
    }
    echo '</div>';
}
renderFooter();
