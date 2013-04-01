{% extends "ajax.tpl" %}
{% block js %}
	$('.modal-body').html(get_hidden_content());
	$('.dialog-select-street').change(function(){
		$.get('get_houses',{
			id: $('.dialog-select-street :selected').val()
		},function(r){
			$('.dialog-select-house').html(r).attr('disabled', false);
		});
	});		
	{% if component.initiator == 'house' %}
		$('.dialog-select-house').change(function(){
			$.get('get_initiator',{
				initiator: 'house',
				id: $('.dialog-select-house :selected').val()
			},function(r){
				init_content(r);
			});
		});	
	{% else %}
		$('.dialog-select-house').change(function(){
			$.get('get_numbers',{
				id: $('.dialog-select-house :selected').val()
			},function(r){
				$('.dialog-select-number').html(r).attr('disabled', false);
			});
		});
		$('.dialog-select-number').change(function(){
			$.get('get_initiator',{
				initiator: 'number',
				id: $('.dialog-select-number :selected').val()
			},function(r){
				init_content(r);
			});
		});	
	{% endif %}
{% endblock js %}
{% block html %}
	{% if component.streets != false %}
		<select style="display:block" class="dialog-select-street">
			<option value="0">Выберите улицу</option>
			{% for street in component.streets %}
				<option value="{{street.id}}">{{street.name}}</option>
			{% endfor %}
		</select>
		<select class="dialog-select-house" style="display:block" disabled="disabled">
			<option value="0">Ожидание...</option>
		</select>
		{% if component.initiator == 'number' %}
			<select class="dialog-select-number" style="display:block" disabled="disabled">
				<option value="0">Ожидание...</option>
			</select>
		{% endif %}
	{% endif %}
{% endblock html %}