<option value="0">Выберите дом</option>
{% for house in component.houses %}
	<option value="{{house.id}}">дом №{{house.number}}</option>
{% endfor %}