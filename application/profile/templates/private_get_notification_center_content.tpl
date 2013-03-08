{% if ans != false %}
<div class='user-list'>
    <ul class='users'>
        {% for user in ans %}
            <li user_id="{{user.id}}">{{user.lastname}}</li>
        {% endfor %}
    </ul>
</div>
<div class='tab-content chat-list' id='main'></div>
{% endif %}
