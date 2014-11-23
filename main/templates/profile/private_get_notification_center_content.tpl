{% if response.users != false %}
<div class="chat-col-left col-sm-4 col-xs-12">
    <input id="chat-user-filter" class="form-control" type="search">
    <a id="chat-user-filter-clear" class="close" href="#">&times;</a>
    <div class='chat-user-list'>
        <ul class='chat-users'>
            {% for user in response.users %}
                <li user_id="{{ user.get_id() }}">{{ user.get_lastname() }}</li>
            {% endfor %}
        </ul>
    </div>
</div>
<div class='chat-col-right tab-content chat-list col-sm-8 col-xs-12' id='main'></div>
{% endif %}