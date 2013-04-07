{% extends "ajax.tpl" %}
{% set user = component.user %}
{% block js %}
	show_dialog(get_hidden_content());
	$('.update_telephone').click(function(){
		$.get('update_telephone',{
			telephone: $('.dialog-telephone').val()
			},function(r){
				init_content(r);
				$('.dialog').modal('hide');
			});
	});
{% endblock js %}
{% block html %}
<div class="modal">
    <div class="modal-header">
        <h3>Смена номера телефона</h3>
    </div>	
	<div class="modal-body">
		<input type="text" value="{{ user.telephone }}" class="dialog-telephone">
	</div>
	<div class="modal-footer">
		<div class="btn update_telephone">Сохранить</div>
		<div class="btn close_dialog">Отмена</div>
	</div>	  
</div>
{% endblock html %}