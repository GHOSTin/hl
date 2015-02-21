{% extends "dialog.tpl" %}

{% block title %}Диалог смены инициатора{% endblock %}

{% block dialog %}
	<div class="form-group">
		<select class="dialog-select-initiator form-control">
			<option value="number"{% if query.get_initiator() == 'number' %} selected{% endif %}>Заявка на лицевой счет</option>
			<option value="house"{% if query.get_initiator() == 'house' %} selected{% endif %}>Заявка на дом</option>
		</select>
	</div>
	<div>
		<select class="dialog-select-street form-control">
			<option value="">Выберите улицу...</option>
			{% for street in streets %}
			<option value="{{ street.get_id() }}">{{ street.get_name() }}</option>
			{% endfor %}
		</select>
	</div>
	<div class="form-group">
		<select class="dialog-select-house form-control" disabled>
			<option>Ожидание...</option>
		</select>
	</div>
	<div class="dialog-options form-group">
		{% if query.get_initiator() == 'number' %}
		<select class="dialog-select-number form-control" disabled>
			<option>Ожидание...</option>
		</select>
		{% endif %}
	</div>
{% endblock %}

{% block buttons %}
	<div class="btn btn-default change_initiator">Изменить</div>
{% endblock %}

{% block script %}
	// изменяет инициатора
	$('.change_initiator').click(function(){
		if($('.dialog-select-initiator').val() == 'number'){
			var number_id = $('.dialog-select-number').val();
			if(number_id > 0){
				$.get('change_initiator',{
					query_id: {{ query.get_id() }},
					number_id: number_id
					},function(r){
						$('.dialog').modal('hide');
						init_content(r);
					});
			}
		}else{
			var house_id = $('.dialog-select-house').val();
			if(house_id > 0){
				$.get('change_initiator',{
					query_id: {{ query.get_id() }},
					house_id: house_id
					},function(r){
						$('.dialog').modal('hide');
						init_content(r);
					});
			}
		}
	});

	$('.dialog-select-initiator').change(function(){
		if($(this).val() == 'number'){
			$('.dialog-options').html('<select class="dialog-select-number form-control" disabled><option>Ожидание...</option></select>');
		}else{
			$('.dialog-options').empty();
		}
		$('.dialog-select-house').val(0);
	});

	// получает дома
	$('.dialog-select-street').change(function(){
		var street_id = $(this).val();
		if(street_id > 0){
			$.get('get_houses', {
				id: street_id
			}, function(r){
				$('.dialog-select-house').html(r);
				$('.dialog-select-house').prop('disabled', false);
			});
		}
	});

	$('.dialog-select-house').change(function(){
		if($('.dialog-select-initiator').val() == 'number'){
			var house_id = $(this).val();
			if(house_id > 0){
				$.get('get_numbers', {
					id: house_id
				}, function(r){
					$('.dialog-select-number').html(r);
					$('.dialog-select-number').prop('disabled', false);
				});
			}
		}
	});
{% endblock %}