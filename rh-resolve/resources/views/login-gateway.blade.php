<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/login-gateway.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>RH Resolve+</title>
</head>

<body>
<div class="wrapper">
  <div class="login-text">
    <button class="cta"><i class="fas fa-chevron-down fa-1x"></i></button>
    <div class="text">
      <a href="">Já é daqui? Bem-vindo de Volta!</a>
      <hr>
      <br>
      <input type="email" class="form-control" placeholder="Email">
      <br>
      <input type="password" class="form-control" placeholder="Senha">
      <br>
      <button class="btn btn-primary login-btn">Entrar</button>
    </div>
  </div>
  <div class="call-text">
    <h1><span>RH Resolve+</span> Praticidade e clareza no seu suporte, como essa tela.</h1>
    <button class="btn btn-success">Vamos Começar!</button>
  </div>
</div>

<script src="{{ asset('js/login-gateway.js') }}"></script>
</body>
</html>
