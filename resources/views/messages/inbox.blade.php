<x-auth-layout>
<div class="flex gap-4 p-4">
    <!-- Conversations List -->
    <div class="w-1/4 border p-2">
        <h4>Conversations</h4>
        <ul id="conversationList">
            @foreach($conversations as $conv)
                <li data-id="{{ $conv['user']->user_id }}" class="cursor-pointer p-2 border-b flex justify-between items-center">
                    <span>{{ $conv['user']->username }}</span>
                    @if($conv['unread_count'] > 0)
                        <span class="bg-red-500 text-white px-2 rounded-full text-xs">{{ $conv['unread_count'] }}</span>
                    @endif
                </li>
            @endforeach
        </ul>

        <h5 class="mt-4">Start New Chat</h5>
        <ul id="othersList">
            @foreach($others as $o)
                <li data-id="{{ $o->user_id }}" class="cursor-pointer p-2 border-b">{{ $o->username }}</li>
            @endforeach
        </ul>
    </div>

    <!-- Chat Window -->
    <div class="w-3/4 border flex flex-col p-2">
        <div id="chatHeader" class="border-b pb-2 mb-2"></div>
        <div id="messages" class="flex-1 overflow-y-auto p-2"></div>

        <form id="messageForm" class="mt-2 flex gap-2">
            @csrf
            <input type="hidden" id="receiver_id" name="receiver_id">
            <input type="text" id="messageInput" name="content" class="flex-1 p-2 border rounded" placeholder="Type a message..." required>
            <button type="submit" class="bg-blue-600 text-white px-4 rounded">Send</button>
        </form>
    </div>
</div>

<!-- Axios + Pusher -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script>
const authUserId = {{ auth()->id() }};
let currentReceiver = null;

// Set active conversation
function setActive(id, name){
    currentReceiver = id;
    document.getElementById('receiver_id').value = id;
    document.getElementById('chatHeader').textContent = 'Chat with: ' + name;
}

// Click handlers (Conversations + Others)
document.querySelectorAll('#conversationList li, #othersList li').forEach(li=>{
    li.addEventListener('click', ()=>{
        setActive(li.dataset.id, li.textContent.trim());
        loadMessages(li.dataset.id); // ✅ load messages immediately
    });
});

// Load messages
function loadMessages(user_id){
    axios.get(`/messages/user/${user_id}`).then(res=>{
        const box = document.getElementById('messages');
        box.innerHTML = '';
        res.data.forEach(m=>{
            const mine = m.sender_id == authUserId;
            const div = document.createElement('div');
            div.className = mine ? 'text-right mb-1' : 'text-left mb-1';
            div.innerHTML = `<b>${m.sender.username}:</b> ${m.content}`;
            box.appendChild(div);
        });
        box.scrollTop = box.scrollHeight;
    });
}

// Send message
document.getElementById('messageForm').addEventListener('submit', function(e){
    e.preventDefault();
    if(!currentReceiver) return alert('Select a conversation first');
    let formData = new FormData(this);
    axios.post('{{ route("messages.store") }}', formData).then(res=>{
        loadMessages(currentReceiver);
        this.reset();
    });
});

// Pusher
var pusher = new Pusher("{{ config('broadcasting.connections.pusher.key') }}", {
    cluster: "{{ config('broadcasting.connections.pusher.options.cluster') }}",
    forceTLS: true
});

var channel = pusher.subscribe('private-chat.' + authUserId);
channel.bind('message.sent', function(data){
    if(data.message.sender.id == currentReceiver){
        loadMessages(currentReceiver);
    }
});
</script>
</x-auth-layout>
