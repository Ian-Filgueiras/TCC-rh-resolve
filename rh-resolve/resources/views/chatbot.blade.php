<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/chatbot.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.9.4/css/bulma.min.css">
    <title>RH Resolve+ | ChatBot</title>
</head>

<body>
    <div class="fullscreen">
        <div id="chatbox" class="box">
            <div id="messages" class="box"></div>
            <div class="field is-grouped">
                <div class="control is-expanded">
                    <input type="text" id="message-input" class="input is-rounded" placeholder="Digite sua mensagem">
                </div>
                <div class="control">
                    <button id="send-button" class="button is-rounded">
                        <img src="img/send_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.svg">
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/chatbot.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>

</html>
