<?php
mt_srand(314);

$password = 'Test123!';
$hash     = password_hash($password, PASSWORD_DEFAULT);

$firstNames = ['Aaron', 'Bella', 'Chen', 'Daniel', 'Elena', 'Farah', 'Gavin', 'Hui Min', 'Isaac', 'Jasmine', 'Kumar', 'Lina',
               'Marcus', 'Nadia', 'Oliver', 'Priya', 'Qi Wei', 'Rachel', 'Siti', 'Tom', 'Uma', 'Victor', 'Wei Ling', 'Xavier',
               'Yasmin', 'Zachary', 'Amir', 'Bryan', 'Clara', 'Dinesh', 'Esther', 'Felix', 'Grace', 'Hafiz', 'Irene', 'Joel'];
$lastNames  = ['Tan', 'Lim', 'Lee', 'Ng', 'Wong', 'Goh', 'Chua', 'Ong', 'Koh', 'Teo', 'Rahman', 'Kumar', 'Singh', 'Ismail',
               'Chen', 'Low', 'Yeo', 'Ho', 'Sim', 'Pereira', 'Fernandez', 'Nair', 'Abdullah', 'Chong', 'Foo', 'Seah'];
$styles     = ['Japandi', 'Coastal', 'Industrial Loft', 'Mid-century', 'Bohemian', 'Art Deco', 'Rustic', 'Tropical',
               'Peranakan', 'Contemporary', 'Wabi-sabi', 'French Country'];
$spaces     = ['Living Room', 'Kitchen', 'Bedroom', 'Bathroom', 'Study', 'Retail', 'Cafe', 'Office'];
$streets    = ['Orchard Road', 'Tampines Avenue', 'Jurong West Street', 'Bukit Timah Road', 'Serangoon Road',
               'Ang Mo Kio Avenue', 'Clementi Road', 'Woodlands Drive', 'Bedok North Street', 'Toa Payoh Lorong'];
$adjectives = ['Bright', 'Cosy', 'Modern', 'Open-plan', 'Compact', 'Airy', 'Warm', 'Elegant', 'Minimal', 'Family'];
$places     = ['HDB Flat', 'Condo', 'Terrace House', 'Studio', 'Penthouse', 'Office', 'Cafe', 'Boutique', 'Clinic', 'Showroom'];
$photos     = ['projects/family-home.jpg', 'projects/city-apartment.jpg', 'projects/studio-office.jpg',
               'projects/boutique-cafe.jpg', 'projects/hotel-suite.webp'];
$statuses   = ['Planning', 'In Progress', 'On Hold'];

function q(string $text): string
{
    return "'" . str_replace("'", "''", $text) . "'";
}

function ago(int $maxMinutes, int $minMinutes = 0): string
{
    return 'NOW() - INTERVAL ' . mt_rand($minMinutes, $maxMinutes) . ' MINUTE';
}

function pick(array $items)
{
    return $items[array_rand($items)];
}

function batch(string $table, string $columns, array $rows): string
{
    $sql = '';
    foreach (array_chunk($rows, 50) as $chunk) {
        $sql .= "INSERT INTO `$table` ($columns) VALUES\n" . implode(",\n", $chunk) . ";\n\n";
    }
    return $sql;
}

$accounts  = [];
$profiles  = [];
$designers = [];
$customers = [];
$userId    = 8;

$groups = [
    ['cust', 100, 'Customer'],
    ['des',  100, 'Interior Designer'],
    ['pm',   5,   'Platform Manager'],
    ['ua',   5,   'User Admin'],
];
foreach ($groups as [$prefix, $count, $type]) {
    for ($i = 1; $i <= $count; $i++) {
        $username = sprintf('%s%03d', $prefix, $i);
        $name     = pick($firstNames) . ' ' . pick($lastNames);
        $status   = mt_rand(1, 100) <= 5 ? 'Suspended' : 'Active';
        $accounts[] = sprintf('(%d, %s, %s, %s, %s)', $userId, q($username), q($hash),
                              q($username . '@example.com'), q($status));
        $profiles[] = sprintf('(%s, %s, %s, %s, %s)', q($username), q($hash), q($name), q($type), q($status));
        if ($type === 'Interior Designer') {
            $designers[] = [$userId, $name];
        } elseif ($type === 'Customer') {
            $customers[] = $userId;
        }
        $userId++;
    }
}

$categories    = [];
$categoryNames = ['Residential', 'Commercial', 'Hospitality', 'Minimalist', 'Scandinavian', 'Industrial'];
$categoryId    = 7;
foreach ($styles as $style) {
    foreach ($spaces as $space) {
        if (count($categories) === 94) {
            break 2;
        }
        $name = $style . ' ' . $space;
        $categories[]    = sprintf('(%d, %s, %s)', $categoryId++, q($name), q("$style style for a $space"));
        $categoryNames[] = $name;
    }
}

$companies = [];
$companyId = 3;
foreach ($designers as $index => [$designerId, $designerName]) {
    $lastName    = explode(' ', $designerName);
    $companyName = end($lastName) . ' ' . pick(['Interiors', 'Design Studio', 'Living', 'Spaces', 'Atelier']) . ' ' . ($index + 1);
    $companies[] = sprintf('(%d, %s, %s, %s, %s, %d)', $companyId++, q($companyName), q(sprintf('2026%05dK', $index + 1)),
                           q('Specialists in ' . strtolower(pick($styles)) . ' interiors for homes and businesses.'),
                           q(mt_rand(1, 999) . ' ' . pick($streets) . ', Singapore ' . mt_rand(100000, 829999)), $designerId);
}

$projects   = [];
$projectIds = [];
$projectId  = 6;
for ($i = 1; $i <= 150; $i++) {
    [$designerId] = pick($designers);
    $category = pick($categoryNames);
    $isPast   = mt_rand(1, 100) <= 30;
    $status   = $isPast ? 'Completed' : pick($statuses);
    $progress = $isPast ? 100 : mt_rand(0, 95);
    $created  = ago(60 * 24 * 60, 60);
    $title    = pick($adjectives) . ' ' . pick($places) . ' ' . $i;
    $projects[] = sprintf('(%d, %s, %s, %s, %s, %d, %s, %d, %s, %s, NOW())', $projectId, q($title), q($category),
                          q("A $category project focused on practical layouts, natural light and durable finishes."),
                          q(pick($photos)), $designerId, q($status), $progress, q($isPast ? 'Past' : 'Current'), $created);
    $projectIds[] = $projectId++;
}

$shortlists = [];
$seen       = [];
while (count($shortlists) < 200) {
    $customerId = pick($customers);
    $project    = pick($projectIds);
    if (isset($seen["$customerId-$project"])) {
        continue;
    }
    $seen["$customerId-$project"] = true;
    $shortlists[] = sprintf('(%d, %d, %s)', $customerId, $project, ago(30 * 24 * 60));
}

$views = [];
for ($i = 0; $i < 600; $i++) {
    $views[] = sprintf('(%d, %s)', pick(array_merge($projectIds, [1, 2, 3, 4, 5])), ago(30 * 24 * 60));
}

$sql  = "-- CK test data: import AFTER interior_design.sql\n";
$sql .= "-- Generated by generate_data.php. Every generated user's password is: $password\n";
$sql .= "-- Usernames: cust001-cust100, des001-des100, pm001-pm005, ua001-ua005\n\n";
$sql .= "START TRANSACTION;\n\n";
$sql .= batch('useraccount', '`userId`, `username`, `password`, `email`, `status`', $accounts);
$sql .= batch('userprofile', '`username`, `password`, `name`, `type`, `status`', $profiles);
$sql .= batch('category', '`categoryID`, `categoryName`, `description`', $categories);
$sql .= batch('company', '`companyID`, `companyName`, `regNum`, `description`, `address`, `designerID`', $companies);
$sql .= batch('idp', '`projectID`, `title`, `category`, `description`, `photos`, `designerID`, `status`, `progress`, `stage`, `createdAt`, `updatedAt`', $projects);
$sql .= batch('shortlist', '`customerID`, `projectID`, `createdAt`', $shortlists);
$sql .= batch('project_views', '`projectID`, `viewedAt`', $views);
$sql .= "COMMIT;\n";

file_put_contents(__DIR__ . '/test_data.sql', $sql);

echo "test_data.sql written:\n";
printf("  %d user accounts and profiles\n  %d categories\n  %d companies\n  %d projects\n  %d shortlists\n  %d project views\n",
       count($accounts), count($categories), count($companies), count($projects), count($shortlists), count($views));
