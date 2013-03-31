<option value="0">Выберите работу</option>
{% for work in component.works %}
<option value="{{work.id}}">{{work.name}}</option>
{% endfor %}