<?
defined('_VALID') or die();

class mailerController extends controller
{
    var $templates = Array(
        'send' => Array('empty', 0)
    );

    function send()
    {
        $subject = TITLE;
        $to = getParam($_REQUEST, "to");
        $name = getParam($_REQUEST, "name");
        $email = getParam($_REQUEST, "email");
        $text = getParam($_REQUEST, "text");

        $text .= "<br/> Сообщение от " . $name . "(" . $email . ")";

        mailer::mail($to, $subject, $text);
    }
}