    <style>
        #chat-bubble-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #0d6efd;
            color: white;
            border: none;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            font-size: 28px;
            z-index: 1000;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        body {
            background: #f5f6fa;
        }

        .chat-container {
            display: flex;
            height: 75vh;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            background: #fff;
        }

        .sidebar {
            width: 80px;
            background: #2f2f3a;
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 15px 0;
        }

        .sidebar i {
            font-size: 20px;
            margin: 20px 0;
            cursor: pointer;
        }

        .chat-list {
            width: 300px;
            border-right: 1px solid #e5e5e5;
            padding: 15px;
            overflow-y: auto;
        }

        .chat-list .chat-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            cursor: pointer;
        }

        .chat-item img {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 50%;
            margin-right: 10px;
        }

        .chat-item .info {
            flex: 1;
        }

        .chat-area {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            background: #f1f4f9;
        }

        .chat-header {
            padding: 15px;
            border-bottom: 1px solid #e5e5e5;
            background: #fff;
            font-weight: bold;
        }

        .chat-messages {
            padding: 15px;
            overflow-y: auto;
            flex: 1;
        }

        .message {
            margin-bottom: 20px;
            max-width: 60%;
            padding: 10px 15px;
            border-radius: 15px;
            position: relative;
            font-size: 14px;
            line-height: 1.5;
        }

        .message.me {
            background: #d1e7dd;
            margin-left: auto;
            border-bottom-right-radius: 0;
        }

        .message.them {
            background: #fff;
            margin-right: auto;
            border-bottom-left-radius: 0;
        }

        .chat-footer {
            padding: 15px;
            background: #fff;
            display: flex;
            border-top: 1px solid #e5e5e5;
        }

        .chat-footer input {
            flex: 1;
            border: 1px solid #ccc;
            border-radius: 20px;
            padding: 10px 15px;
            outline: none;
        }

        .chat-footer button {
            margin-left: 10px;
        }
    </style>

    <!-- Tombol Bulat dengan Notifikasi -->
    <div class="position-fixed bottom-0 end-0 m-4" style="z-index: 1050;">
        <div class="position-relative">
            <button id="chat-bubble-btn" data-bs-toggle="modal" data-bs-target="#chatModal"
                class="btn btn-primary rounded-circle" style="width: 60px; height: 60px; font-size: 28px;">
                💬
            </button>

            @if ($chatUsers->sum('unread_count') > 0)
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    {{ $chatUsers->sum('unread_count') }}
                </span>
            @endif
        </div>
    </div>

    <!-- Modal Chat -->
    <div class="modal fade" id="chatModal" tabindex="-1" aria-labelledby="chatModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="chat-container">
                        <!-- Sidebar -->
                        <div class="sidebar">
                            <i class="bi bi-chat-dots-fill"></i>
                            <i class="bi bi-people-fill"></i>
                            <i class="bi bi-gear-fill"></i>
                        </div>

                        <!-- Chat List -->
                        <div class="chat-list">
                            @foreach ($chatUsers as $user)
                                <div class="chat-item" onclick="selectUser({{ $user->id }}, '{{ $user->name }}')">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}"
                                        alt="{{ $user->name }}">
                                    <div class="info">
                                        <div>{{ $user->name }}</div>
                                        <small class="text-muted">Klik untuk chat</small>
                                    </div>
                                    @if ($user->unread_count > 0)
                                        <span class="badge bg-danger ms-auto">{{ $user->unread_count }}</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>


                        <!-- Chat Area -->
                        <div class="chat-area">
                            <div class="chat-header" id="chat-with">Pilih user untuk mulai chat</div>
                            <div class="chat-messages" id="chat-box"></div>
                            <div class="chat-footer">
                                <input type="text" id="chat-message" placeholder="Tulis pesan..." />
                                <button class="btn btn-primary" onclick="sendMessage()">Kirim</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <script>
        let selectedUserId = null;
        const authUserId = {{ auth()->id() }};

        function selectUser(userId, userName) {
            selectedUserId = userId;
            document.getElementById('chat-with').innerText = 'Chat dengan ' + userName;
            fetch('/chat/messages', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        receiver_id: userId
                    })
                })
                .then(res => res.json())
                .then(messages => {
                    const box = document.getElementById('chat-box');
                    box.innerHTML = '';
                    messages.forEach(msg => {
                        const isMe = msg.sender_id == authUserId;
                        box.innerHTML += `
                    <div class="message ${isMe ? 'me' : 'them'}">
                        ${msg.message}
                    </div>
                `;
                    });
                    box.scrollTop = box.scrollHeight;
                });
        }

        function sendMessage() {
            const message = document.getElementById('chat-message').value;
            if (!message.trim()) return;

            fetch('/chat/send', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    receiver_id: selectedUserId,
                    message: message
                })
            }).then(res => res.json()).then(data => {
                const box = document.getElementById('chat-box');
                box.innerHTML += `
                <div class="message me">
                    ${data.message}
                </div>
            `;
                document.getElementById('chat-message').value = '';
                box.scrollTop = box.scrollHeight;
            });
        }

        window.Echo.private('chat.{{ auth()->id() }}')
            .listen('MessageSent', (e) => {
                if (e.message.sender_id == selectedUserId) {
                    document.getElementById('chat-box').innerHTML += `
                    <div class="message them">
                        ${e.message.message}
                    </div>
                `;
                    document.getElementById('chat-box').scrollTop = document.getElementById('chat-box').scrollHeight;
                }
            });
    </script>
