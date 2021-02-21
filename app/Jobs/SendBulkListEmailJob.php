<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Http\Controllers\PusherNotification\PusherController;
use App\Http\Controllers\Message\MessageController;

class SendBulkListEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $message;
    protected $recipient_variables;
    protected $subject;
    protected $sender;
    protected $user;

    public $timeout = 0;

    private $message_progress = [

        'total' => 0,
        'success' => 0,
        'fails' => []
    ];

    public $backoff = 3;
    public $tries = 3;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $message, $recipients, $subject, $sender)
    {
        $this->message = $message;
        $this->recipients = $recipients;
        $this->subject = $subject;
        $this->sender = $sender;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $messageController = new MessageController();
        $pusher = new PusherController();
        $chunk_count = 0;

        $this->message_progress['total'] = ceil(count($this->recipient/1000);

        $dummy_recipient_variable = [
            $recipients[0] => ['email' => $recipients[0]]
        ];

        try{

            foreach(array_chunk($this->recipients, 1000, true) as $recipient){

                $chunk_count++;

                $mgClient = \Mailgun\Mailgun::create(config('constants.mailgun.secret'), config('constants.mailgun.base_url'));
                $domain = config('constants.mailgun.domain');
                $params =  array(
                    'from'    => $this->sender . ' <' . config('constants.mailgun.mail_from') . '>',
                    'to'      => $recipient,
                    'subject' => $this->subject,
                    'html'    => $this->message,
                    'recipient-variables' => json_encode($dummy_recipient_variable)
                );

                $result = $mgClient->messages()->send($domain, $params);

                $this->message_progress['success']++;
                $pusher->send_message_progress($this->user, $this->message_progress, 'list_email_batch', $this->job->getJobId());

                Log::info('Email sent to list batch ' . $chunk_count);  
            }

            try{

                $messageLog = [

                    'partner_id' => null,
                    'user_id' => $this->user,
                    'sender' => $this->sender,
                    'recipient' => json_encode($recipients),
                    'subject' => $this->subject,
                    'message' => strip_tags($this->message),
                    'status' => 'sent',

                ];

                $messageController->saveEmailMessageLog($messageLog);
            
                    
                }
                catch(Exception $e){
                    Log::info('Failed to save email log for list batch ' . $chunk_count);
                    Log::error('Cause: ' . $e->getMessage());
                }

        }
        catch(Exception $exception){
            array_push($this->message_progress['fails'], 'list_email_batch'.$chunk_count);
            $pusher->send_message_progress($this->user, $this->message_progress, 'list_email_batch', $this->job->getJobId());
            Log::error('Error sending email to list batch ' . $chunk_count);
            Log::error('Cause: ' . $exception->getMessage());
        }

    }
}
