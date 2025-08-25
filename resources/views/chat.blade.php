<html>
<head>
    <title>HRMS AI Chat</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f4f8;
            font-family: Arial, sans-serif;
        }
        .chat-container {
            max-width: 600px;
            margin: 60px auto;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        .chat-box {
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 15px;
            height: 400px;
            overflow-y: auto;
            margin-bottom: 20px;
            background-color: #fafafa;
        }
        .chat-msg {
            margin-bottom: 10px;
        }
        .user-msg {
            text-align: right;
        }
        .bot-msg {
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="chat-container">
        <h3 class="text-center mb-4">💬 Chat with HRMS Assistant</h3>

        <div id="chat-box" class="chat-box">
            
        </div>

        <div class="input-group">
            <input type="text" id="user-input" class="form-control" placeholder="Type your message...">
            <button class="btn btn-primary" onclick="sendMessage()">Send</button>
        </div>
    </div>

    <script>
        function sendMessage() {
            let input = document.getElementById('user-input');
            let message = input.value.trim();
            if (!message) return;

            let chatBox = document.getElementById('chat-box');
            chatBox.innerHTML += `<div class='chat-msg user-msg'><strong>You:</strong> ${message}</div>`;

            fetch('/chat/send', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ message: message })
            })
            .then(res => res.json())
            .then(data => {
                chatBox.innerHTML += `<div class='chat-msg bot-msg'><strong>Bot:</strong> ${data.reply}</div>`;
                input.value = '';
                chatBox.scrollTop = chatBox.scrollHeight;
            });
        }
    </script>
</body>
</html>
