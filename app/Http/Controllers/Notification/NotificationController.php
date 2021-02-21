<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ControllerHelper\PostCaller;
use App\Http\Controllers\Notification\NotificationController;
use App\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'title' => 'required',
            'notification' => 'required',
            'user_id' => 'nullable',
            'job_id' => 'nullable',
        ];

        $this->validate($request, $rules);

        $data = $request->all();

        $data['read'] = false;

        $notification = Notification::create($data);

        return response($notification, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function get_new_notifications(){

        $notifications = Notification::
                    where('user_id', session('user')['user_id'])
                    ->orWhere('user_id', null)
                    ->where('read', false)
                    ->get();

        return response()->json(['notifications' => $notifications]);
    }


    public function save_message_notification($job_id){

        Log::debug('Saving Notification');

        $notification = new PostCaller(
            NotificationController::class,
            'store',
            Request::class,
            [
                'title' => 'Sending Message',
                'notification' => "Sending <div id='messages_sent'>0</div> of <div id='message_total'>0</div> ",
                'user_id' => session('user')['user_id'],
                'job_id' => $job_id,
            ]
        );

        $response = $notification->call();
    }
}
