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
	{% if request.GET('value') == 'house' %}
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
	{% if response.streets != false %}
        <div class="row">
            <div class="col-lg-5">
                <select style="display:block" class="form-control dialog-select-street">
                    <option value="0">Выберите улицу</option>
                    {% for street in response.streets %}
                        <option value="{{street.get_id()}}">{{street.get_name()}}</option>
                    {% endfor %}
                </select>
                <select class="form-control dialog-select-house" style="display:block" disabled="disabled">
                    <option value="0">Ожидание...</option>
                </select>
                {% if request.GET('value') == 'number' %}
                    <select class="form-control dialog-select-number" style="display:block" disabled="disabled">
                        <option value="0">Ожидание...</option>
                    </select>
                {% endif %}
            </div>
        </div>
	{% endif %}
{% endblock html %}