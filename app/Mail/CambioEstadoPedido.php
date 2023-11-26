<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CambioEstadoPedido extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $asunto;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $asunto)
    {
        $this->data = $data;
        $this->asunto = $asunto;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this->view('cambio-estado-pedido')
            ->from('confeccionesrocio9@gmail.com', 'Confecciones Rocío')
            ->subject($this->asunto);
    }
}
