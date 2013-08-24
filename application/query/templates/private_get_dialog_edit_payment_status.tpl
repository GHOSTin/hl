{% extends "ajax.tpl" %}
{% set query = component.query %}
{% set payment_statuses = {'paid':'Оплачиваемая', 'unpaid':'Неоплачиваемая', 'recalculation': 'Перерасчет'}%}
{% block js %}
	show_dialog(get_hidden_content());
	$('.update_payment_status').click(function(){
		$.get('update_payment_status',{
			id: {{query.id}},
			status: $('.dialog-payment_status :selected').val()
			},function(r){
				init_content(r);
				$('.dialog').modal('hide');
			});
	});
{% endblock js %}
{% block html %}
<div class="modal">
    <div class="modal-header">
        <h3>Тип оплаты заявки</h3>
    </div>	
	<div class="modal-body">
		<label>Тип оплаты:</label>
		<select class="dialog-payment_status">
			{% for key, payment_status in payment_statuses %}
				<option value="{{key}}"
				{% if query.payment_status == key%}
					selected
				{% endif %}
				>{{payment_status}}</option>
			{% endfor %}
		</select>
	</div>
	<div class="modal-footer">
		<div class="btn update_payment_status">Сохранить</div>
		<div class="btn close_dialog">Отмена</div>
	</div>	  
</div>
{% endblock html %}