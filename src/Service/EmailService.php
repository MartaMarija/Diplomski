<?php

namespace App\Service;

use Pimcore\Mail;
use Pimcore\Model\Document;

class EmailService
{
    public function sendEmail(Document $document, string $to, string $subject, array $params)
    {
        $mail = new Mail();

        $mail->to($to);
        $mail->subject($subject);
        $mail->setDocument($document);
        $mail->setParams($params);

        $mail->send();
    }
}
