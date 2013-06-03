{% extends "dialog.tpl" %}
{% set meter = component.meters[0] %}
{% set number = component.number %}
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
		<li>Тарифность: {{ rates[meter.rates - 1] }}</li>
		<li>Разрядность: {{ meter.capacity }}</li>
	</ul>
	<ul>
		<li>
			<span>Заводской номер</span>
			<input type="text" class="dialog-input-serial">
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
			<span>Дата последней поверки</span>
			<input type="text" class="dialog-input-date_checking" value="{{ time }}">
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
			<textarea style="width:90%" class="dialog-textarea-comment"></textarea>
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
	// Привязывает счетчик к лицевому счету с выбранными параметрами
	$('.add_meter').click(function(){
		$.get('add_meter',{
			number_id: {{ number.id }},
			meter_id: {{ meter.id }},
			service: '{{ service}}',
			serial: $('.dialog-input-serial').val(),
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