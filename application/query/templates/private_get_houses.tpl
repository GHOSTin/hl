<option value="all">Все дома</option>
{% for house in component.houses %}
	<option value="{{house.id}}">дом №{{house.number}}</option>
{% endfor %}