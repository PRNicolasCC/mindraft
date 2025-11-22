<?php
declare(strict_types=1);

// This library need to have the extension zip enabled
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require BASE_PATH . 'vendor/autoload.php';

class EmailService {
    static function sendEmailRecuperacion(string $email, string $token): void {

        $subject = "Recuperación de Contraseña - ". htmlspecialchars($_ENV['APP_NAME']);

        $enlace_recuperacion = htmlspecialchars($_ENV['DOMAIN'])."/password/reset/{$token}/{$email}";
        $message = '
    <!-- Contenido principal -->
    <div class="content">
    <div class="welcome-message">
        <h2>Solicitud de Cambio de Contraseña</h2>
    </div>

    <div class="message-text">
        <p>Hola,</p>
        <p>Hemos recibido una solicitud para <strong>restablecer la contraseña</strong> de tu cuenta en ' . htmlspecialchars($_ENV['APP_NAME']) . '.</p>
        <p>Si fuiste tú quien realizó esta solicitud, puedes cambiar tu contraseña haciendo clic en el botón de abajo.</p>
    </div>

    <div class="warning-box">
        <div class="warning-icon">⚠️</div>
        <p><strong>¡Importante!</strong> Este enlace expirará en 1 hora por seguridad. Si no cambiaste tu contraseña en ese tiempo, deberás solicitar un nuevo enlace.</p>
    </div>

    <div class="reset-section">
        <p class="reset-text">
            Para crear una nueva contraseña y recuperar el acceso a tu cuenta, haz clic en el siguiente botón:
        </p>

        <a href="' . $enlace_recuperacion . '" class="activation-button">✅ Cambiar mi contraseña</a>

        </div>

    <div class="info-box">
        <div class="info-icon">ℹ️</div>
        <p><strong>¿No solicitaste este cambio?</strong> Si no reconoces esta solicitud, puedes ignorar este correo. Tu contraseña actual permanecerá segura y no se realizará ningún cambio.</p>
    </div>

    <div class="security-tips">
        <h3>🛡️ Consejos para una contraseña segura:</h3>
        <ul>
            <li>Usa al menos 8 caracteres</li>
            <li>Combina letras mayúsculas y minúsculas</li>
            <li>Incluye números y símbolos especiales</li>
            <li>Evita usar información personal obvia</li>
            <li>No reutilices contraseñas de otras cuentas</li>
        </ul>
    </div>
</div>

<div class="footer">
    <p>Este enlace de restablecimiento expirará en 1 hora por tu seguridad.</p>
    <p>Si no solicitaste este cambio, tu cuenta permanece segura.</p>
    <p style="margin-top: 15px;">
        ¿Necesitas ayuda? <a href="mailto:' . htmlspecialchars($_ENV['RECIPIENT_EMAIL']) . '">Contáctanos</a>
    </p>
</div>
';


        $altMessage = "Haz clic en el botón para cambiar tu contraseña.";
        self::sendEmail($subject, $message, $altMessage, $email);
    }

    static function sendWelcomeEmail(string $email, string $token): void {
        $subject = "Activación de Cuenta - ".htmlspecialchars($_ENV['APP_NAME']);

        $enlace_activacion = htmlspecialchars($_ENV['DOMAIN'])."/user/activate/{$token}/{$email}";
        $message = '
    <div class="content">
        <div class="welcome-message">
            <h2>¡Bienvenido a ' . htmlspecialchars($_ENV['APP_NAME']) . '! 🎉</h2>
        </div>

        <div class="message-text">
            <p>Hola,</p>
            <p>¡Nos complace informarte que tu cuenta ha sido <strong>registrada exitosamente</strong> en nuestra aplicación de notas!</p>
            <p>Con ' . htmlspecialchars($_ENV['APP_NAME']) . ' podrás:</p>
            <ul style="margin: 15px 0; padding-left: 20px;">
                <li>Crear y organizar tus notas en diferentes cuadernos</li>
                <li>Buscar rápidamente cualquier información</li>
                <li>Mantener tus notas seguras y privadas</li>
            </ul>
        </div>

        <div class="warning-box">
            <div class="warning-icon">⚠️</div>
            <p><strong>¡Importante!</strong> Tu cuenta aún no está activa. Debes activarla para poder acceder a todas las funcionalidades de la aplicación.
            Tienes 24 horas a pertir del registro de la cuenta para completar la activación.</p>
        </div>

        <div class="activation-section">
            <p class="activation-text">
                Para completar tu registro y comenzar a usar ' . htmlspecialchars($_ENV['APP_NAME']) . ', haz clic en el siguiente botón:
            </p>

            <a href="' . $enlace_activacion . '" class="activation-button">✅ Activar mi cuenta</a>

        </div>
    </div>

    <div class="footer">
        <p>Si no solicitaste esta cuenta, puedes ignorar este correo.</p>
        <p style="margin-top: 15px;">
            ¿Necesitas ayuda? <a href="mailto:' . htmlspecialchars($_ENV['RECIPIENT_EMAIL']) . '">Contáctanos</a>
        </p>
    </div>
';

        $altMessage = "Tu usuario ha sido creado en ".htmlspecialchars($_ENV['APP_NAME']).". Activa tu cuenta haciendo clic en el enlace proporcionado.";
        self::sendEmail($subject, $message, $altMessage, $email);
    }


    private static function sendEmail(string $subject, string $message, string $altMessage, string $email): void {
        try {
            // Create a new PHPMailer instance
            $mail = new PHPMailer(true);
        
            $mail->CharSet = 'UTF-8';
            
            // Server settings
            $mail->isSMTP();                                    // Send using SMTP
            $mail->Host       = $_ENV['EMAIL_HOST'];               // Set the SMTP server
            $mail->SMTPAuth   = true;                           // Enable SMTP authentication
            $mail->Username   = $_ENV['SENDING_EMAIL'];         // SMTP username
            $mail->Password   = $_ENV['PASSWORD_SENDING_EMAIL'];           // SMTP password (use app password for Gmail)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
            $mail->Port       = $_ENV['EMAIL_PORT'];                            // TCP port to connect to
        
            // Recipients
            $mail->setFrom($_ENV['SENDING_EMAIL'], 'Nicolas Cueca');
            $mail->addAddress($email);
            #$mail->addAddress('another@example.com');                         // Name is optional
            $mail->addReplyTo($email, 'Nicolas Cueca');
        
            // Content
            $mail->isHTML(true);                               // Set email format to HTML

            $head_message = '<!DOCTYPE html>
            <html lang="es">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>`.$subject.`</title>
                <style>
                    /* Reset básico para compatibilidad con clientes de correo */
                    * {
                        margin: 0;
                        padding: 0;
                        box-sizing: border-box;
                    }

                    body {
                        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
                        background: linear-gradient(135deg, #FADF7D 0%, #FAB669 100%);
                        padding: 20px;
                        line-height: 1.6;
                    }

                    .email-container {
                        max-width: 600px;
                        margin: 0 auto;
                        background: white;
                        border: 1px solid black;
                        border-radius: 12px;
                        overflow: hidden;
                        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
                    }

                    .header {
                        background: linear-gradient(135deg, #FBB11E 0%, #FAEE7D 100%);
                        color: white;
                        padding: 30px;
                        text-align: center;
                    }

                    .header h1 {
                        font-size: 32px;
                        margin-bottom: 8px;
                        font-weight: 600;
                    }

                    .header p {
                        font-size: 16px;
                        opacity: 0.95;
                    }

                    .content {
                        padding: 40px 30px;
                        color: #0f172a;
                    }

                    .welcome-message {
                        text-align: center;
                        margin-bottom: 25px;
                    }

                    .welcome-message h2 {
                        color: #FBB11E;
                        font-size: 26px;
                        font-weight: 600;
                        margin-bottom: 15px;
                    }

                    .message-text {
                        color: #555;
                        font-size: 16px;
                        margin-bottom: 25px;
                        text-align: left;
                    }

                    .message-text p {
                        margin-bottom: 15px;
                    }

                    .warning-box {
                        background: #fff3cd;
                        border-left: 4px solid #ffc107;
                        padding: 20px;
                        margin: 25px 0;
                        border-radius: 6px;
                        display: flex;
                        align-items: flex-start;
                        gap: 12px;
                    }

                    .warning-icon {
                        font-size: 24px;
                        flex-shrink: 0;
                    }

                    .warning-box p {
                        margin: 0;
                        color: #856404;
                        font-size: 14px;
                        font-weight: 500;
                    }

                    .info-box {
                        background: #d1ecf1;
                        border-left: 4px solid #17a2b8;
                        padding: 20px;
                        margin: 25px 0;
                        border-radius: 6px;
                        display: flex;
                        align-items: flex-start;
                        gap: 12px;
                    }

                    .info-icon {
                        font-size: 24px;
                        flex-shrink: 0;
                    }

                    .info-box p {
                        margin: 0;
                        color: #0c5460;
                        font-size: 14px;
                    }

                    .activation-section,
                    .reset-section {
                        background: #f8f9fa;
                        text-align: center;
                        margin-top: 40px;
                        padding: 30px;
                        border-top: 2px solid #f0f0f0;
                        border-radius: 8px;
                    }

                    .activation-text,
                    .reset-text {
                        margin-bottom: 25px;
                        font-size: 16px;
                        color: #555;
                    }

                    .activation-button,
                    .reset-button {
                        display: inline-block;
                        background: linear-gradient(135deg, #FBB11E 0%, #FAEE7D 100%);
                        color: white !important;
                        padding: 16px 40px;
                        font-size: 18px;
                        font-weight: bold;
                        text-decoration: none;
                        border-radius: 50px;
                        border: none;
                        cursor: pointer;
                        box-shadow: 0 4px 15px rgba(251, 177, 30, 0.4);
                        transition: all 0.3s ease;
                    }

                    .activation-button:hover,
                    .reset-button:hover {
                        transform: translateY(-2px);
                        box-shadow: 0 6px 20px rgba(251, 177, 30, 0.6);
                    }

                    .security-tips {
                        background: #e7f3ff;
                        border-radius: 8px;
                        padding: 20px;
                        margin: 25px 0;
                    }

                    .security-tips h3 {
                        color: #0066cc;
                        font-size: 18px;
                        margin-bottom: 12px;
                        display: flex;
                        align-items: center;
                        gap: 8px;
                    }

                    .security-tips ul {
                        margin: 10px 0;
                        padding-left: 20px;
                        color: #555;
                        font-size: 14px;
                    }

                    .security-tips li {
                        margin: 8px 0;
                    }

                    .footer {
                        background: #f8f9fa;
                        padding: 25px 30px;
                        text-align: center;
                        color: #666;
                        font-size: 14px;
                        border-top: 1px solid #e9ecef;
                    }

                    .footer p {
                        margin: 8px 0;
                    }

                    .footer a {
                        color: #FBB11E;
                        text-decoration: none;
                        font-weight: 600;
                    }

                    .footer a:hover {
                        text-decoration: underline;
                    }

                    /* Compatibilidad con clientes de correo */
                    @media screen and (max-width: 600px) {
                        body {
                            padding: 10px;
                        }

                        .email-container {
                            margin: 0;
                            border-radius: 0;
                        }

                        .content {
                            padding: 25px 20px;
                        }

                        .header {
                            padding: 25px 20px;
                        }

                        .header h1 {
                            font-size: 26px;
                        }

                        .activation-button,
                        .reset-button {
                            padding: 14px 30px;
                            font-size: 16px;
                        }

                        .reset-section {
                            padding: 20px;
                        }
                    }
                </style>
            </head>
            <body>
            <div class="email-container">
                <!-- Header -->
                <div class="header">
                    <h1>🔐 '.htmlspecialchars($_ENV['APP_NAME']).'</h1>
                    <p>Tu aplicación personal de notas</p>
                </div>';

            $end_message = '</div></body></html>';

            $mail->Subject = $subject;
            $mail->Body    = $head_message . $message . $end_message;
            $mail->AltBody = $altMessage;
        
            $mail->send();
        } catch (Exception $e) {
            throw new Exception('No se pudo enviar el correo: ' . $e->getMessage());
            
        }
    }
}

?>