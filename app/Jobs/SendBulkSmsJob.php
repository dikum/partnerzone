<?php

namespace App\Jobs;

use App\Http\Controllers\Message\MessageController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Jobs\SendSmsJob;
use App\Http\Controllers\PusherNotification\PusherController;
use Illuminate\Support\Facades\Log;

class SendBulkSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user_id;
    protected $username;
    protected $message;
    protected $sender;
    protected $tableData;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user_id, $username, $tableData, $message, $sender)
    {
        $this->user_id = $user_id;
        $this->username = $username;
        $this->tableData = $tableData;
        $this->message = $message;
        $this->sender = $sender;
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
        $messageLog = [

            'partner_id' => null,
            'username' => $this->username,
            'sender' => $this->sender,
            'subject' => null,
            'recipient' => [],
            'message' => '',
            'status' => 'sent',

        ];

        $message_progress = [

            'total' => count($this->tableData),
            'success' => 0,
            'fails' => []
        ];


        foreach($this->tableData as $row){
            
            if(isset($row[4])){

                array_push($messageLog['recipient'], $row[4]);

                $messageBody = $messageController->replacePlaceHolders(true, 'sms', $row, $this->message);

                try{
                    Log::info('Sending sms to: ' . $row[4]);
                    if(SendSmsJob::dispatch($row[4], $messageBody)){

                        $message_progress['success']++;
                        $pusher->send_message_progress($this->user_id, $message_progress, 'partner_sms', $this->job->getJobId());
                    }
                    else{
                        array_push($message_progress['fails'], $this->phone);
                        $pusher->send_message_progress($this->user_id, $message_progress, 'partner_sms', $this->job->getJobId());
                        Log::info('SMS sending failed ' . $row[4]);
                    }
                    
                }
                catch(Exception $e){
                    array_push($message_progress['fails'], $this->phone);
                    $pusher->send_message_progress($this->user_id, $message_progress, 'partner_sms', $this->job->getJobId());
                    Log::error('Failed to send sms to: ' . $row[4]);
                    Log::error('Cause: ' . $e->getMessage());
                }       
            }   
        }

        try{
            $messageLog['recipient'] = json_encode($messageLog['recipient']);
            $messageLog['message'] = $messageBody;

            $messageController->saveMessageLog($messageLog);

        }
        catch(Exception $e){

            Log::info('Failed to save sms log');
            Log::error('Cause: ' . $e->getMessage());
        }
    }
}
