<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RecuperarClave extends Mailable
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
        return $this->view('enviarclave')
        ->from('confeccionesrocio9@gmail.com', 'CONFECCIONES ROCIO')
        ->subject('CLAVE DE ACCESO');
    }
}
