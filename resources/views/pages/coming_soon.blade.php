<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>House of KNP</title>
    <style>
        /* Reset and Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Outfit', 'Inter', sans-serif;
            background: url("{{ asset('images/flamingo-bg.png') }}") no-repeat center center/cover;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            position: relative;
        }

        /* Overlay to darken background for text readability */
        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5); /* Darken bg */
            z-index: 1;
        }

        /* Container */
        .container {
            text-align: center;
            z-index: 10;
            padding: 3rem;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
            animation: fadeIn 1.5s ease-out;

            /* More subtle glass effect */
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.05) 0%, rgba(255, 255, 255, 0.0) 100%);
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
            /* Remove harsh borders and shadows for a cleaner look */
        }

        /* Text Content */
        .brand-title {
            font-size: 2.5rem;
            font-weight: 500;
            letter-spacing: 0.5rem;
            text-transform: uppercase;
            color: #ffffff;
            margin-bottom: 0;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.8);
            opacity: 0; /* Star invisible */
            animation: slideDown 1s ease-out 0.5s forwards; /* Delay start */
        }

        .main-title {
            font-size: 7rem;
            font-weight: 800;
            letter-spacing: -2px;
            text-transform: uppercase;

            /* Gradient Shine Effect */
            background: linear-gradient(to right, #888888 0%, #ffffff 50%, #888888 100%);
            background-size: 200% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;

            margin: 0;
            line-height: 1;
            filter: drop-shadow(0px 4px 10px rgba(0,0,0,0.5));

            /* Animations: Entrance + Continuous Shine */
            animation: scaleIn 1.5s cubic-bezier(0.25, 1, 0.5, 1) forwards, shine 5s linear infinite;
        }

        .subtitle {
            font-size: 1.5rem;
            color: #ff6b6b; /* Accent color */
            margin-top: 1rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            font-weight: 600;
            text-shadow: 1px 1px 2px rgba(0,0,0,1);
        }

        /* Animations */
        @keyframes shine {
            to {
                background-position: 200% center;
            }
        }

        @keyframes scaleIn {
            0% {
                opacity: 0;
                transform: scale(0.9);
                letter-spacing: -20px; /* Dramatic spread start */
                filter: blur(10px);
            }
            100% {
                opacity: 1;
                transform: scale(1);
                letter-spacing: -2px; /* Final state */
                filter: blur(0px);
            }
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 0.9; transform: translateY(0); }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .main-title {
                font-size: 4rem;
            }
            .brand-title {
                font-size: 1.5rem;
                letter-spacing: 0.3rem;
            }
            .subtitle {
                font-size: 1rem;
            }
        }

        @media (max-width: 480px) {
            .main-title {
                font-size: 3rem;
            }
        }
    </style>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&family=Outfit:wght@500;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <div class="overlay"></div>
    <div class="container">
        <div class="content">
            <h2 class="brand-title">FLAMINGO</h2>
            <h1 class="main-title">COMING SOON</h1>
            <!-- <p class="subtitle">Global Logistics Redefined</p> -->
            <div class="loader"></div>
        </div>
    </div>
</body>
</html>
