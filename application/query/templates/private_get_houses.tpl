{% if houses != false %}
	{% for house in houses %}
		<option value="{{house.id}}">дом №{{house.number}}</option>
	{% endfor %}
{% endif %}