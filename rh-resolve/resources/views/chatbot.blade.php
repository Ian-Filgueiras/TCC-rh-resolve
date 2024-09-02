<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/chatbot.css') }}">
    <title>Chatbot</title>
</head>
<body>
    <div id="chatbox">
        <div id="messages"></div>
        <input type="text" id="message-input" placeholder="Digite sua mensagem">
        <button id="send-button">Enviar</button>
    </div>
    <script src="{{ asset('js/chatbot.js') }}"></script>
</body>
</html>