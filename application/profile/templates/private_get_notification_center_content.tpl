{% if component.users != false %}
<div class="col-left">
    <input id="user-filter" class="input-medium search-query" type="text"><br>
    <div class='user-list'>
        <ul class='users'>
            {% for user in component.users %}
                <li user_id="{{user.id}}">{{user.lastname}}</li>
            {% endfor %}
        </ul>
    </div>
</div>
<div class='col-right tab-content chat-list' id='main'></div>
{% endif %}