{% for key in keys %}
<li>{{ key.get_name() }} ({{ key.get_hash() }})</li>
{% endfor %}