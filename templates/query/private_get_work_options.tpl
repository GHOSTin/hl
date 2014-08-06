<option value="0">Выберите работу</option>
{% for work in response.work_group.get_works() %}
<option value="{{ work.get_id() }}">{{ work.get_name() }}</option>
{% endfor %}