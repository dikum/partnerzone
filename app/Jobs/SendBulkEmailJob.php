<?php

namespace App\Jobs;

use App\Http\Controllers\Message\MessageController;
use App\Http\Controllers\Notification\NotificationController;
use App\Http\Controllers\PusherNotification\PusherController;
use App\Mail\SendMessageMailable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendBulkEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $message;
    protected $recipient_variables;
    protected $subject;
    protected $sender;
    protected $user_id;
    protected $username;

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
    public function __construct($user_id, $username, $message, $recipient_variables, $subject, $sender)
    {
        $this->message = $message;
        $this->recipient_variables = $recipient_variables;
        $this->subject = $subject;
        $this->sender = $sender;
        $this->user_id = $user_id;
        $this->username = $username;


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
        $this->message_progress['total'] = ceil(count($this->recipient_variables)/1000);

        foreach(array_chunk($this->recipient_variables, 1000, true) as $recipient_variable){

            $chunk_count++;
            try{

                $mgClient = \Mailgun\Mailgun::create(config('constants.mailgun.secret'), config('constants.mailgun.base_url'));
                $domain = config('constants.mailgun.domain');
                $params =  array(
                    'from'    => $this->sender . ' <' . config('constants.mailgun.mail_from') . '>',
                    'to'      => array_keys($recipient_variable),
                    'subject' => $this->subject,
                    'html'    => $this->message,
                    'recipient-variables' => json_encode($recipient_variable)
                );

                $result = $mgClient->messages()->send($domain, $params);

                try{

                    $messageLog = [

                        'partner_id' => null,
                        'username' => $this->username,
                        'sender' => $this->sender,
                        'recipient' => json_encode(array_keys($recipient_variable)),
                        'subject' => $this->subject,
                        'message' => strip_tags($this->message),
                        'status' => 'sent',

                    ];

                    $messageController->saveMessageLog($messageLog); 
                }
                catch(Exception $e){
                    Log::info('Failed to save email log for batch ' . $chunk_count);
                    Log::error('Cause: ' . $e->getMessage());
                }

                $this->message_progress['success']++;
                $pusher->send_message_progress($this->user_id, $this->message_progress, 'partner_email_batch', $this->job->getJobId());



                Log::info('Email sent to batch ' . $chunk_count);
            }
            catch(\Mailgun\Exception\HttpServerException $me){

                array_push($this->message_progress['fails'], 'partner-email-batch-'.$chunk_count);
                $pusher->send_message_progress($this->user_id, $this->message_progress, 'partner_email_batch', $this->job->getJobId());
                Log::error('Error sending email to batch ' . $chunk_count);
                Log::error('Cause: ' . $me->getMessage());
            }
            catch(Exception $exception){
                array_push($this->message_progress['fails'], 'partner-email-batch-'.$chunk_count);
                $pusher->send_message_progress($this->user_id, $this->message_progress, 'partner_email_batch', $this->job->getJobId());
                Log::error('Error sending email to batch ' . $chunk_count);
                Log::error('Cause: ' . $exception->getMessage());
            }
        }
    }
}
