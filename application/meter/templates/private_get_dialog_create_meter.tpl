{% extends "dialog.tpl" %}
{% block title %}Диалог создания нового счетчика{% endblock title %}
{% block dialog %}
	<input type="input" class="dialog-input-name">
	<p>В названии счетчика могут быть использованы буквы русского алфавита, цифры, пробелы.</p>
	<p><label>Тарифность</label>
		<select class="dialog-select-rates">
			<option value="1">Однотарифный</option>
			<option value="2">Двухтарифный</option>
			<option value="3">Трехтарифный</option>
		</select>
	</p>
	<p><label>Разрядность</label>
		<select class="dialog-select-capacity">
			<option value="1">1</option>
			<option value="2">2</option>
			<option value="3">3</option>
			<option value="4">4</option>
			<option value="5">5</option>
			<option value="6">6</option>
			<option value="7">7</option>
			<option value="8">8</option>
			<option value="9">9</option>
		</select>
	</p>
{% endblock dialog %}
{% block buttons %}
	<div class="btn create_meter">Создать</div>
{% endblock buttons %}
{% block script %}
	$('.create_meter').click(function(){
		$.get('create_meter',{
			name: $('.dialog-input-name').val(),
			rates: $('.dialog-select-rates').val(),
			capacity: $('.dialog-select-capacity').val()
			},function(r){
				$('.dialog').modal('hide');
				init_content(r);
			});
	});
{% endblock script %}