<?php

namespace A17\Twill\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class Reset extends ResetPassword
{
    public function toMail($notifiable)
    {
        return (new MailMessage)->markdown('twill::emails.html.email', [
            'url' => url('https://' . config('twill.admin_app_url') . route('admin.password.reset.form', $this->token, false)),
            'actionText' => 'Restablecer la contraseña',
            'copy' => 'Has recibido este correo porque estás creando una nueva contraseña para tu cuenta o porque la has reseteado o la quieres recuperar. Si no has sido tu simplemente elimina el correo, y si has sido tú haz click en el siguiente botón.',
        ]);
    }
}
