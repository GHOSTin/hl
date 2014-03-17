{% extends "ajax.tpl" %}
{% set queries = component.queries %}
{% block js %}
	show_dialog(get_hidden_content());
	$('.create_query').click(function(){
		$.get('create_query',{
			initiator: '{{ request.GET('initiator') }}',
			{% if request.GET('initiator') == 'number' %}
				id: {{ component.number.get_id() }},
			{% else %}
				id: {{ component.house.get_id() }},
			{% endif %}
			fio: $('.dialog-fio').val(),
			work_type: $('.dialog-worktype').val(),
			telephone: $('.dialog-telephone').val(),
			cellphone: $('.dialog-cellphone').val(),
			description: $('.dialog-description').val()
			},function(r){
				$('.queries').html(r);
				$('.dialog').modal('hide');
			});
	});
{% endblock js %}
{% block html %}
<div class="modal-content">
    <div class="modal-header">
        <h3>Форма создания заявки</h3>
    </div>
    <div class="modal-body">
      {% if queries is not empty %}
      <h5>Последние заявки на этот дом</h5>
       <ul class="list-unstyled old-queries">
          {% for query in component.queries  %}
            <li class="query_status_{{ query.get_status() }}"><strong>{{ query.get_time_open()|date('d.m.Y') }} №{{ query.get_number() }}</strong> {{ query.get_description() }}
              {% if query.get_status() in ['close', 'reopen'] %}
              <p class="label label-default">{{ query.get_close_reason() }}</p>
              {% endif %}
            </li>
          {% endfor %}
        </ul>
      {% endif %}
    <h5>Информация об инициаторе заявке</h5>
		{% if request.GET('initiator') == 'number' %}
        <div>
  				<ul>
  					<li>л/с №{{ component.number.get_number() }}</li>
  					<li>Владелец: {{ component.number.get_fio() }}</li>
  					<li>Телефон: {{ component.number.get_telephone() }}</li>
  					<li>Сотовый: {{ component.number.get_cellphone() }}</li>
  					{#<li>Контактное лицо: {#{ component.number.get_contact_fio() }}</li>
  					<li>Телефон контактного лица: {{ component.number.get_contact_telephone()}}</li>
  					<li>Сотовые телефон контактного лица: {{ component.number.get_contact_cellphone() }}</li>#}
  				</ul>
        </div>
		{% else %}
				<div>
					{{ component.house.get_street().get_name() }}, дом №{{ component.house.get_number() }}
				</div>
		{% endif %}
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
                {% if component.query_work_types != false %}
                    {% for query_work_type in component.query_work_types %}
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
			<textarea class="form-control dialog-description" rows="5"></textarea>
		</div>
	</div>
	<div class="modal-footer">
		<div class="btn btn-primary create_query">Создать</div>
		<div class="btn btn-default close_dialog" data-dismiss="modal">Отмена</div>
	</div>
</div>
{% endblock html %}