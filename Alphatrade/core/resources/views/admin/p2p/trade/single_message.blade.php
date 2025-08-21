<div class="single-message ">
    <div class="message-content-outer">
        <div class="message-content-outer-wrapper d-flex  gap-1">
            @if ($message->admin_id != 0)
                <div class="chat-author" style="background-color: transparent">
                    <img src="{{ siteFavicon() }}" />
                </div>
            @else
                <div class="chat-author">
                    <span>{{ __(firstTwoCharacter(@$message->sender->full_name)) }}</span>
                </div>
            @endif
            <div class="message-content">
                <p class="message-text skeleton">
                    @php echo $message->message; @endphp
                    <small class="text--small d-block">{{ showDateTime($message->created_at) }}</small>
                </p>
            </div>
        </div>
    </div>
</div>
