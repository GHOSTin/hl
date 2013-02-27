{% if initiator == 'number' %}
	{%if number != false %}
		<ul>
			<li>л/с №{{number.number}}</li>
			<li>Владелец: {{number.fio}}</li>
			<li>Телефон: {{number.telephone}}</li>
			<li>Сотовый: {{number.cellphone}}</li>
			<li>Контактное лицо: {{number.contact_fio}}</li>
			<li>Телефон контактного лица: {{number.contact_telephone}}</li>
			<li>Сотовые телефон контактного лица: {{number.contact_cellphone}}</li>
		</ul>
	{% endif %}
{% elseif initiator == 'house' %}
	{% if house != false %}
		<div>
			{{house.street_name}}, дом №{{house.number}}
		</div>
	{% endif %}
{% endif %}
<div>
	<div>Выберите тип работ по заявке </div>
	<select class="dialog-select-worktypeID">
		{% if query_work_types != false %}
			{% for query_work_type in query_work_types %}
				<option value="{{query_work_type.id}}">{{query_work_type.name}}</option>
			{% endfor %}
		{% endif %}
	</select>
</div>
<div style="padding:10px 0px 0px 0px;">
	<div>Выберите тип заявка</div>
	<select class="dialog-select-warningtype">
		<option value="hight">Аварийная заявка</option>
		<option value="normal" selected>Заявка на участок</option>
		<option value="planned">Плановая заявка</option>
	</select>
</div>
<div style="padding: 20px 0px 20px 0px;">
	<div>Данные контактного лица по заявке</div>
	<div class="dialog-addinfo">
		<table>
			<tr>
				<td>ФИО:</td>
				<td><input type="text" class="dialog-addinfo-fio" /></td>
			</tr>
			<tr>
				<td>Телефон:</td>
				<td><input type="text" class="dialog-addinfo-telephone" /></td>
			</tr>
			<tr>
				<td>Сот. телефон:</td>
				<td><input type="text" class="dialog-addinfo-cellphone" /></td>
			</tr>										
		</table>
	</div>
</div>
<div class="dialog-trouble" style="padding: 20px 0px 0px 0px;">
	<textarea class="dialog-trouble-textarea" style="width:500px; height:100px;"></textarea>
</div>