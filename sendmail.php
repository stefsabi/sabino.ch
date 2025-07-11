<?php
// Prüfe, ob es ein AJAX-Request ist
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

header('Vary: X-Requested-With');

// Empfänger-Adresse
$to = 'family@sabino.ch';

// POST-Daten auslesen
$name    = isset($_POST['name'])    ? trim($_POST['name'])    : '';
$email   = isset($_POST['email'])   ? trim($_POST['email'])   : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';
$nobot   = isset($_POST['nobot'])   ? trim($_POST['nobot'])   : '';

// Honeypot: Wenn ausgefüllt, Abbruch
if ($nobot !== '') {
    if ($isAjax) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Bot erkannt.']);
    } else {
        echo '<h2>Bot erkannt.</h2>';
    }
    exit;
}

// Validierung
if ($name === '' || $email === '' || $message === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    if ($isAjax) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Bitte alle Felder korrekt ausfüllen.']);
    } else {
        echo '<h2>Bitte alle Felder korrekt ausfüllen.</h2>';
    }
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
    if ($isAjax) {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'name' => $name,
            'email' => $email,
            'message' => $message
        ]);
    } else {
        echo '<h2>Danke für deine Nachricht!</h2><p>Wir melden uns so schnell wie möglich.</p>';
    }
} else {
    if ($isAjax) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Mailversand fehlgeschlagen.']);
    } else {
        echo '<h2>Fehler beim Versand.</h2><p>Bitte versuche es später erneut.</p>';
    }
} 