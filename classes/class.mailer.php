<?
defined('_VALID') or die();

class mailer
{
    static function mail($to, $subject, $message)
    {
        $template = file_get_contents("templates/mail.php");
        $message = str_replace("!content", $message, $template);

        $headers = 'From: Info <info@domain.com>' . "\r\n";
        $headers .= "MIME-Version: 1.0". "\r\n";
        $headers .= "Content-Type: text/html; charset=utf-8" . "\r\n";

        return mail($to, $subject, $message, $headers);
    }
}