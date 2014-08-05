{% for profile in profiles %}
    <li class="profile" profile="{{ profile }}">
        <div class="profile-name get_profile_content">{{ profile }}</div>
    </li>
{% else %}
    <li>Нет профилей.</li>
{% endfor %}