<!DOCTYPE html>
<html>
<head>    <title>Cooking Assistant</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/markdown-it@13.0.1/dist/markdown-it.min.css">
    <script src="https://cdn.jsdelivr.net/npm/markdown-it@13.0.1/dist/markdown-it.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .chat-container {
            width: 90%;
            max-width: 1200px;
            height: 80vh;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
        }
        .chat-header {
            background: #dc3545;
            color: white;
            padding: 20px;
            border-radius: 10px 10px 0 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .chef-icon {
            font-size: 24px;
        }
        .chat-messages {
            flex-grow: 1;
            padding: 20px;
            overflow-y: auto;
        }
        .message {
            margin-bottom: 15px;
            display: flex;
        }
        .message.user {
            justify-content: flex-end;
        }
        .message-content {
            max-width: 70%;
            padding: 10px 15px;
            border-radius: 15px;
        }
        .user .message-content {
            background: #dc3545;
            color: white;
            border-bottom-right-radius: 5px;
        }        .bot .message-content {
            background: #fee2e2;
            color: #450a0a;
            border-bottom-left-radius: 5px;
        }
        .bot .message-content strong,
        .bot .message-content b {
            color: #991b1b;
            font-weight: 600;
        }
        .bot .message-content em,
        .bot .message-content i {
            font-style: italic;
            color: #5c0f0f;
        }        .bot .message-content code {
            background: #fecaca;
            padding: 2px 4px;
            border-radius: 3px;
            font-family: monospace;
        }
        .typing {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .typing .dots {
            display: flex;
            gap: 4px;
        }
        .typing .dot {
            width: 8px;
            height: 8px;
            background: #dc3545;
            border-radius: 50%;
            animation: typing 1.4s infinite;
        }
        .typing .dot:nth-child(2) { animation-delay: 0.2s; }
        .typing .dot:nth-child(3) { animation-delay: 0.4s; }
        @keyframes typing {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        .chat-input {
            padding: 20px;
            border-top: 1px solid #dee2e6;
            display: flex;
            background: white;
        }
        .chat-input input {
            flex-grow: 1;
            padding: 12px;
            border: 2px solid #dc3545;
            border-radius: 20px;
            margin-right: 10px;
            font-size: 16px;
            transition: border-color 0.2s;
        }
        .chat-input input:focus {
            outline: none;
            border-color: #b91c1c;
        }
        .chat-input button {
            padding: 12px 25px;
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            transition: background 0.2s;
            font-size: 16px;
            font-weight: bold;
        }
        .chat-input button:hover {
            background: #b91c1c;
        }
    </style>
</head>
<body>    <div class="chat-container">        <div class="chat-header">
            <i class="fa-solid fa-utensils chef-icon"></i>
            <h2>Cooking Assistant</h2>
        </div>        <div class="chat-messages" id="chatMessages">
            <div class="message bot">
                <div class="message-content">
                    Halo! Saya adalah asisten memasak Anda. Bagaimana saya bisa membantu petualangan kuliner Anda hari ini? 😊
                </div>
            </div>
        </div>
        <form class="chat-input" id="chatForm">
            @csrf
            <input type="text" id="userInput" placeholder="Type your message..." required>
            <button type="submit">Send</button>
        </form>
    </div>

    <script>
        const chatForm = document.getElementById('chatForm');
        const chatMessages = document.getElementById('chatMessages');
        const userInput = document.getElementById('userInput');

        chatForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const message = userInput.value;
            if (!message.trim()) return;           
            addMessage(message, 'user');
            userInput.value = '';

            // Show typing indicator
            const typingDiv = document.createElement('div');
            typingDiv.className = 'message bot';
            typingDiv.innerHTML = `
                <div class="message-content typing">
                    <span>Cooking Assistant is typing</span>
                    <div class="dots">
                        <div class="dot"></div>
                        <div class="dot"></div>
                        <div class="dot"></div>
                    </div>
                </div>
            `;
            chatMessages.appendChild(typingDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;            try {
                const response = await fetch('/chatbot/send', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify({ message })
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                
                // Remove typing indicator before adding bot response
                const typingIndicator = chatMessages.querySelector('.typing').parentNode;
                if (typingIndicator) {
                    typingIndicator.remove();
                }

                if (data.response) {
                    addMessage(data.response, 'bot');
                } else {
                    throw new Error('No response data received');
                }
            } catch (error) {
                console.error('Error:', error);
                // Remove typing indicator if it exists
                const typingIndicator = chatMessages.querySelector('.typing')?.parentNode;
                if (typingIndicator) {
                    typingIndicator.remove();
                }
                addMessage('Maaf, terjadi kesalahan. Silakan coba lagi.', 'bot');
            }
        });        const md = window.markdownit();
          function addMessage(message, type) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `message ${type}`;
            
            let content = '';
            if (type === 'bot') {
                if (typeof message === 'object' && (message.markdown || message.text)) {
                    // Use markdown version if available, otherwise use plain text
                    content = message.markdown || message.text;
                } else if (typeof message === 'string') {
                    content = message;
                } else {
                    content = 'Sorry, I encountered an error in processing the response.';
                }
                // Convert markdown to HTML
                content = md.render(content);
            } else {
                content = message;
            }
            
            messageDiv.innerHTML = `
                <div class="message-content">
                    ${content}
                </div>
            `;
            chatMessages.appendChild(messageDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
    </script>
</body>
</html>
