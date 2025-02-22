<?php
// Définir les constantes
define('RECIPIENT_EMAIL', 'dupuyfrancois35@gmail.com');
define('MAX_MESSAGE_LENGTH', 1000);

// Fonction pour nettoyer et valider les données
function cleanInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Fonction pour valider l'email
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Fonction pour vérifier la longueur du message
function isValidMessageLength($message) {
    return strlen($message) <= MAX_MESSAGE_LENGTH;
}

// Fonction pour envoyer l'email
function sendEmail($to, $subject, $body, $headers) {
    try {
        if(mail($to, $subject, $body, $headers)) {
            header('Location: ../contact.html?status=success');
            exit();
        } else {
            throw new Exception("Échec de l'envoi de l'email");
        }
    } catch (Exception $e) {
        error_log($e->getMessage());
        header('Location: ../contact.html?status=error');
        exit();
    }
}

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header('Location: ../contact.html?status=error');
    exit();
}

// Récupérer et nettoyer les données
$name = cleanInput($_POST['name'] ?? '');
$email = cleanInput($_POST['email'] ?? '');
$subject = cleanInput($_POST['subject'] ?? '');
$message = cleanInput($_POST['message'] ?? '');

// Validation des données
$errors = [];

if(empty($name)) {
    $errors[] = "Le nom est requis";
}

if(empty($email) || !isValidEmail($email)) {
    $errors[] = "Email invalide";
}

if(empty($subject)) {
    $errors[] = "Le sujet est requis";
}

if(empty($message)) {
    $errors[] = "Le message est requis";
} elseif(!isValidMessageLength($message)) {
    $errors[] = "Le message est trop long";
}

// S'il y a des erreurs, rediriger avec un message d'erreur
if(!empty($errors)) {
    header('Location: ../contact.html?status=error&message=' . urlencode(implode(', ', $errors)));
    exit();
}

// Préparer l'email
$to = RECIPIENT_EMAIL;
$email_subject = "Nouveau message de contact: " . $subject;
$email_body = "Détails du message:\n\n";
$email_body .= "Nom: " . $name . "\n";
$email_body .= "Email: " . $email . "\n";
$email_body .= "Sujet: " . $subject . "\n\n";
$email_body .= "Message:\n" . $message;

$headers = "From: " . $email . "\r\n";
$headers .= "Reply-To: " . $email . "\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

// Envoyer l'email
sendEmail($to, $email_subject, $email_body, $headers);
?>
