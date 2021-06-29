<?php

namespace App;
use Illuminate\Database\Eloquent\Model;


class MailQueue extends Model
{

    protected $table = 'mail_queue';

    public static function createMail($mailto, $mailcc,$mailbcc,$subject,$content){
    	$mail = new MailQueue;

    	$mail->mailto = $mailto;
    	$mail->mailcc = $mailcc;
    	$mail->mailbcc = $mailbcc;
    	$mail->subject = $subject;
    	$mail->content = $content;
    	$mail->save();
    	return $mail;


    }

    
}