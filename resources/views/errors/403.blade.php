<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Forbidden Access | Maxumax</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800;900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/feather-icons"></script>
    <style>
        :root {
            --primary: #111827;
            --danger: #ef4444;
            --danger-dark: #991b1b;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Outfit', sans-serif;
            background-color: #fef2f2;
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            overflow: hidden;
        }

        .container {
            text-align: center;
            padding: 2.5rem;
            max-width: 600px;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border-radius: 2rem;
            border: 1px solid rgba(239, 68, 68, 0.2);
            box-shadow: 0 25px 50px -12px rgba(220, 38, 38, 0.15);
            position: relative;
            z-index: 10;
        }

        .red-card {
            width: 120px;
            height: 180px;
            background: var(--danger);
            border-radius: 1rem;
            margin: 0 auto 2.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 15px 30px rgba(239, 68, 68, 0.4);
            transform: rotate(-10deg);
            animation: card-entry 1s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        @keyframes card-entry {
            0% {
                transform: translateY(100px) rotate(30deg);
                opacity: 0;
            }

            100% {
                transform: translateY(0) rotate(-10deg);
                opacity: 1;
            }
        }

        .error-code {
            font-size: 3rem;
            font-weight: 900;
            color: white;
            margin: 0;
        }

        .title {
            font-size: 2.5rem;
            font-weight: 900;
            margin: 0 0 1rem;
            color: #991b1b;
            text-transform: uppercase;
            letter-spacing: -0.05em;
        }

        .subtitle {
            font-size: 1.125rem;
            color: #7f1d1d;
            margin-bottom: 2.5rem;
            line-height: 1.6;
            font-weight: 500;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem 2.5rem;
            background: var(--danger);
            color: white;
            text-decoration: none;
            border-radius: 1rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-size: 0.875rem;
            transition: all 0.3s;
            box-shadow: 0 10px 15px -3px rgba(239, 68, 68, 0.2);
        }

        .btn:hover {
            transform: scale(1.05);
            background: var(--danger-dark);
            box-shadow: 0 20px 25px -5px rgba(239, 68, 68, 0.3);
        }

        .referee-bg {
            position: absolute;
            bottom: -50px;
            right: -50px;
            font-size: 30rem;
            color: rgba(239, 68, 68, 0.03);
            z-index: -1;
            user-select: none;
        }
    </style>
</head>

<body>
    <div class="referee-bg">
        <i data-feather="slash"></i>
    </div>

    <div class="container">
        <div class="red-card">
            <span class="error-code">403</span>
        </div>
        <h1 class="title">RED CARD!</h1>
        <p class="subtitle">
            A foul has been detected. You don't have the required permissions to enter this area of the court.
        </p>
        <a href="/" class="btn">
            <i data-feather="rotate-ccw"></i>
            Walk Back to Safety
        </a>
    </div>

    <script>
        feather.replace();
    </script>
</body>

</html>