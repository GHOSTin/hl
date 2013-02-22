{% if ans != false %}
<ul>
	{% for user in ans %}
		<li user_id="{{user.id}}">{{user.lastname}}</li>
	{% endfor %}
</ul>
{% endif %}