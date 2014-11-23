{% extends "ajax.tpl" %}
{% set client_query = response.client_query %}
{% set number = response.number %}
{% block js %}
	show_dialog(get_hidden_content());
	$('.accept_client_query').click(function(){
		$.get('accept_client_query',{
			id: {{ number.get_id() }},
			fio: $('.dialog-fio').val(),
			work_type: $('.dialog-worktype').val(),
			telephone: $('.dialog-telephone').val(),
			cellphone: $('.dialog-cellphone').val(),
			description: $('.dialog-description').val(),
            time: {{ request.GET('time') }}
			},function(r){
                $('.dialog').modal('hide');
                init_content(r);
			});
	});
{% endblock js %}
{% block html %}
<div class="modal-content">
    <div class="modal-header">
        <h3>Форма создания заявки</h3>
    </div>
    <div class="modal-body">
				<ul>
					<li>л/с №{{ number.get_number() }}</li>
					<li>Владелец: {{ number.get_fio() }}</li>
					<li>Телефон: {{ number.get_telephone() }}</li>
					<li>Сотовый: {{ number.get_cellphone() }}</li>
					{#<li>Контактное лицо: {#{ number.get_contact_fio() }}</li>
					<li>Телефон контактного лица: {{ number.get_contact_telephone()}}</li>
					<li>Сотовые телефон контактного лица: {{ number.get_contact_cellphone() }}</li>#}
				</ul>
		        <p><strong>Данные контактного лица по заявке</strong></p>
        <div class="dialog-addinfo">
            <div class="row form-group">
                <label class="col-lg-3 control-label">ФИО:</label>
                <div class="col-lg-5"><input type="text" class="form-control dialog-fio"></div>
            </div>
            <div class="row form-group">
                <label class="col-lg-3 control-label">Телефон:</label>
                <div class="col-lg-5"><input type="text" class="form-control dialog-telephone" /></div>
            </div>
            <div class="row form-group">
                <label class="col-lg-3 control-label">Сот. телефон:</label>
                <div class="col-lg-5"><input type="text" class="form-control dialog-cellphone" /></div>
            </div>
        </div>
        <div class="form-group">
            <p><strong>Выберите тип работ по заявке</strong></p>
            <select class="form-control dialog-worktype">
                {% if response.query_work_types != false %}
                    {% for query_work_type in response.query_work_types %}
                        <option value="{{query_work_type.get_id()}}">{{query_work_type.get_name()}}</option>
                    {% endfor %}
                {% endif %}
            </select>
            <p><strong>Выберите тип заявки</strong></p>
            <select class="form-control dialog-warningtype">
                <option value="hight">Аварийная заявка</option>
                <option value="normal" selected>Заявка на участок</option>
                <option value="planned">Плановая заявка</option>
            </select>
        </div>
		<div class="form-group dialog-trouble">
			<textarea class="form-control dialog-description" rows="5">{{ client_query.get_text() }}</textarea>
		</div>
	</div>
	<div class="modal-footer">
		<div class="btn btn-primary accept_client_query">Принять заявку</div>
		<div class="btn btn-default close_dialog" data-dismiss="modal">Отмена</div>
	</div>
</div>
{% endblock html %}