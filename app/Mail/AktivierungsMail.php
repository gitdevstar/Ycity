<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class AktivierungsMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if($this->data["type"] == "company"){
            $sva = storage_path('app/private/uploads/creators/'.Auth::id().'/sva.pdf');
            $info = storage_path('app/private/uploads/creators/'.Auth::id().'/info.pdf');

            return $this->subject("Bewerbung eines Creators erhalten")->view('emails.aktivierungsMail')->with('data', $this->data)
                ->attach($sva, [
                    'as' => 'SVA.pdf',
                    'mime' => 'application/pdf',
                ])->attach($info, [
                    'as' => 'creator_info.pdf',
                    'mime' => 'application/pdf',
                ]);
        } else {
            $info = storage_path('app/private/uploads/creators/'.Auth::id().'/info.pdf');
            return $this->subject("Bewerbung eines Creators erhalten")->view('emails.aktivierungsMail')->with('data', $this->data)
                ->attach($info, [
                    'as' => 'creator_info.pdf',
                    'mime' => 'application/pdf',
                ]);
        }

    }
}
