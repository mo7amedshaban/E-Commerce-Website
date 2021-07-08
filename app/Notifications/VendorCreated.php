<?php

namespace App\Notifications;

use App\Models\Vendor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VendorCreated extends Notification
{
    use Queueable;


    public $vendor;
    public function __construct(Vendor $vendor)
    {
       $this -> vendor =  $vendor;
    }

    public function via($notifiable)
    {
        return ['mail']; // or sms
    }
                            #fixed
    public function toMail($notifiable)
    {
                                                                # not use compact
        return (new MailMessage)->view('emails.notification',['vendor' => $this->vendor]);

        // $subject = sprintf('%s: لقد تم انشاء حسابكم في موقع الامامي %s!', config('app.name'), 'ahmed');
        // $greeting = sprintf('مرحبا %s!', $notifiable->name);

      //  return (new MailMessage)
            // ->subject($subject)
            // ->greeting($greeting)
            // ->salutation('Yours Faithfully')
            // ->line('The introduction to the notification.')
            // ->action('Notification Action', url('/'))
            // ->line('Thank you for using our application!');

    }


    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
