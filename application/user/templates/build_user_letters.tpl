<ul>
{% for letter, users in letters %}
    <li title="{{ users }}"><div>{{ letter }}</div></li>
{% endfor %}
</ul>