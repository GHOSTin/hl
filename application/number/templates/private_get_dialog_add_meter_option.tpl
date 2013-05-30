{% extends "dialog.tpl" %}
{% set meter = component.meters[0] %}
{% set service = component.service %}
{% set time = component.time %}
{% set services = {'cold_water':'Холодное водоснабжение',
    'hot_water':'Горячее водоснабжение', 'electrical':'Электроэнергия'} %}
{% set rates = ['однотарифный', 'двухтарифный', 'трехтарифный'] %}
{% block title %}Диалог добавления счетчика{% endblock title %}
{% block dialog %}
	<ul class="unstyled">
		<li>Счетчик: {{ meter.name }}</li>
		<li>Услуга: {{ services[service] }}</li>
		<li>Тарифность: {{ rates[meter.rates] }}</li>
		<li>Разрядность: {{ meter.capacity }}</li>
	</ul>
	<ul>
		<li>
			<span>Заводской номер</span>
			<input type="text">
		</li>
		<li>
			<span>Дата выпуска</span>
			<input type="text" class="dialog-input-date_release" value="{{ time }}">
		</li>
		<li>
			<span>Дата установки</span>
			<input type="text" class="dialog-input-date_install" value="{{ time }}">
		</li>
		<li>
			<span>Дата поверки</span>
			<input type="text" class="dialog-input-date_checking" value="{{ time }}">
		</li>
		<li>
			<span>Период поверки</span>
			<input type="text">
		</li>
		{% if service in ['cold_water', 'hot_water'] %}
		<li>
			<span>Место установки</span>
			<select>
				<option value="bathroom">Ванна</option>
				<option value="kitchen">Кухня</option>
				<option value="toilet" selected>Туалет</option>
			</select>
		</li>
		{% endif %}
		<li>
			<label>Коментарий</label>
			<textarea style="width:90%"></textarea>
		</li>
	</ul>
{% endblock dialog %}
{% block buttons %}
	<div class="btn add_meter">Добавить</div>
{% endblock buttons %}
{% block script %}
	$('.dialog-input-date_release').datepicker({format: 'dd.mm.yyyy', language: 'ru'}).on('changeDate', function(){
		$('.dialog-input-date_release').datepicker('hide');
	});
	$('.dialog-input-date_install').datepicker({format: 'dd.mm.yyyy', language: 'ru'}).on('changeDate', function(){
		$('.dialog-input-date_install').datepicker('hide');
	});
	$('.dialog-input-date_checking').datepicker({format: 'dd.mm.yyyy', language: 'ru'}).on('changeDate', function(){
		$('.dialog-input-date_checking').datepicker('hide');
	});
{% endblock script %}