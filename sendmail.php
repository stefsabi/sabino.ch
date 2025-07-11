<?php
header('Content-Type: application/json');

// Zieladresse für Kontaktformular
$to = 'family@sabino.ch';

// Formulareingaben
$name    = isset($_POST['name'])    ? trim($_POST['name'])    : '';
$email   = isset($_POST['email'])   ? trim($_POST['email'])   : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';
$nobot   = isset($_POST['nobot'])   ? trim($_POST['nobot'])   : '';

// Honeypot: Wenn ausgefüllt, ist es ein Bot
if ($nobot !== '') {
    echo json_encode(['success' => false, 'error' => 'Bot erkannt.']);
    exit;
}

// Validierung
if ($name === '' || $email === '' || $message === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'error' => 'Bitte alle Felder korrekt ausfüllen.']);
    exit;
}

// Mailinhalt
$subject = "Neue Kontaktanfrage von $name";
$body    = "Name: $name\nE-Mail: $email\n\nNachricht:\n$message";

// Mail-Header
$headers = "From: noreply@sabino.ch\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

// Mail senden
if (mail($to, $subject, $body, $headers)) {
    echo json_encode(['success' => true, 'name' => $name, 'email' => $email, 'message' => $message]);
} else {
    echo json_encode(['success' => false, 'error' => 'Mailversand fehlgeschlagen.']);
}