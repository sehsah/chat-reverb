<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Chat Application') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css'])
    @vite(['resources/js/app.js'])

    <style>
        :root {
            --chat-primary: #0D6EFD;
            --chat-secondary: #6C757D;
            --chat-success: #198754;
            --chat-bg: #f8f9fa;
            --chat-sidebar: #ffffff;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--chat-bg);
            height: 100vh;
            margin: 0;
        }

        .chat-container {
            height: calc(100vh - 56px);
            margin-top: 56px;
        }

        .chat-sidebar {
            background: var(--chat-sidebar);
            border-right: 1px solid rgba(0,0,0,.1);
            height: 100%;
            overflow-y: auto;
        }

        .chat-messages {
            height: calc(100vh - 170px);
            overflow-y: auto;
            padding: 1rem;
        }

        .chat-input {
            border-top: 1px solid rgba(0,0,0,.1);
            padding: 1rem;
            background: white;
        }

        .conversation-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .conversation-item {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid rgba(0,0,0,.05);
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .conversation-item:hover {
            background-color: rgba(13, 110, 253, 0.05);
        }

        .conversation-item.active {
            background-color: rgba(13, 110, 253, 0.1);
        }

        .message {
            margin-bottom: 1rem;
            max-width: 80%;
        }

        .message-sent {
            margin-left: auto;
        }

        .message-received {
            margin-right: auto;
        }

        .message-content {
            padding: 0.5rem 1rem;
            border-radius: 1rem;
            display: inline-block;
        }

        .message-sent .message-content {
            background-color: var(--chat-primary);
            color: white;
            border-top-right-radius: 0.25rem;
        }

        .message-received .message-content {
            background-color: #e9ecef;
            border-top-left-radius: 0.25rem;
        }

        .navbar-brand {
            font-weight: 600;
        }

        .user-status {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 5px;
        }

        .status-online {
            background-color: var(--chat-success);
        }

        .status-offline {
            background-color: var(--chat-secondary);
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top border-bottom">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'Chat Application') }}
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="chat-container">
        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        const messagesContainer = document.getElementById('chat-messages');
        messagesContainer.scrollTop = messagesContainer.scrollHeight;

        document.addEventListener('DOMContentLoaded', function() {
            window.Echo.join('general').listenForWhisper('typing', (e) => {
                showTypingIndicator(e.user);
            }).listenForWhisper('stopTyping', (e) => {
                hideTypingIndicator();
            });
            window.Echo.channel('general').listen('MessageSent', (e) => {
                console.log(e);
                appendMessage(e.message);
            });
        });
        function appendMessage(message) {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
            const isCurrentUser = message.user_id === {{ Auth::id() }};
            const messageHtml = ``;
            messagesContainer.insertAdjacentHTML('beforeend', messageHtml);
        }
        function showTypingIndicator(user) {
            let typingDiv = document.getElementById('typing-indicator');
            if (!typingDiv) {
                typingDiv = document.createElement('div');
                typingDiv.id = 'typing-indicator';
                typingDiv.classList.add('typing-indicator');
                document.querySelector('.chat-messages').appendChild(typingDiv);
            }
            typingDiv.innerText = `${user.name} is typing...`;
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        function hideTypingIndicator() {
            const typingDiv = document.getElementById('typing-indicator');
            if (typingDiv) {
                typingDiv.remove();
            }
        }
        // Enable Bootstrap tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        // Add CSRF token to all AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
