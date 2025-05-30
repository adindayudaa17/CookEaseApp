<!DOCTYPE html>
<html>
<head>    <title>Cooking Assistant</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/markdown-it@13.0.1/dist/markdown-it.min.css">
    <script src="https://cdn.jsdelivr.net/npm/markdown-it@13.0.1/dist/markdown-it.min.js"></script>
    <style>
        .header {
            background: white;
            padding: 20px 40px; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            width: calc(100% - 80px); 
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #b73e3e;
            flex-shrink: 0;
        }

        .navbar {
            display: flex;
            gap: 25px;
            font-weight: normal;
            font-size: 16px;
        }

        .navbar a {
            text-decoration: none;
            color: #444;
            padding: 8px 12px;
            border-radius: 5px;
            transition: background-color 0.2s ease;
        }

        .navbar a:hover {
            background-color: #f0f0f0;
        }

        .navbar a.active {
            color: #b73e3e;
            font-weight: bold;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0; 
            padding: 0; 
            min-height: 100vh;
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
            margin: 20px auto; 
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
        }
        .bot .message-content code {
            background: #fecaca;
            padding: 2px 4px;
            border-radius: 3px;
            font-family: monospace;
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
<body>
    <div class="header">
        <div class="logo">CookEase</div>
        <nav class="navbar">
            <a href="/dashboard">Home</a>
            <a href="/favorites">Favorites</a>
            <a href="/rate-recipes">Rate Recipe</a>
            <a href="/chatbot" class="active">Cooking Ast</a>
            <a href="/settings">Settings</a>
        </nav>
    </div>   
     <div class="chat-container">        <div class="chat-header">
            <i class="fa-solid fa-utensils chef-icon"></i>
            <h2>Cooking Assistant</h2>
        </div>
        <div class="chat-messages" id="chatMessages">
            <div class="message bot">
                <div class="message-content">
                    Hello! I'm your cooking assistant. How can I help you with your culinary adventures today?
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

            // Add user message to chat
            addMessage(message, 'user');
            userInput.value = '';

            try {
                const response = await fetch('/chatbot/send', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify({ message })
                });

                const data = await response.json();
                addMessage(data.response, 'bot');
            } catch (error) {
                console.error('Error:', error);
                addMessage('Sorry, something went wrong.', 'bot');
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
