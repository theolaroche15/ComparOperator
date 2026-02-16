<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/autoloader.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = trim($_POST['message'] ?? '');
    $author = trim($_POST['author'] ?? '');
    $tour_operator_id = (int)($_POST['tour_operator_id'] ?? 0);
    $stars = trim($_POST['stars'] ?? '');
    if ($message !== '' && $author !== '' && $tour_operator_id > 0 && $stars !== '') {
        $pdo = getPDO();
        $stmt = $pdo->prepare("INSERT INTO review (message, author, tour_operator_id, stars) VALUES (:message, :author, :tour_operator_id, :stars)");
        $stmt->execute([
            ':message' => $message,
            ':author' => $author,
            ':tour_operator_id' => $tour_operator_id,
            ':stars' => $stars
        ]);
    }
}
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit;
