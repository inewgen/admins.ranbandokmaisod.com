<?php

class MailController extends BaseController
{

    public function sendMail()
    {
        // $from = 'no-reply@ranbandokmaisod.com';
        // $from = 'postmaster@ranbandokmaisod.com';

        $subject = 'Welcome New Members to ranbandokmaisod.com!';
        $from = 'no-reply@ranbandokmaisod.com';
        $to   = 'suraches2010@gmail.com';
        $name = 'Suraches See';
        $detail = 'คุณได้ทำการสมัครสมาชิกกับเว็บไซต์ ranbandokmaisod.com แล้วครับ คุณต้องทำการยืนยันการสมัครสมาชิกผ่านอีเมล คุณจึงจะสามารถใช้บริการจากทางเว็บไซต์ได้ครับ ยืนยันการสมัคร ';
        $detail .= '<a href="http://admins.ranbandokmaisod.dev/register/verify?email=suraches2010@gmail.com&token=xxx" style="color:#0099cc" target="_blank">คลิกที่นี่</a>';

        $data = array(
            'detail' => $detail,
            'name' => $name,
        );

        $user = array(
            'email' => $to,
            'name' => $name,
            'from' => $from,
            'subject' => $subject,
        );

        $sendmail = Mail::send('emails.register', $data, function ($message) use ($user) {
            $message->from($user['from'], 'ranbandokmaisod.com');
            $message->to($user['email'], $user['name'])->subject($user['subject']);
        });

        return 'Success';
    }
}
