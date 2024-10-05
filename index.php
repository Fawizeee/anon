
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anonymous Chat</title>

    <link rel="stylesheet" href="public/css/styles.css">

    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="#"><i class="bi bi-info-circle"></i> About</a></li>
                <li><a href="#"><i class="bi bi-question-circle"></i> FAQ</a></li>
                <li><a href="#"><i class="bi bi-envelope"></i> Contact</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section class="hero">
            <h1>Anonymous Chat</h1>
            <p>Connect with others without revealing your identity</p>
            <button><i class="bi bi-chat-left-text"></i> Start Chatting</button>
        </section>
        <section class="features">
            <h2>Features</h2>
            <ul>
                <li>
                    <i class="bi bi-lock"></i>
                    <p>End-to-end encryption ensures your conversations are private</p>
                </li>
                <li>
                    <i class="bi bi-incognito"></i>
                    <p>Remain anonymous and protect your identity</p>
                </li>
                <li>
                    <i class="bi bi-chat-square-text"></i>
                    <p>Engage in real-time conversations with others</p>
                </li>
            </ul>
        </section>
        <section class="how-it-works">
            <h2>How it Works</h2>
            <ol>
                <li>
                    <p><i class="bi bi-arrow-right-circle"></i> Click the "Start Chatting" button to begin</p>
                </li>
                <li>
                    <p><i class="bi bi-user-circle"></i> You'll be assigned a random username and connected to a chat room</p>
                </li>
                <li>
                    <p><i class="bi bi-chat-left-text"></i> Start chatting with others in the room, and react to their messages with our fun reaction system</p>
                </li>
            </ol>
            <p class="text-center">No account? <a href="/anon/signup" class="btn btn-primary btn-sm">Sign up now</a></p>
<p class="text-center">Already a member? <a href="/anon/login" class="btn btn-secondary btn-sm">Login now</a></p>
        </section>
    </main>
    <footer>
        <p>&copy; 2023 Anonymous Chat</p>
    </footer>
    <script src="script.js"></script>
</body>
</html>