{% extends "dialog.tpl" %}
{% set meter = component.meters[0] %}
{% set old_meter = component.old_meters[0] %}
{% set service = component.service %}
{% set time = component.time %}
{% set services = {'cold_water':'Холодное водоснабжение',
    'hot_water':'Горячее водоснабжение', 'electrical':'Электроэнергия'} %}
{% set rates = ['однотарифный', 'двухтарифный', 'трехтарифный'] %}
{% block title %}Диалог перепривязки счетчика{% endblock title %}
{% block dialog %}
	<ul class="unstyled">
		<li>Счетчик: {{ meter.name }}</li>
		<li>Услуга: {{ services[service] }}</li>
		<li>Тарифность: {{ rates[meter.rates - 1] }}</li>
		<li>Разрядность: {{ meter.capacity }}</li>
	</ul>
	<ul>
		<li>
			<span>Заводской номер</span>
			<input type="text" class="dialog-input-serial" value="{{ old_meter.serial }}">
		</li>
		<li>
			<span>Дата выпуска</span>
			<input type="text" class="dialog-input-date_release" value="{{ old_meter.date_release|date('d.m.Y') }}">
		</li>
		<li>
			<span>Дата установки</span>
			<input type="text" class="dialog-input-date_install" value="{{ old_meter.date_install|date('d.m.Y') }}">
		</li>
		<li>
			<span>Дата последней поверки</span>
			<input type="text" class="dialog-input-date_checking" value="{{ old_meter.date_checking|date('d.m.Y') }}">
		</li>
		<li>
			<span>Период поверки</span>
			<select class="dialog-select-period">
                {% for period in meter.periods %}
               		<option value="{{ period }}">
                    {% if period > 12 %}
                        {{ period // 12 }} г {{ period % 12 }} месяц
                    {% else %}
                        {{ period }} мес.
                    {% endif %}
                   </option>
                {% endfor %}
            </select>
		</li>
		{% if service in ['cold_water', 'hot_water'] %}
		<li>
			<span>Место установки</span>
			<select class="dialog-select-place">
				<option value="bathroom">Ванна</option>
				<option value="kitchen">Кухня</option>
				<option value="toilet" selected>Туалет</option>
			</select>
		</li>
		{% endif %}
		<li>
			<label>Коментарий</label>
			<textarea style="width:90%" class="dialog-textarea-comment">{{ old_meter.comment }}</textarea>
		</li>
	</ul>
{% endblock dialog %}
{% block buttons %}
	<div class="btn change_meter">Перепривязать</div>
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
	// Привязывает счетчик к лицевому счету с выбранными параметрами
	$('.change_meter').click(function(){
		$.get('change_meter',{
			number_id: {{ old_meter.number_id }},
			meter_id: {{ old_meter.meter_id }},
			serial: {{ old_meter.serial }},
			service: '{{ service }}',
			new_meter_id: {{ meter.id }},
			new_serial: $('.dialog-input-serial').val(),
			date_release: $('.dialog-input-date_release').val(),
			date_install: $('.dialog-input-date_install').val(),
			date_checking: $('.dialog-input-date_checking').val(),
			period: $('.dialog-select-period').val(),
			place: $('.dialog-select-place').val(),
			comment: $('.dialog-textarea-comment').val()
			},function(r){
				$('.dialog').modal('hide');
				init_content(r);
			});
	});
{% endblock script %}