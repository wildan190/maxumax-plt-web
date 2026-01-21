<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>503 - Technical Timeout | Maxumax</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800;900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/feather-icons"></script>
    <style>
        :root {
            --primary: #111827;
            --info: #3b82f6;
            --info-dark: #1d4ed8;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Outfit', sans-serif;
            background-color: #f0f7ff;
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            overflow: hidden;
        }

        .container {
            text-align: center;
            padding: 3rem;
            max-width: 650px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 2.5rem;
            border: 1px solid rgba(59, 130, 246, 0.1);
            box-shadow: 0 30px 60px -12px rgba(59, 130, 246, 0.15);
            position: relative;
            z-index: 10;
        }

        .icon-box {
            width: 100px;
            height: 100px;
            background: #dbeafe;
            color: var(--info);
            border-radius: 2rem;
            margin: 0 auto 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: pulse-gear 3s infinite ease-in-out;
        }

        @keyframes pulse-gear {

            0%,
            100% {
                transform: scale(1) rotate(0deg);
                box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.4);
            }

            50% {
                transform: scale(1.1) rotate(180deg);
                box-shadow: 0 0 0 20px rgba(59, 130, 246, 0);
            }
        }

        .title {
            font-size: 2.75rem;
            font-weight: 900;
            margin: 0 0 1rem;
            color: #1e40af;
            text-transform: uppercase;
            letter-spacing: -0.05em;
        }

        .subtitle {
            font-size: 1.25rem;
            color: #3b82f6;
            margin-bottom: 2.5rem;
            line-height: 1.6;
            font-weight: 600;
        }

        .desc {
            font-size: 1rem;
            color: #64748b;
            margin-bottom: 2.5rem;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem 2.5rem;
            background: var(--info);
            color: white;
            text-decoration: none;
            border-radius: 1.25rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-size: 0.875rem;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
        }

        .btn:hover {
            transform: translateY(-5px);
            background: var(--info-dark);
            box-shadow: 0 15px 30px rgba(59, 130, 246, 0.3);
        }

        .grid-bg {
            position: absolute;
            width: 200%;
            height: 200%;
            background-image:
                linear-gradient(rgba(59, 130, 246, 0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(59, 130, 246, 0.05) 1px, transparent 1px);
            background-size: 50px 50px;
            z-index: -1;
            transform: rotate(15deg) translate(-25%, -25%);
        }
    </style>
</head>

<body>
    <div class="grid-bg"></div>

    <div class="container">
        <div class="icon-box">
            <i data-feather="tool" style="width: 50px; height: 50px;"></i>
        </div>
        <h1 class="title">TECHNICAL TIMEOUT</h1>
        <p class="subtitle">Our team is currently strategizing.</p>
        <p class="desc">
            We're performing some quick maintenance to improve your game experience. We'll be back on the field in just
            a moment.
        </p>
        <button onclick="window.location.reload()" class="btn">
            <i data-feather="refresh-cw"></i>
            Check If We're Back
        </button>
    </div>

    <script>
        feather.replace();
    </script>
</body>

</html>