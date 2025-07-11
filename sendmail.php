<?php
// Empfänger-Adresse
$to = 'family@sabino.ch';

// POST-Daten auslesen
$name    = isset($_POST['name'])    ? trim($_POST['name'])    : '';
$email   = isset($_POST['email'])   ? trim($_POST['email'])   : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';
$nobot   = isset($_POST['nobot'])   ? trim($_POST['nobot'])   : '';

// Honeypot: Wenn ausgefüllt, Abbruch
if ($nobot !== '') {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Bot erkannt.']);
    exit;
}

// Validierung
if ($name === '' || $email === '' || $message === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Bitte alle Felder korrekt ausfüllen.']);
    exit;
}

// Mail-Header
$subject = "Neue Kontaktanfrage von $name";
$headers = "From: $name <$email>\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

// Mail-Text
$body = "Name: $name\nE-Mail: $email\n\nNachricht:\n$message\n";

// Mail senden
if (mail($to, $subject, $body, $headers)) {
    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
} else {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Mailversand fehlgeschlagen.']);
} 