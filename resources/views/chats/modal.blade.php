{{-- ===== CHAT MODAL - MINIMAL CLEAN VERSION ===== --}}
<style>
    #chat-bubble-btn {
        position: fixed;
        bottom: 90px;
        right: 20px;
        background-color: #0d6efd;
        color: #fff;
        border: none;
        border-radius: 50%;
        width: 58px;
        height: 58px;
        font-size: 26px;
        z-index: 1000;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
        transition: 0.2s;
    }

    #chat-bubble-btn:hover {
        transform: scale(1.08);
        background-color: #0b5ed7;
    }

    .chat-container {
        display: flex;
        flex-direction: column;
        height: 70vh;
        border-radius: 16px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
    }

    .chat-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 16px;
        background: #fff;
        border-bottom: 1px solid #e5e5e5;
        position: relative;
    }

    .chat-messages {
        flex: 1;
        display: flex;
        flex-direction: column;
        padding: 16px;
        background: #f5f7fa;
        overflow-y: auto;
        font-size: 14px;
        gap: 10px;
        /* beri jarak antar pesan */
    }

    .message {
        display: block;
        /* penting: agar vertikal */
        width: fit-content;
        max-width: 70%;
        padding: 10px 14px;
        border-radius: 16px;
        line-height: 1.5;
        word-wrap: break-word;
        white-space: pre-wrap;
    }

    .message.me {
        align-self: flex-end;
        background: #d0ebff;
        border-bottom-right-radius: 4px;
    }

    .message.them {
        align-self: flex-start;
        background: #fff;
        border: 1px solid #e5e5e5;
        border-bottom-left-radius: 4px;
    }

    .chat-footer {
        background: #fff;
        padding: 10px 15px;
        border-top: 1px solid #e5e5e5;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    #chat-message {
        flex: 1;
        border-radius: 20px;
        border: 1px solid #ddd;
        padding: 8px 14px;
        font-size: 14px;
        outline: none;
        transition: 0.2s;
    }

    #chat-message:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 2px rgba(13, 110, 253, 0.15);
    }

    .btn-send {
        border-radius: 50%;
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #0d6efd;
        color: #fff;
        border: none;
        transition: 0.2s;
    }

    .btn-send:hover {
        background: #0b5ed7;
        transform: scale(1.05);
    }

    /* === Select2 Custom === */
    .select2-container--default .select2-selection--single {
        height: 40px !important;
        border-radius: 10px !important;
        border: 1px solid #ddd !important;
        display: flex !important;
        align-items: center !important;
        padding: 4px 6px !important;
    }

    .select2-selection__rendered {
        display: flex !important;
        align-items: center !important;
        gap: 8px !important;
        font-size: 13px !important;
        color: #333 !important;
    }

    .select2-results__option {
        display: flex !important;
        align-items: center !important;
        gap: 8px !important;
        padding: 6px !important;
        font-size: 13px !important;
    }

    .select2-results__option img {
        border-radius: 50%;
        width: 30px;
        height: 30px;
        object-fit: cover;
    }

    .select2-results__option small {
        color: #6c757d;
        display: block;
        font-size: 12px;
    }
</style>


{{-- ===== FLOATING BUTTON ===== --}}
<div class="position-fixed bottom-0 end-0 m-4" style="z-index:1050;">
    <div class="position-relative">
        <button id="chat-bubble-btn" data-bs-toggle="modal" data-bs-target="#chatModal">💬</button>
        @if ($chatUsers->sum('unread_count') > 0)
            <span
                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">{{ $chatUsers->sum('unread_count') }}</span>
        @endif
    </div>
</div>

{{-- ===== MODAL CHAT ===== --}}
<div class="modal fade" id="chatModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4">
            <div class="modal-body p-0">
                <div class="chat-container">
                    {{-- HEADER: SELECT2 DI SINI --}}
                    <div class="chat-header">
                        <h6>Pilih User untuk di chat -</h6>
                        <select id="user-search" class="form-select" style="width: 300px;">
                            <option value="">Pilih user untuk chat...</option>
                            @foreach ($chatUsers as $u)
                                <option value="{{ $u->id }}"
                                    data-photo="{{ $u->profile_image ? asset('profile_images/' . $u->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($u->name) }}"
                                    data-unit="{{ $u->employmentDetail?->unit?->name ?? '-' }}">
                                    {{ $u->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- AREA CHAT --}}
                    <div class="chat-messages" id="chat-box"></div>

                    {{-- FOOTER --}}
                    <div class="chat-footer">
                        <input type="text" id="chat-message" placeholder="Tulis pesan..." />
                        <button class="btn-send" id="send-btn"><i class="bi bi-send-fill"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===== SCRIPTS ===== --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<script>
    let selectedUserId = null;
    const authUserId = {{ auth()->id() }};

    $(function() {
        // Format tampilan user di select2
        function formatUser(user) {
            if (!user.id) return user.text;
            const photo = $(user.element).data('photo');
            const unit = $(user.element).data('unit') || '-';
            return $(`
                <div class="d-flex align-items-center">
                    <img src="${photo}" width="32" height="32" class="rounded-circle me-2" style="object-fit:cover;">
                    <div>
                        <div class="fw-semibold">${user.text}</div>
                        <small class="text-muted">${unit}</small>
                    </div>
                </div>
            `);
        }

        // Inisialisasi Select2
        $('#user-search').select2({
            placeholder: 'Pilih user...',
            allowClear: true,
            width: 'resolve',
            dropdownParent: $('#chatModal'),
            templateResult: formatUser,
            templateSelection: formatUser,
            minimumResultsForSearch: 1
        });

        // Ketika user dipilih, langsung buka chat
        $('#user-search').on('select2:select', function(e) {
            const data = e.params.data;
            openChatForUser(data.id, data.text);
        });

        $('#send-btn').on('click', sendMessage);
        $('#chat-message').on('keypress', e => {
            if (e.key === 'Enter') {
                e.preventDefault();
                sendMessage();
            }
        });
    });

    function openChatForUser(userId, userName) {
        selectedUserId = parseInt(userId);
        console.log('📱 Chat dibuka dengan user:', selectedUserId);

        $('#chat-box').html('<p class="text-center text-muted small">Memuat pesan...</p>');

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
                $('#chat-box').html('');
                messages.forEach(msg => {
                    const isMe = msg.sender_id == authUserId;
                    $('#chat-box').append(
                        `<div class="message ${isMe ? 'me' : 'them'}">${escapeHtml(msg.message)}</div>`
                    );
                });
                $('#chat-box').scrollTop($('#chat-box')[0].scrollHeight);
                $('#chat-message').focus();
            });
    }


    // Kirim pesan
    function sendMessage() {
        const message = $('#chat-message').val().trim();
        if (!message || !selectedUserId) return;
        fetch('/chat/send', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    receiver_id: selectedUserId,
                    message
                })
            })
            .then(res => res.json())
            .then(data => {
                $('#chat-box').append(`<div class="message me">${escapeHtml(data.message)}</div>`);
                $('#chat-message').val('');
                $('#chat-box').scrollTop($('#chat-box')[0].scrollHeight);
            });
    }

    function escapeHtml(text) {
        return String(text).replace(/[&<>"'`=\/]/g, s => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#39;',
            '/': '&#x2F;',
            '`': '&#x60;',
            '=': '&#x3D;'
        })[s]);
    }

    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(() => {
            if (!window.Echo) {
                // console.error('❌ Echo belum siap, listener tidak dijalankan!');
                return;
            }

            // console.log('✅ Echo listener aktif untuk chat.{{ auth()->id() }}');

            window.Echo.private('chat.{{ auth()->id() }}')
                .listen('.MessageSent', (e) => {
                    // console.log('📩 Pesan realtime diterima:', e.message);

                    if (e.message.sender_id === selectedUserId) {
                        $('#chat-box').append(`
                        <div class="message them">${escapeHtml(e.message.message)}</div>
                    `);
                        $('#chat-box').scrollTop($('#chat-box')[0].scrollHeight);
                    } else {
                        // console.log('💬 Pesan dari user lain:', e.message.sender_id);
                    }
                });

        }, 1000); // kasih delay 1 detik agar Echo benar-benar sudah siap
    });
</script>
