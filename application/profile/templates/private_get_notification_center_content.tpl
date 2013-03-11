{% if component.users != false %}
<div class='user-list'>
    <ul class='users'>
        {% for user in component.users %}
            <li user_id="{{user.id}}">{{user.lastname}}</li>
        {% endfor %}
    </ul>
</div>
<div class='tab-content chat-list' id='main'></div>
{% endif %}