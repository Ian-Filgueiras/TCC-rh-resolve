document.getElementById('send-button').addEventListener('click', sendMessage);

document.getElementById('message-input').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        sendMessage();
    }
});

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
    let messageElement = document.createElement('div');
    messageElement.classList.add('message');
    messageElement.innerHTML = `<strong>${sender}:</strong> ${message}`;
    messagesContainer.appendChild(messageElement);
    messagesContainer.scrollTop = messagesContainer.scrollHeight; 
}

// Exibir a mensagem inicial quando a página carregar
document.addEventListener('DOMContentLoaded', function() {
    appendMessage('Bot', '1. Início com informações padrões automáticas<br>2. Perguntas pré-definidas para funcionários de empresas cadastradas<br>3. Falar com atendente (consultor do RH Resolve +)<br>4. Agendamento de horário presencial com o RH da empresa');
});
