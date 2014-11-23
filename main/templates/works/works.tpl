{% for work in works %}
    <li class="work" work_id="{{ work.get_id() }}">
        <div class="work-title">{{ work.get_name() }}</div>
    </li>
{% endfor %}