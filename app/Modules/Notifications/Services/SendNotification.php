<?php

namespace App\Modules\Notifications\Services;

use App\Modules\Notifications\Repository\NotificationRepository;
use App\Modules\Shared\Services\Response;
use DB;
use Validator;

class SendNotification
{
    private $repo;

    public function __construct(NotificationRepository $repo)
    {
        $this->repo = $repo;
    }

    public function __invoke($message): Response
    {
        $validator = Validator::make($message, [
            'app_id' => ['required', 'exists:padm_replogs_notification,app_id'],
            'subject' => ['required'],
            'message' => ['required']
        ]);
        if($validator->passes()){
            $notification = $this->repo->find($message['app_id']);
            // DB::statement("call sp_test_send_mail(:subject, :message)", [
            DB::statement("call sp_send_mail_notification(:subject, :message, :p_group)", [
                'subject' => $notification->pre_subject.' '.$message['subject'],
                'message' => $message['message']."\n".$notification->post_message,
                'p_group' => $notification->user_group
            ]);
            return new Response([]);
        }
        return new Response($validator->errors()->messages());
        
        // $message['var_fromalias'] = 'Portal regulatorio';
        // $message['var_fromuser'] = 'daniel.munante';
        // $message['var_touser'] = 'daniel.munante';
        // $message['var_group'] = 'TEST_ALARMA';
    }
}
