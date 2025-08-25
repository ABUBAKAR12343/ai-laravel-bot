<!DOCTYPE html>
<html>
<head>
    <title>AI Chat</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f0f2f5;
            font-family: 'Segoe UI', sans-serif;
        }
        .chat-container {
            max-width: 700px;
            margin: 50px auto;
            background: #fff;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.1);
        }
        .chat-box {
            height: 400px;
            overflow-y: auto;
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 15px;
            background-color: #fafafa;
        }
        .chat-msg {
            margin-bottom: 12px;
        }
        .user-msg {
            text-align: right;
            color: #333;
        }
        .bot-msg {
            text-align: left;
            color: #0d6efd;
        }
        .loader {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            animation: spin 1s linear infinite;
            display: inline-block;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>

<div class="chat-container">
    <h3 class="text-center mb-4">🤖 Conversational AI</h3>

    <div id="chat-box" class="chat-box mb-3">
        <!-- Chat messages will be added here -->
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
        chatBox.innerHTML += `<div class="chat-msg user-msg"><strong>You:</strong> ${message}</div>`;
        chatBox.innerHTML += `<div class="chat-msg bot-msg" id="loading"><strong>Bot:</strong> <span class="loader"></span></div>`;
        chatBox.scrollTop = chatBox.scrollHeight;
        input.value = '';

        // AJAX request to send message to backend
        fetch('/send-message', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ message: message })
        })
        .then(res => res.json())
        .then(data => {
            document.getElementById('loading').remove();
            chatBox.innerHTML += `<div class="chat-msg bot-msg"><strong>Bot:</strong> ${data.reply}</div>`;
            chatBox.scrollTop = chatBox.scrollHeight;
        })
        .catch(error => {
            document.getElementById('loading').remove();
            chatBox.innerHTML += `<div class="chat-msg bot-msg"><strong>Bot:</strong> Something went wrong!</div>`;
        });
    }
</script>

</body>
</html>
