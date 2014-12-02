{% extends "ajax.tpl" %}

{% block js %}
	show_dialog(get_hidden_content());
	$('.update_contact_information').click(function(){
		$.get('update_contact_information',{
			id: {{ query.get_id() }},
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
<div class="modal-content">
    <div class="modal-header">
        <h3>Контактная информация</h3>
    </div>
	<div class="modal-body row">
        <div class="col-lg-8">
            <div class="form-group">
                <label  class="control-label">ФИО</label>
                <input type="text" value="{{query.get_contact_fio()}}" class="dialog-fio form-control">
            </div>
            <div class="form-group">
                <label class="control-label">Телефон</label>
                <input type="tel" value="{{query.get_contact_telephone()}}" class="dialog-telephone form-control">
            </div>
            <div class="form-group">
                <label class="control-label">Сотовый телефон</label>
                <input type="tel" value="{{query.get_contact_cellphone()}}" class="dialog-cellphone form-control">
            </div>
        </div>
	</div>
	<div class="modal-footer">
		<div class="btn btn-primary update_contact_information">Сохранить</div>
		<div class="btn btn-default close_dialog">Отмена</div>
	</div>
</div>
{% endblock html %}