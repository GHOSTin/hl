{% extends "ajax.tpl" %}
{% set user = component.user %}
{% block js %}
	show_dialog(get_hidden_content());
	$('.update_cellphone').click(function(){
		$.get('update_cellphone',{
			id: {{ user.id }},
			cellphone: $('.dialog-cellphone').val()
			},function(r){
				init_content(r);
				$('.dialog').modal('hide');
			});
	});
{% endblock js %}
{% block html %}
<div class="modal">
    <div class="modal-header">
        <h3>Смена номера сотового телефона</h3>
    </div>	
	<div class="modal-body">
		<input type="text" value="{{ user.cellphone }}" class="dialog-cellphone">
	</div>
	<div class="modal-footer">
		<div class="btn update_cellphone">Сохранить</div>
		<div class="btn close_dialog">Отмена</div>
	</div>	  
</div>
{% endblock html %}