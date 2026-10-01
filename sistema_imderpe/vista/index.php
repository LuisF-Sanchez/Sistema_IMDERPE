<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - IMDERPE</title>
    <link rel="stylesheet" href="../estilo/style1.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <img src="../estilo/borderizquierda.png" class="decoracion-esquina esquina-superior-izquierda" alt="Decoración Izquierda">
    <img src="../estilo/borderderecha.png" class="decoracion-esquina esquina-inferior-derecha" alt="Decoración Derecha">

    <canvas id="burbujasCanvas"></canvas>

    <div class="login-container">
        <div class="login-box" id="balatroCard">
            <div class="card-glare"></div>

            <h1 class="sistema-titulo">SISGAPDIM</h1>

            <div class="logo"></div>
            
            <h2>Acceso al Sistema</h2>

            <?php if (isset($_SESSION['error_login'])): ?>
                <div class="error-banner">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>
                        <?php 
                            echo $_SESSION['error_login']; 
                            unset($_SESSION['error_login']); 
                        ?>
                    </span>
                </div>
            <?php endif; ?>

            <form action="../controlador/iniciar_sesion.php" method="POST" id="loginForm">
                <div class="input-group">
                    <label for="email">Correo Electrónico</label>
                    <div class="input-wrapper">
                        <i class="fas fa-envelope input-icon"></i>
                        <input type="email" id="email" name="email" placeholder="ejemplo123@correo.com" required autocomplete="off">
                    </div>
                </div>
                
                <div class="input-group">
                    <label for="password">Contraseña</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" id="password" name="password" placeholder="••••••••" required>
                    </div>
                </div>
                
                <button type="submit" class="btn-login" id="btnSubmit">
                    <span class="btn-text">Entrar</span>
                    <i class="fas fa-circle-notch fa-spin btn-loader"></i>
                </button>
            </form>
            
            <footer>
                Instituto Autónomo de Deporte y Recreación del Municipio Peña
            </footer>
        </div>
    </div>

    <script>
    const canvas = document.getElementById('burbujasCanvas');
    const ctx = canvas.getContext('2d');

    function resizeCanvas() {
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
    }
    window.addEventListener('resize', resizeCanvas);
    resizeCanvas();

    class Burbuja {
        constructor() {
            this.reset();
        }

        reset() {
            this.x = Math.random() * canvas.width;
            this.y = canvas.height + Math.random() * 100;
            this.radius = Math.random() * 20 + 6;
            this.speed = Math.random() * 1.2 + 0.4;
            this.opacity = Math.random() * 0.35 + 0.15;
            this.oscillation = Math.random() * 0.02;
            this.angle = Math.random() * Math.PI * 2;
        }

        update() {
            this.y -= this.speed;
            this.angle += this.oscillation;
            this.x += Math.sin(this.angle) * 0.6;

            if (this.y + this.radius < 0) {
                this.reset();
            }
        }

        draw() {
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2);
            ctx.fillStyle = `rgba(255, 255, 255, ${this.opacity})`;
            ctx.fill();
            ctx.strokeStyle = `rgba(255, 255, 255, ${this.opacity + 0.2})`;
            ctx.lineWidth = 1;
            ctx.stroke();
        }
    }

    const burbujas = Array.from({ length: 35 }, () => new Burbuja());

    function animateBurbujas() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        burbujas.forEach(b => {
            b.update();
            b.draw();
        });
        requestAnimationFrame(animateBurbujas);
    }
    animateBurbujas();

    document.addEventListener('DOMContentLoaded', () => {
        const card = document.getElementById('balatroCard');
        setTimeout(() => {
            card.classList.add('revelar-formulario');
        }, 3000);
    });

    const card = document.getElementById('balatroCard');
    const glare = card.querySelector('.card-glare');

    card.addEventListener('mousemove', (e) => {
        if (!card.classList.contains('revelar-formulario')) return;

        const rect = card.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        const centerX = rect.width / 2;
        const centerY = rect.height / 2;

        const rotateX = -((y - centerY) / centerY) * 0.8;
        const rotateY = ((x - centerX) / centerX) * 0.8;

        card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale3d(1.005, 1.005, 1.005)`;

        const glareX = (x / rect.width) * 100;
        const glareY = (y / rect.height) * 100;
        glare.style.background = `radial-gradient(circle at ${glareX}% ${glareY}%, rgba(255,255,255,0.18) 0%, transparent 70%)`;
    });

    card.addEventListener('mouseleave', () => {
        if (!card.classList.contains('revelar-formulario')) return;
        card.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg) scale3d(1, 1, 1)';
        glare.style.background = 'none';
    });

    document.getElementById('loginForm').addEventListener('submit', function() {
        document.getElementById('btnSubmit').classList.add('loading');
    });

    window.addEventListener('pageshow', function() {
        document.getElementById('btnSubmit').classList.remove('loading');
    });
    </script>
</body>
</html>