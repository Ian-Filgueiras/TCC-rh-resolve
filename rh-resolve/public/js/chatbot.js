document.addEventListener('DOMContentLoaded', function () {

    appendMessage('Bot', 'Olá! Como posso ajudar você hoje? <br>1. Holerite <br>2. Benefícios <br>3. Descontos <br>4. Horas Extras<br>5. Férias<br>6. Atestados Médicos<br>7. Direitos e Deveres do Funcionário<br>8. Código de Conduta e Ética <br>9. Finalizar');

    // Adiciona o evento de clique ao botão de envio
    const sendButton = document.getElementById('send-button');
    sendButton.addEventListener('click', function() {
        // Adiciona a classe clicked
        sendButton.classList.add('clicked');

        // Remove a classe após a animação terminar
        setTimeout(() => {
            sendButton.classList.remove('clicked');
        }, 500); // 500ms corresponde ao tempo da animação
    });

    // Adiciona o evento de pressionar Enter ao campo de mensagem
    document.getElementById('message-input').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            sendMessage();
        }
    });
});

// Função para enviar a mensagem
function sendMessage() {
    let messageInput = document.getElementById('message-input');
    let message = messageInput.value.trim();

    if (message === '') return;

    appendMessage('Você', message);
    messageInput.value = '';

    fetch('/chatbot', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            message: message
        })
    })
    .then(response => response.json())
    .then(data => {
        appendMessage('Bot', data.response);
    })
    .catch(error => {
        console.error('Erro:', error);
        appendMessage('Bot', 'Desculpe, ocorreu um erro.');
    });
}

function appendMessage(sender, message) {
    let messagesContainer = document.getElementById('messages');

    let messageContainer = document.createElement('div');
    messageContainer.classList.add('message-container');

    let messageElement = document.createElement('div');
    messageElement.classList.add('message');

    if (sender === 'Bot') {
        messageElement.classList.add('message-bot', 'roboto-regular');
        messageContainer.classList.add('left');
    } else if (sender === 'Você') {
        messageElement.classList.add('message-usuario', 'roboto-regular');
        messageContainer.classList.add('right');
    }

    messageElement.innerHTML = `<strong>${sender}:</strong> ${message}`;
    messageContainer.appendChild(messageElement);
    messagesContainer.appendChild(messageContainer);
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}

