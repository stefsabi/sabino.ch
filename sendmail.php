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
if (mb_strlen($message) < 20) {
    echo json_encode(['success' => false, 'error' => 'Die Nachricht muss mindestens 20 Zeichen lang sein.']);
    exit;
}

$subject = "Neue Kontaktanfrage von $name";

// HTML-Mail-Body
$body = '<html><head><style>
  body { font-family: Montserrat, Arial, sans-serif; background: #f8f8f8; color: #222; }
  .mail-container { background: #fff; border-radius: 8px; max-width: 500px; margin: 2rem auto; padding: 2rem; box-shadow: 0 4px 24px #0001; }
  .mail-header { color: #bfa046; font-size: 1.3rem; margin-bottom: 1.5rem; }
  .mail-label { color: #bfa046; font-weight: bold; }
  .mail-value { margin-bottom: 1rem; }
  .mail-logo { max-width: 120px; margin-bottom: 1.5rem; }
</style></head><body><div class="mail-container">';
$body .= '<img src="https://www.sabino.ch/img/sabino_print-logo1.png" alt="Sabino Wappen" class="mail-logo">';
$body .= '<div class="mail-header">Neue Kontaktanfrage über sabino.ch</div>';
$body .= '<div class="mail-value"><span class="mail-label">Name:</span> ' . htmlspecialchars($name) . '</div>';
$body .= '<div class="mail-value"><span class="mail-label">E-Mail:</span> ' . htmlspecialchars($email) . '</div>';
$body .= '<div class="mail-value"><span class="mail-label">Nachricht:</span><br>' . nl2br(htmlspecialchars($message)) . '</div>';
$body .= '<hr style="margin:2rem 0 1rem 0;border:0;border-top:1px solid #eee;">';
$body .= '<div style="font-size:0.9rem;color:#888;">Diese Nachricht wurde automatisch über das Kontaktformular von sabino.ch generiert.</div>';
$body .= '</div></body></html>';

// Mail-Header für HTML
$headers = "From: noreply@sabino.ch\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

// Mail senden
if (mail($to, $subject, $body, $headers)) {
    echo json_encode(['success' => true, 'name' => $name, 'email' => $email, 'message' => $message]);
} else {
    echo json_encode(['success' => false, 'error' => 'Mailversand fehlgeschlagen.']);
}