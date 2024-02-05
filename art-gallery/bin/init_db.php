#!/usr/bin/env php
<?php
$dsn = "pgsql:host=localhost;port=5432;dbname=art_gallery_db";
$dbConn = new PDO($dsn, 'postgres', '');

$dbConnect = pg_connect('host=localhost dbname=art_gallery_db user=postgres password=');
/**
 *  Импорт данных админа
 */
$dbConn->query('DELETE FROM admins');
$sqlData = json_decode(file_get_contents(__DIR__ . '/../data/admin.json'), true, 512, JSON_THROW_ON_ERROR);
foreach ($sqlData as $sqlItem) {
    $sql = <<<EOF
INSERT INTO admins(id, full_name, date_of_birth, telephone_number, login, password)
VALUES ({$sqlItem['id']}, '{$sqlItem['fullName']}', '{$sqlItem['dateOfBirth']}', 
        '{$sqlItem['telephoneNumber']}', '{$sqlItem['login']}', '{$sqlItem['password']}')
EOF;
    $dbConn->query($sql);
}

/**
 *  Импорт данных посетителя
 */
$dbConn->query('DELETE FROM visitors');
$sqlData = json_decode(file_get_contents(__DIR__ . '/../data/visitor.json'), true, 512, JSON_THROW_ON_ERROR);
foreach ($sqlData as $sqlItem) {
    $sql = <<<EOF
INSERT INTO visitors(id, full_name, date_of_birth, telephone_number)
VALUES ({$sqlItem['id']}, '{$sqlItem['fullName']}', '{$sqlItem['dateOfBirth']}', 
        '{$sqlItem['telephoneNumber']}')
EOF;
    $dbConn->query($sql);
}

/**
 *  Импорт данных художника
 */
$dbConn->query('DELETE FROM painters');
$sqlData = json_decode(file_get_contents(__DIR__ . '/../data/painter.json'), true, 512, JSON_THROW_ON_ERROR);
foreach ($sqlData as $sqlItem) {
    $dateOfBirth = $sqlItem['dateOfBirth'] ?? null;
    $telephoneNumber = $sqlItem['telephoneNumber'] ?? 'null';
    if (null !== $dateOfBirth) {
        $sql = <<<EOF
INSERT INTO painters(id, full_name, date_of_birth, telephone_number)
VALUES ({$sqlItem['id']}, '{$sqlItem['fullName']}', '$dateOfBirth', 
        $telephoneNumber)
EOF;
        $dbConn->query($sql);
    } else {
        $sql = <<<EOF
INSERT INTO painters(id, full_name, date_of_birth, telephone_number)
VALUES ({$sqlItem['id']}, '{$sqlItem['fullName']}', null, 
        $telephoneNumber)
EOF;
        $dbConn->query($sql);
    }
}

/**
 *  Импорт данных картины
 */
$dbConn->query('DELETE FROM pictures');
$sqlData = json_decode(file_get_contents(__DIR__ . '/../data/picture.json'), true, 512, JSON_THROW_ON_ERROR);
foreach ($sqlData as $sqlItem) {
    $sql = <<<EOF
INSERT INTO pictures(id, name, painter_id, year)
VALUES ({$sqlItem['id']}, '{$sqlItem['name']}', '{$sqlItem['painter_id']}', 
        '{$sqlItem['year']}/01/01')
EOF;
    $dbConn->query($sql);
}

/**
 *  Импорт данных галереи
 */
$dbConn->query('DELETE FROM galleries');
$sqlData = json_decode(file_get_contents(__DIR__ . '/../data/gallery.json'), true, 512, JSON_THROW_ON_ERROR);
foreach ($sqlData as $sqlItem) {
    $sql = <<<EOF
INSERT INTO galleries(id, name, address)
VALUES ({$sqlItem['id']}, '{$sqlItem['name']}', '{$sqlItem['address']}')
EOF;
    $dbConn->query($sql);
}

/**
 *  Импорт данных билета
 */
$dbConn->query('DELETE FROM tickets');
$sqlData = json_decode(file_get_contents(__DIR__ . '/../data/ticket.json'), true, 512, JSON_THROW_ON_ERROR);
foreach ($sqlData as $sqlItem) {
    $sql = <<<EOF
INSERT INTO tickets(id, gallery_id, date_of_visit, currency, cost)
VALUES ({$sqlItem['id']}, '{$sqlItem['gallery_id']}', '{$sqlItem['dateOfVisit']}', 
        '{$sqlItem['currency']}', {$sqlItem['cost']})
EOF;
    $dbConn->query($sql);
}

/**
 *  Импорт данных о покупке билета
 */
$dbConn->query('DELETE FROM ticket_purchase_reports');
$sqlData = json_decode(file_get_contents(__DIR__ . '/../data/ticketPurchaseReport.json'), true, 512, JSON_THROW_ON_ERROR);
foreach ($sqlData as $sqlItem) {
    $sql = <<<EOF
INSERT INTO ticket_purchase_reports(id, visitor_id, ticket_id, date_of_purchase, currency, cost)
VALUES ({$sqlItem['id']}, '{$sqlItem['visitor_id']}', '{$sqlItem['ticket_id']}', '{$sqlItem['purchasePrice']['dateOfPurchase']}', 
        '{$sqlItem['purchasePrice']['currency']}', {$sqlItem['purchasePrice']['cost']})
EOF;
    $dbConn->query($sql);
}

/**
 *  Импорт данных о покупке картины
 */
$dbConn->query('DELETE FROM picture_purchase_reports');
$sqlData = json_decode(file_get_contents(__DIR__ . '/../data/picturePurchaseReport.json'), true, 512, JSON_THROW_ON_ERROR);
foreach ($sqlData as $sqlItem) {
    $sql = <<<EOF
INSERT INTO picture_purchase_reports(id, visitor_id, picture_id, date_of_purchase, currency, cost)
VALUES ({$sqlItem['id']}, '{$sqlItem['visitor_id']}', '{$sqlItem['picture_id']}', '{$sqlItem['purchasePrice']['dateOfPurchase']}', 
        '{$sqlItem['purchasePrice']['currency']}', {$sqlItem['purchasePrice']['cost']})
EOF;
    $dbConn->query($sql);
}
