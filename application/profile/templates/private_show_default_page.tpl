{% if user != false %}
<div>
	{{user.firstname}} {{user.lastname}}
</div>
<div>
	{{user.id}}
</div>
{% endif %}