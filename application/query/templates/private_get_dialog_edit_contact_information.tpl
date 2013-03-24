{% extends "ajax.tpl" %}
{% set query = component.queries[0] %}
{% block js %}
	show_dialog(get_hidden_content());
	$('.update_contact_information').click(function(){
		$.get('update_contact_information',{
			id: {{query.id}},
			fio: $('.dialog-fio').val(),
			telephone: $('.dialog-telephone').val(),
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
        <h3>Контактная информация</h3>
    </div>	
	<div class="modal-body">
		<label>ФИО</label>
		<input type="text" value="{{query.contact_fio}}" class="dialog-fio" />
		<label>Телефон</label>
		<input type="text" value="{{query.contact_telephone}}" class="dialog-telephone" />
		<label>Сотовый телефон</label>
		<input type="text" value="{{query.contact_cellphone}}" class="dialog-cellphone" />
	</div>
	<div class="modal-footer">
		<div class="btn update_contact_information">Сохранить</div>
		<div class="btn close_dialog">Отмена</div>
	</div>	  
</div>
{% endblock html %}