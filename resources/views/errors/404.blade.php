<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Out of Bounds | Maxumax</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800;900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/feather-icons"></script>
    <style>
        :root {
            --primary: #111827;
            --accent: #3b82f6;
            --text-gray: #6b7280;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Outfit', sans-serif;
            background-color: #f9fafb;
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            overflow: hidden;
        }

        .container {
            text-align: center;
            padding: 2rem;
            max-width: 600px;
            position: relative;
            z-index: 10;
        }

        .error-code {
            font-size: 12rem;
            font-weight: 900;
            line-height: 1;
            margin: 0;
            background: linear-gradient(135deg, #111827 0%, #374151 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -0.05em;
            position: relative;
        }

        .error-code::after {
            content: '404';
            position: absolute;
            left: 0;
            top: 0;
            z-index: -1;
            text-shadow: 20px 20px 0px rgba(59, 130, 246, 0.05);
            -webkit-text-fill-color: initial;
            color: rgba(59, 130, 246, 0.05);
        }

        .title {
            font-size: 2.5rem;
            font-weight: 800;
            margin: 1rem 0 0.5rem;
            text-transform: uppercase;
            letter-spacing: -0.02em;
        }

        .subtitle {
            font-size: 1.125rem;
            color: var(--text-gray);
            margin-bottom: 2.5rem;
            line-height: 1.6;
        }

        .visual {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: -1;
            width: 100%;
            height: 100%;
            opacity: 0.3;
            pointer-events: none;
        }

        .field-line {
            position: absolute;
            width: 200vw;
            height: 2px;
            background: rgba(59, 130, 246, 0.2);
            top: 60%;
            left: -50vw;
            transform: rotate(-15deg);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem 2rem;
            background: var(--primary);
            color: white;
            text-decoration: none;
            border-radius: 1rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-size: 0.875rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .btn:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            background: #1f2937;
        }

        .ball {
            position: absolute;
            width: 60px;
            height: 60px;
            background: #fff;
            border: 2px solid #000;
            border-radius: 50%;
            bottom: 15%;
            right: 15%;
            animation: bounce 4s infinite ease-in-out;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            font-size: 0.75rem;
        }

        @keyframes bounce {

            0%,
            100% {
                transform: translateY(0) rotate(0);
            }

            50% {
                transform: translateY(-30px) rotate(180deg);
            }
        }
    </style>
</head>

<body>
    <div class="field-line"></div>

    <div class="container">
        <p class="error-code">404</p>
        <h1 class="title">OUT OF BOUNDS</h1>
        <p class="subtitle">
            It looks like you've dribbled too far. The page you're searching for is currently outside the field of play.
        </p>
        <a href="/" class="btn">
            <i data-feather="home"></i>
            Back to Headquarters
        </a>
    </div>

    <div class="ball">
        <i data-feather="target" style="width: 30px; height: 30px; color: #111827;"></i>
    </div>

    <script>
        feather.replace();
    </script>
</body>

</html>