@extends('layouts.admin')

@section('title', $title)

@section('admin-content')
<style>
    .chat-container {
        display: flex;
        flex-direction: column;
        height: 600px;
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid rgba(17,24,39,0.1);
    }
    .chat-messages {
        flex: 1;
        overflow-y: auto;
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 12px;
        background: #f8fafc;
    }
    .msg-bubble {
        max-width: 80%;
        padding: 10px 14px;
        border-radius: 14px;
        position: relative;
        font-size: 14px;
        line-height: 1.5;
    }
    .msg-in {
        align-self: flex-start;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-bottom-left-radius: 2px;
    }
    .msg-out {
        align-self: flex-end;
        background: #dcf8c6;
        border: 1px solid #c5e1a5;
        border-bottom-right-radius: 2px;
    }
    .msg-meta {
        font-size: 10px;
        color: #64748b;
        margin-top: 4px;
        display: block;
        text-align: right;
    }
    .chat-input-area {
        padding: 16px;
        background: #fff;
        border-top: 1px solid #e2e8f0;
    }
    .chat-form {
        display: flex;
        gap: 10px;
    }
    .chat-input {
        flex: 1;
        padding: 10px 14px;
        border: 1px solid #e2e8f0;
        border-radius: 24px;
        outline: none;
    }
    .chat-input:focus { border-color: #2563eb; }
    .btn-send {
        background: #2563eb;
        color: #fff;
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }
</style>
<div class="module-header">
    <div class="header-info">
        <h3>WhatsApp Conversation</h3>
        <p>{{ $mother->full_name }} · {{ $mother->whatsapp_number }}</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('admin.mothers.show', $mother) }}" class="btn-primary" style="text-decoration:none;">Back to Details</a>
    </div>
</div>

<div class="chat-container">
    <div class="chat-messages custom-scrollbar" id="chatWindow">
        @forelse($messages->reverse() as $m)
            @php($isOut = ($m->direction ?? 'in') === 'out')
            <div class="msg-bubble {{ $isOut ? 'msg-out' : 'msg-in' }}">
                <div class="msg-body">{{ $m->body }}</div>
                <span class="msg-meta">{{ $m->sent_at?->format('H:i') ?: $m->created_at->format('H:i') }}</span>
            </div>
        @empty
            <div style="text-align:center; padding:40px; color:#64748b;">No messages yet. Start the conversation!</div>
        @endforelse
    </div>

    <div class="chat-input-area">
        <form action="{{ route('admin.mothers.messages.send', $mother) }}" method="POST" class="chat-form">
            @csrf
            <input type="text" name="message" class="chat-input" placeholder="Type a WhatsApp message..." required autocomplete="off">
            <button type="submit" class="btn-send">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 6 22 2"/></svg>
            </button>
        </form>
    </div>
</div>

<script>
    const chatWindow = document.getElementById('chatWindow');
    chatWindow.scrollTop = chatWindow.scrollHeight;
</script>
@endsection
