@extends('layout.master')

@section('content')
<div class="row h-100">
    <!-- Users Sidebar -->
    <div class="col-md-3 chat-sidebar">
        <div class="p-3">
            <h5 class="mb-3">Online Users</h5>
            <div class="user-list">
                @foreach($users as $user)
                <div class="d-flex align-items-center mb-3">
                    <div class="user-status status-offline"></div>
                    <div>{{ $user->name }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Chat Area -->
    <div class="col-md-9">
        <div class="chat-messages" id="chat-messages">
            @foreach($messages as $message)
            <div class="message {{ $message->user_id === Auth::id() ? 'message-sent' : 'message-received' }}">
                <div class="message-content">
                    <div class="message-header">
                        <small class="text-muted">{{ $message->user->name }}</small>
                    </div>
                    <div>{{ $message->message }}</div>
                    <div class="message-footer">
                        <small class="text-muted">{{ $message->created_at->diffForHumans() }}</small>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Chat Input -->
        <div class="chat-input">
            <form id="message-form" class="d-flex" method="POST">
                @csrf
                <input type="text" 
                       id="message-input" 
                       class="form-control me-2" 
                       placeholder="Type your message..."
                       required>
                <button type="submit" class="btn btn-primary">Send</button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const messageForm = document.getElementById('message-form');
    const messageInput = document.getElementById('message-input');
    const messagesContainer = document.getElementById('chat-messages');

    // Handle message submission
    messageForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const message = messageInput.value.trim();
        if (!message) return;
        
        try {
            const response = await fetch('/chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    message: message,
                    conversation_id: '1'
                })
            });

            const data = await response.json();
            messageInput.value = '';
            // Remove the local append since we'll receive it via broadcast
            // appendMessage(data.message);
        } catch (error) {
            console.error('Error:', error);
        }
    });

    // Listen for broadcast events
    window.Echo.channel('general')
        .listen('MessageSent', (e) => {
            appendMessage(e.message);
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        });

    function appendMessage(message) {
        const currentUserId = {{ Auth::id() }};
        const isCurrentUser = message.user.id === currentUserId;
        const messageHtml = `
            <div class="message ${isCurrentUser ? 'message-sent' : 'message-received'}">
                <div class="message-content">
                    <div class="message-header">
                        <small class="text-muted">${message.user.name}</small>
                    </div>
                    <div>${message.message}</div>
                    <div class="message-footer">
                        <small class="text-muted">Just now</small>
                    </div>
                </div>
            </div>
        `;
        messagesContainer.insertAdjacentHTML('beforeend', messageHtml);
    }
});
</script>
@endpush
@endsection
