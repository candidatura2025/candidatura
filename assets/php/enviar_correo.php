<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/Exception.php';
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';

// Configuración de la base de datos
$servername = "localhost";
$username = "root";  
$password = "";      
$dbname = "elecciones";

// Conectar a la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Recibir los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = htmlspecialchars($_POST["nombre"]);
    $asunto = htmlspecialchars($_POST["asunto"]);
    $mensaje = htmlspecialchars($_POST["mensaje"]);

    // Guardar en la base de datos
    $sql = "INSERT INTO mensajes (nombre, asunto, mensaje, fecha) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $nombre, $asunto, $mensaje);
    $stmt->execute();
    $stmt->close();

    // Enviar el correo con PHPMailer
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'tu-email@gmail.com';
        $mail->Password = 'tu-contraseña-de-app'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('tu-email@gmail.com', 'Formulario Elecciones');
        $mail->addAddress('candidaturaicacs2025@gmail.com');

        $mail->Subject = $asunto;
        $mail->Body = "Nombre: $nombre\n\nMensaje:\n$mensaje";

        $mail->send();
        echo "Correo enviado con éxito.";
    } catch (Exception $e) {
        echo "Error al enviar el correo: {$mail->ErrorInfo}";
    }
}
$conn->close();
?>


