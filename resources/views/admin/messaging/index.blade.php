@extends('layouts.admin')

@section('title', 'Internal Messaging')

@section('admin-content')
<div class="messaging-container">
    <div class="chat-list">
        <div class="chat-search">
            <input type="text" placeholder="Search messages...">
        </div>
        <div class="chats">
            <div class="chat-item active">
                <div class="avatar">M</div>
                <div class="chat-info">
                    <h5>Marketing Team</h5>
                    <p>New article draft is ready...</p>
                </div>
                <span class="time">10:45 AM</span>
            </div>
            <div class="chat-item">
                <div class="avatar">J</div>
                <div class="chat-info">
                    <h5>John Doe</h5>
                    <p>I've uploaded the payroll report.</p>
                </div>
                <span class="time">9:30 AM</span>
            </div>
        </div>
    </div>
    
    <div class="chat-view">
        <div class="chat-header">
            <h3>Marketing Team</h3>
        </div>
        <div class="chat-messages">
            <div class="msg-received">
                <p>Hello! The new campaign for "Malkia Konnect" is ready to launch.</p>
                <span class="msg-time">10:40 AM</span>
            </div>
            <div class="msg-sent">
                <p>Great! Let me review the draft before we publish it.</p>
                <span class="msg-time">10:45 AM</span>
            </div>
        </div>
        <div class="chat-input">
            <input type="text" placeholder="Type your message...">
            <button class="btn-send">Send</button>
        </div>
    </div>
</div>
@endsection
