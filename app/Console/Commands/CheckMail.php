<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpImap\Mailbox;

class CheckMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Проверка писем';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
//        env('MAIL_HOST')
//        env('MAIL_ENCRYPTION'),
//            env('MAIL_PORT')


//        $mailbox = new Mailbox(
//            "{" . env('MAIL_HOST') . ":" . env('MAIL_PORT') . "/imap/ssl}INBOX",
//            env('MAIL_USERNAME'),
//            env('MAIL_PASSWORD'),
//        );

        $mailbox = new Mailbox(
            "{imap.mail.ru:993/imap/ssl}INBOX",
            env('MAIL_USERNAME'),
            env('MAIL_PASSWORD'),
        );

        dd($mailbox->checkMailbox());

        // Получаем массив идентификаторов писем
        $mailsIds = $mailbox->searchMailbox('ALL');

        dd($mailsIds);

        if (empty($mailsIds)) {
            $this->info('Нет писем!');
        }

        $messages = [];
        foreach ($mailsIds as $id) {
            $message = $mailbox->getMail($id);
            $messages[] = [
                'subject' => $message->subject,
                'from'    => $message->fromAddress,
                'date'    => $message->date,
                'body'    => $message->textPlain, // Или используйте textHtml для HTML-сообщений
            ];
        }

        dd($messages);
    }
}
