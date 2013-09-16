{% for group in groups %}
    <li class="group" group="{{ group.get_id() }}">
        <div class="group-name get_group_content">{{ group.get_name() }}</div>
    </li>
{% else %}
    <li>Нет групп в букве.</li>
{% endfor %}