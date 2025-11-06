<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to NR Bidding Store</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            overflow: hidden;
            height: 100vh;
            background: #000000;
        }

        /* Header Styles */
        .header {
            background: transparent;
            padding: 2rem 2rem 1rem;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo {
            font-size: 1.75rem;
            font-weight: 700;
            color: #ffffff;
            text-decoration: none;
            flex-shrink: 0;
        }

        .logo span {
            color: #f59e0b;
        }

        .nav-links {
            display: flex;
            gap: 3rem;
            list-style: none;
            margin: 0;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
        }

        .nav-links a {
            color: #ffffff;
            text-decoration: none;
            font-weight: 500;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            position: relative;
            padding: 0.5rem 0;
        }

        .nav-links a:hover {
            color: #f59e0b;
            transform: translateY(-2px);
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: #f59e0b;
            transition: width 0.3s ease;
        }

        .nav-links a:hover::after {
            width: 100%;
        }

        /* Main Content Styles */
        .starter-container {
            height: 100vh;
            background: #000000;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .content-wrapper {
            z-index: 2;
            max-width: 800px;
            padding: 2rem;
            animation: fadeInUp 1.5s ease-out;
        }

        .welcome-title {
            font-size: 4rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 1rem;
            text-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
            animation: slideInFromTop 1s ease-out;
        }

        .store-name {
            font-size: 2.5rem;
            font-weight: 600;
            color: #f59e0b;
            margin-bottom: 1.5rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
            animation: slideInFromLeft 1s ease-out 0.3s both;
        }

        .description {
            font-size: 1.25rem;
            color: #e5e7eb;
            margin-bottom: 3rem;
            line-height: 1.6;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
            animation: slideInFromRight 1s ease-out 0.6s both;
        }

        .explore-btn {
            display: inline-block;
            padding: 1rem 3rem;
            font-size: 1.125rem;
            font-weight: 600;
            color: #ffffff;
            background: linear-gradient(135deg, #f59e0b, #d97706);
            border: none;
            border-radius: 50px;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(245, 158, 11, 0.4);
            animation: pulse 2s infinite 1s, fadeIn 1s ease-out 0.9s both;
            position: relative;
            overflow: hidden;
        }

        .explore-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(245, 158, 11, 0.6);
            background: linear-gradient(135deg, #d97706, #b45309);
        }

        .explore-btn:active {
            transform: translateY(-1px);
        }

        .explore-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }

        .explore-btn:hover::before {
            left: 100%;
        }

        /* Modern Tech Background */
        .tech-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            filter: blur(2px);
            opacity: 0.7;
            pointer-events: none;
        }

        /* Circuit Board Grid */
        .circuit-grid {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                linear-gradient(90deg, transparent 99%, rgba(245, 158, 11, 0.1) 100%),
                linear-gradient(transparent 99%, rgba(245, 158, 11, 0.1) 100%);
            background-size: 50px 50px;
            animation: gridMove 20s linear infinite;
        }

        /* Digital Particles */
        .digital-particle {
            position: absolute;
            width: 2px;
            height: 2px;
            background: #f59e0b;
            border-radius: 50%;
            animation: particleFloat 6s ease-in-out infinite;
        }

        /* Binary Code Rain */
        .binary-rain {
            position: absolute;
            color: rgba(245, 158, 11, 0.3);
            font-family: 'Courier New', monospace;
            font-weight: bold;
            font-size: 0.875rem;
            animation: binaryFall 8s linear infinite;
        }

        /* Auction Radar */
        .auction-radar {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 400px;
            height: 400px;
            border: 1px solid rgba(245, 158, 11, 0.3);
            border-radius: 50%;
            animation: radarSpin 8s linear infinite;
        }

        .radar-sweep {
            position: absolute;
            top: 0;
            left: 50%;
            width: 2px;
            height: 50%;
            background: linear-gradient(to bottom, transparent, #f59e0b, transparent);
            transform-origin: bottom center;
            animation: radarSweep 4s linear infinite;
        }

        /* Bid Signal Dots */
        .bid-signal {
            position: absolute;
            width: 8px;
            height: 8px;
            background: #f59e0b;
            border-radius: 50%;
            animation: signalPulse 3s ease-in-out infinite;
        }

        /* Tech Wave */
        .tech-wave {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 100px;
            background: linear-gradient(transparent, rgba(245, 158, 11, 0.1));
            animation: waveMove 6s ease-in-out infinite;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInFromTop {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInFromLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideInFromRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                box-shadow: 0 8px 25px rgba(245, 158, 11, 0.4);
            }
            50% {
                transform: scale(1.05);
                box-shadow: 0 12px 35px rgba(245, 158, 11, 0.6);
            }
        }

        /* New Tech Animations */
        @keyframes gridMove {
            0% {
                transform: translate(0, 0);
            }
            100% {
                transform: translate(50px, 50px);
            }
        }

        @keyframes particleFloat {
            0%, 100% {
                transform: translateY(0) translateX(0);
                opacity: 0;
            }
            50% {
                opacity: 1;
            }
        }

        @keyframes binaryFall {
            0% {
                transform: translateY(-100px);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(100vh);
                opacity: 0;
            }
        }

        @keyframes radarSpin {
            0% {
                transform: translate(-50%, -50%) rotate(0deg);
            }
            100% {
                transform: translate(-50%, -50%) rotate(360deg);
            }
        }

        @keyframes radarSweep {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }

        @keyframes signalPulse {
            0%, 100% {
                transform: scale(1);
                opacity: 0.5;
            }
            50% {
                transform: scale(1.5);
                opacity: 1;
            }
        }

        @keyframes waveMove {
            0%, 100% {
                transform: translateX(0);
            }
            50% {
                transform: translateX(-20px);
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .welcome-title {
                font-size: 2.5rem;
            }

            .store-name {
                font-size: 2rem;
            }

            .description {
                font-size: 1.125rem;
            }

            .nav-container {
                flex-direction: column;
                gap: 1.5rem;
            }

            .nav-links {
                position: static;
                transform: none;
                gap: 2rem;
            }

            .logo {
                font-size: 1.5rem;
            }

            .header {
                padding: 1.5rem 1rem;
            }

            .auction-radar {
                width: 300px;
                height: 300px;
            }
        }

        @media (max-width: 480px) {
            .welcome-title {
                font-size: 2rem;
            }

            .nav-links {
                gap: 1.5rem;
            }

            .nav-links a {
                font-size: 1rem;
            }

            .logo {
                font-size: 1.25rem;
            }

            .store-name {
                font-size: 1.5rem;
            }

            .description {
                font-size: 1rem;
            }

            .explore-btn {
                padding: 0.875rem 2rem;
                font-size: 1rem;
            }

            .content-wrapper {
                padding: 1rem;
            }

            .auction-radar {
                width: 250px;
                height: 250px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <nav class="nav-container">
            <a href="{{ url('/') }}" class="logo">NR<span>Store</span></a>
            <ul class="nav-links">
                <li><a href="{{ url('/homepage') }}">Home</a></li>
                <li><a href="{{ url('/products') }}">Products</a></li>
                <li><a href="{{ url('/bid') }}">Bid</a></li>
                <li><a href="{{ url('/orders') }}">Orders</a></li>
            </ul>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="starter-container">
        <!-- Modern Tech Background -->
        <div class="tech-background">
            <!-- Circuit Board Grid -->
            <div class="circuit-grid"></div>
            
            <!-- Auction Radar -->
            <div class="auction-radar">
                <div class="radar-sweep"></div>
            </div>
            
            <!-- Tech Wave -->
            <div class="tech-wave"></div>
        </div>

        <!-- Digital Elements Container -->
        <div id="digitalElements"></div>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <h1 class="welcome-title">Welcome to NR Bidding Store</h1>
            <h2 class="store-name">NR Bidding Store</h2>
            <h4 class="description">
                Experience the next generation of live bidding technology. 
                Compete for premium electronics in our cutting-edge auction platform 
                with real-time updates and seamless bidding experience.
            </h4>
            <a href="{{ url('/homepage') }}" class="explore-btn">
                Start Bidding <i class="fas fa-bolt" style="margin-left: 8px;"></i>
            </a>
        </div>
    </main>

    <script>
        // Create digital particles
        function createDigitalParticles() {
            const container = document.getElementById('digitalElements');
            const particleCount = 50;

            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.classList.add('digital-particle');
                
                const left = Math.random() * 100;
                const top = Math.random() * 100;
                const delay = Math.random() * 6;
                const duration = Math.random() * 4 + 4;
                
                particle.style.left = `${left}%`;
                particle.style.top = `${top}%`;
                particle.style.animationDelay = `${delay}s`;
                particle.style.animationDuration = `${duration}s`;
                
                container.appendChild(particle);
            }
        }

        // Create binary code rain
        function createBinaryRain() {
            const container = document.getElementById('digitalElements');
            const binaryCount = 20;

            for (let i = 0; i < binaryCount; i++) {
                const binary = document.createElement('div');
                binary.classList.add('binary-rain');
                
                const left = Math.random() * 100;
                const delay = Math.random() * 8;
                const duration = Math.random() * 4 + 6;
                const binaryText = Math.random() > 0.5 ? '1' : '0';
                
                binary.textContent = binaryText.repeat(8 + Math.floor(Math.random() * 12));
                binary.style.left = `${left}%`;
                binary.style.animationDelay = `${delay}s`;
                binary.style.animationDuration = `${duration}s`;
                
                container.appendChild(binary);
            }
        }

        // Create bid signal dots
        function createBidSignals() {
            const container = document.getElementById('digitalElements');
            const signalCount = 12;

            for (let i = 0; i < signalCount; i++) {
                const signal = document.createElement('div');
                signal.classList.add('bid-signal');
                
                const left = Math.random() * 100;
                const top = Math.random() * 100;
                const delay = Math.random() * 3;
                const duration = Math.random() * 2 + 2;
                
                signal.style.left = `${left}%`;
                signal.style.top = `${top}%`;
                signal.style.animationDelay = `${delay}s`;
                signal.style.animationDuration = `${duration}s`;
                
                container.appendChild(signal);
            }
        }

        // Initialize when page loads
        document.addEventListener('DOMContentLoaded', function() {
            createDigitalParticles();
            createBinaryRain();
            createBidSignals();
            
            // Add click animation to explore button
            const exploreBtn = document.querySelector('.explore-btn');
            
            exploreBtn.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-3px) scale(1.05)';
            });
            
            exploreBtn.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
            
            // Add tech-themed click effect
            exploreBtn.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Create tech effect
                const techEffect = document.createElement('div');
                techEffect.style.cssText = `
                    position: fixed;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    font-size: 3rem;
                    font-weight: bold;
                    color: #f59e0b;
                    z-index: 10000;
                    animation: techPop 1s ease-out forwards;
                    text-shadow: 0 0 20px rgba(245, 158, 11, 0.8);
                `;
                techEffect.textContent = 'SYSTEM ONLINE';
                
                document.body.appendChild(techEffect);
                
                // Add CSS for tech pop animation
                const style = document.createElement('style');
                style.textContent = `
                    @keyframes techPop {
                        0% {
                            opacity: 0;
                            transform: translate(-50%, -50%) scale(0.5);
                            filter: blur(10px);
                        }
                        50% {
                            opacity: 1;
                            transform: translate(-50%, -50%) scale(1.2);
                            filter: blur(0px);
                        }
                        100% {
                            opacity: 0;
                            transform: translate(-50%, -50%) scale(1);
                            filter: blur(5px);
                        }
                    }
                `;
                document.head.appendChild(style);
                
                // Navigate after animation
                setTimeout(() => {
                    window.location.href = this.href;
                }, 1000);
            });
        });
    </script>
</body>
</html>