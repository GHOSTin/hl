{% if component.users != false %}
<div class="chat-col-left col-sm-4 col-xs-12">
    <input id="chat-user-filter" class="form-control" type="search">
    <a id="chat-user-filter-clear" class="close" href="#">&times;</a>
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