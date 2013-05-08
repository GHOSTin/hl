{% extends "ajax.tpl" %}
{% block js %}
	show_dialog(get_hidden_content());
	$('.create_query').click(function(){
		$.get('create_query',{
		{% if component.initiator != false %}
			initiator: '{{component.initiator}}',
			{% if component.initiator == 'number' %}
				id: {{component.number.id}},
			{% else %}
				id: {{component.house.id}},
			{% endif %}
		{% endif %}
			fio: $('.dialog-fio').val(),
			work_type: $('.dialog-worktype').val(),
			telephone: $('.dialog-telephone').val(),
			cellphone: $('.dialog-cellphone').val(),
			description: $('.dialog-description').val()
			},function(r){
				$('.queries').prepend(r);
				$('.dialog').modal('hide');
			});
	});
{% endblock js %}
{% block html %}
<div class="modal">
    <div class="modal-header">
        <h3>Форма создания заявки</h3>
    </div>
    <div class="modal-body">
	{% if component.initiator != false %}
		{% if component.initiator == 'number' %}
			{%if component.number != false %}
				<ul>
					<li>л/с №{{component.number.number}}</li>
					<li>Владелец: {{component.number.fio}}</li>
					<li>Телефон: {{component.number.telephone}}</li>
					<li>Сотовый: {{component.number.cellphone}}</li>
					<li>Контактное лицо: {{component.number.contact_fio}}</li>
					<li>Телефон контактного лица: {{component.number.contact_telephone}}</li>
					<li>Сотовые телефон контактного лица: {{component.number.contact_cellphone}}</li>
				</ul>
			{% endif %}
		{% elseif component.initiator == 'house' %}
			{% if component.house != false %}
				<div>
					{{component.house.street_name}}, дом №{{component.house.number}}
				</div>
			{% endif %}
		{% endif %}
		<div style="display:inline-block;vertical-align:top; width:300px">
			<div>Данные контактного лица по заявке</div>
			<div class="dialog-addinfo">
				<table>
					<tr>
						<td>ФИО:</td>
						<td><input type="text" class="dialog-fio" /></td>
					</tr>
					<tr>
						<td>Телефон:</td>
						<td><input type="text" class="dialog-telephone" /></td>
					</tr>
					<tr>
						<td>Сот. телефон:</td>
						<td><input type="text" class="dialog-cellphone" /></td>
					</tr>										
				</table>
			</div>
		</div>
		<div style="display:inline-block;">
			<div>Выберите тип работ по заявке </div>
			<select class="dialog-worktype">
				{% if component.query_work_types != false %}
					{% for query_work_type in component.query_work_types %}
						<option value="{{query_work_type.id}}">{{query_work_type.name}}</option>
					{% endfor %}
				{% endif %}
			</select>
			<div>
				<div>Выберите тип заявка</div>
				<select class="dialog-warningtype">
					<option value="hight">Аварийная заявка</option>
					<option value="normal" selected>Заявка на участок</option>
					<option value="planned">Плановая заявка</option>
				</select>
			</div>
		</div>
		<div class="dialog-trouble" style="padding: 20px 0px 0px 0px;">
			<textarea class="dialog-description" style="width:500px; height:100px;"></textarea>
		</div>

	{% endif %}
	</div>
	<div class="modal-footer">
		<div class="btn create_query">Создать</div>
		<div class="btn close_dialog">Отмена</div>
	</div>
</div>
{% endblock html %}