<?php

namespace App\Jobs;

use App\Http\Controllers\Message\MessageController;
use App\Mail\SendBulkMessageMailable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendBulkMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $request;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $messageController = new MessageController();
        Log::debug($this->request);
        $send_list = explode(',', $this->request->send_list);
        $table_data = $this->request->tableData;
        $message = $this->request->messageEditor;

        $from = env('MAIL_FROM_NAME', $request->sender);

        session(['bulkMessageListCount' => count($send_list) + count($table_data)]);
        Log::info('Total Message List Count: ' + session('bulkMessageListCount'));

        foreach($table_data as $row){

            $message = $messageController->replacePlaceHolder($row, $message);

            $emailBody = new SendBulkMessageMailable($message);

            Mail::to($row['Email'], $row['Name'])
            ->from($from)
            ->subject($request->subject)
            ->send($emailBody);

            //TODO Send SMS to $row['Phone']
            Log::info('Sending SMS to: ' . $row['Phone']);
            
        }

        foreach($send_list as $send_to){

            if(filter_var($send_to, FILTER_VALIDATE_EMAIL)){

                Mail::to($send_to)
                ->from($from)
                ->subject($request->subject)
                ->send($emailBody);
            }
            else{

                //TODO: Send SMS
                Log::info('Sending SMS from List to: ' . $send_to);
            }
        }

        Log::info('Job ID: ' . $this->job->getJobId());
    }
}
