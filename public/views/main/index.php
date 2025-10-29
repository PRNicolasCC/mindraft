<?php
/* if (!isset($_SESSION['user_moni'])) {
    header("location: ../index.php");
} */
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="static/css/bootstrap.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="static/bootstrap-icons-1.13.1/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color:rgb(0, 247, 255);
            --gradient-bg: linear-gradient(135deg, #1a1a1a 0%, #2d4059 100%);
        }

        body {
            background: var(--gradient-bg);
            min-height: 100vh;
            font-family: 'Poppins', sans-serif;
            color: white;
            display: flex;
            flex-direction: column;
        }

        main.container {
            flex-grow: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .glass-container {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            padding: 3rem;
            text-align: center;
            width: 100%;
        }

        .welcome-title {
            font-size: 2.4rem;
            font-weight: 600;
            color: var(--primary-color);
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            animation: zoomIn 1.2s ease-out both;
        }

        .welcome-subtitle {
            font-size: 1.2rem;
            color: rgba(255, 255, 255, 0.85);
            margin-top: 1rem;
            font-weight: bold;
            min-height: 1.5em;
            display: inline-block;
        }

        /* Animaciones */
        @keyframes zoomIn {
            0% {
                transform: scale(0.8);
                opacity: 0;
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .fade-in-letter {
            opacity: 0;
            display: inline-block;
            animation: fadeLetter 0.6s forwards;
        }

        @keyframes fadeLetter {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        footer {
            background-color: rgba(0, 0, 0, 0.7);
            text-align: center;
            padding: 10px 15px;
            font-size: 0.8rem;
            color: #ccc;
        }

        .btn-custom {
            background-color: var(--primary-color);
            color: black;
            border: none;
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-custom:hover {
            background-color: #00e67a;
            box-shadow: 0 0 10px rgba(0, 255, 136, 0.5);
        }

        @media (max-width: 576px) {
            .glass-container {
                padding: 1.5rem;
            }

            .welcome-title {
                font-size: 1.8rem;
            }

            .welcome-subtitle {
                font-size: 1rem;
            }

            main.container {
                padding-top: 1rem;
                padding-bottom: 1rem;
            }
        }
    </style>
</head>

<body>

    <?php include_once 'layouts/navbar.php'; ?>

    <main class="container">
        <div class="row justify-content-center w-100">
            <div class="col-md-8 col-lg-6">
                <div class="glass-container">
                    <h1 class="welcome-title">¡Bienvenido!</h1>
                    <p class="welcome-subtitle" id="typing-element"></p>
                </div>
            </div>
        </div>
    </main>

    <?php #include_once 'views/layouts/footer.php'; ?>

    <!-- Bootstrap JS -->
    <script src="static/js/bootstrap.bundle.min.js"></script>

    <!-- Animación de escritura moderna -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const nombre = "<?= htmlspecialchars($nombre); ?>";
            const typingElement = document.getElementById('typing-element');
            typingElement.innerHTML = ''; // Limpia el contenido previo

            [...nombre].forEach((char, index) => {
                const span = document.createElement('span');
                span.textContent = char === ' ' ? '\u00A0' : char;
                span.classList.add('fade-in-letter');
                span.style.animationDelay = `${index * 0.1}s`;
                typingElement.appendChild(span);
            });
        });
    </script>
</body>
</html>
