<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/chatbot.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.9.4/css/bulma.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <title>RH RESOLVE+</title>
</head>

<body>

    <nav class="navbar" role="navigation" aria-label="main navigation">
        <div class="navbar-brand">
            <h4 id="logo" class="roboto-bold">RH RESOLVE+</h4>
            <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
            </a>
        </div>

        <div id="navbarBasicExample" class="navbar-menu">
            <div class="navbar-start">
                <a class="navbar-item roboto-regular">Início</a>
                <a class="navbar-item roboto-regular">ChatBot</a>
            </div>

            <div class="navbar-end">
                <div class="navbar-item">
                    <div class="buttons">
                        <a id="pfp" class="button is-primary">
                            <img src="img/account_circle_50dp_E8EAED_FILL0_wght400_GRAD0_opsz48.svg" alt="Profile Picture">
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </nav>

    <section class="section">
        <div class="container">
            <div class="columns">
                <div class="column is-half is-offset-0">
                    <h1 class="roboto-black">Mural<br>Informativo</h1>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="content has-text-centered">
            <p>
                <strong>DEV. Sistemas | 4° MOD.</strong> by <a href="https://www.instagram.com/maquessssss">Max</a>,<a href="https://www.instagram.com/ian_filgueiras"> Ian</a>,<a href="https://www.instagram.com/leoosilva9_"> Leonardo</a>.
                The source code is licensed
                <a href="https://opensource.org/license/mit">MIT</a>. The
                website content is licensed
                <a href="https://creativecommons.org/licenses/by-nc-sa/4.0//">CC BY NC SA 4.0</a>.
            </p>
        </div>
    </footer>

    <script src="{{ asset('js/chatbot.js') }}"></script>
</body>

</html>
