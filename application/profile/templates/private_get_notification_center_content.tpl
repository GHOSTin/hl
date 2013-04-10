{% if component.users != false %}
<div class="chat-col-left">
    <input id="chat-user-filter" class="input-medium search-query" type="text"><br>
    <div class='chat-user-list'>
        <ul class='chat-users'>
            {% for user in component.users %}
                <li user_id="{{user.id}}">{{user.lastname}}</li>
            {% endfor %}
        </ul>
    </div>
</div>
<div class='chat-col-right tab-content chat-list' id='main'></div>
{% endif %}