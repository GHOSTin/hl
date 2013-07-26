{% for group in groups %}
    <li class="group" group="{{ group.id }}">
        <div class="group-name get_group_content">{{ group.name }}</div>
    </li>
{% else %}
    <li>Нет групп в букве.</li>
{% endfor %}