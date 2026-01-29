<?php
require_once DIR . '/../models/ContactMessage.php';

class ContactController {
    public static function submit(): void {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $subject = trim($_POST['subject'] ?? '');
        $message = trim($_POST['message'] ?? '');

        $errors = [];

        if ($name === '') $errors[] = "Name is required";
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required";
        if ($message === '') $errors[] = "Message is required";

        if (!empty($errors)) {
            $_SESSION['contact_errors'] = $errors;
            $_SESSION['contact_old'] = ['name'=>$name,'email'=>$email,'subject'=>$subject,'message'=>$message];
            header('Location: ' . BASE_URL . '/contact.php');
            exit;
        }

        ContactMessage::create([
            'name' => $name,
            'email' => $email,
            'subject' => $subject,
            'message' => $message,
        ]);

        $_SESSION['contact_success'] = "Message sent!";
        header('Location: ' . BASE_URL . '/contact.php');
        exit;
    }
}
