<?php

namespace App\Mail;

use App\Models\WorkFlow\MailCenter;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RigMailCenterMessage extends Mailable
{
    use Queueable, SerializesModels;

    public $messageModel;
    public $renderedHtml;
    public $renderedText;

    public function __construct(MailCenter $messageModel, string $renderedHtml, string $renderedText = '')
    {
        $this->messageModel = $messageModel;
        $this->renderedHtml = $renderedHtml;
        $this->renderedText = $renderedText;
    }

    public function build()
    {
        $mail = $this->subject($this->messageModel->subject)
            ->view('mail.mailcenter')
            ->with([
                'renderedHtml' => $this->renderedHtml,
                'renderedText' => $this->renderedText,
                'messageModel' => $this->messageModel,
            ]);

        if ($this->messageModel->mailbox) {
            $mail->from(
                $this->messageModel->mailbox->from_email,
                $this->messageModel->mailbox->from_name
            );

            if ($this->messageModel->mailbox->reply_to_email) {
                $mail->replyTo(
                    $this->messageModel->mailbox->reply_to_email,
                    $this->messageModel->mailbox->reply_to_name ?: $this->messageModel->mailbox->from_name
                );
            }
        }

        foreach ($this->messageModel->attachments as $attachment) {
            $mail->attachFromStorageDisk(
                $attachment->disk ?: 'public',
                $attachment->path,
                $attachment->filename
            );
        }

        return $mail;
    }
}
