{% extends "ajax.tpl" %}

{% block js %}
	show_dialog(get_hidden_content());
	$('.create_query').click(function(){
		$.get('create_query',{
			initiator: '{{ initiator }}',
			{% if initiator == 'number' %}
				id: {{ number.get_id() }},
			{% else %}
				id: {{ house.get_id() }},
			{% endif %}
			fio: $('.dialog-fio').val(),
			work_type: $('.dialog-worktype').val(),
      query_type: $('.dialog-query_type').val(),
			telephone: $('.dialog-telephone').val(),
			cellphone: $('.dialog-cellphone').val(),
			description: $('.dialog-description').val()
			},function(r){
				$('.queries').html(r);
				$('.dialog').modal('hide');
			});
	});
{% endblock %}

{% block html %}
<div class="modal-content">
    <div class="modal-header">
      <h3>Создание заявки</h3>
    </div>
    <div class="modal-body">
      {% if queries is not empty %}
      <h5>Последние заявки на этот дом</h5>
       <ul class="list-unstyled old-queries">
          {% for query in queries  %}
            <li class="query_status_{{ query.get_status() }}"><strong>{{ query.get_time_open()|date('d.m.Y') }} №{{ query.get_number() }}</strong> {{ query.get_description() }}
              {% if query.get_status() in ['close', 'reopen'] %}
              <p class="label label-default">{{ query.get_close_reason() }}</p>
              {% endif %}
            </li>
          {% endfor %}
        </ul>
      {% endif %}
      {% if initiator == 'number' and number.get_events() is not empty %}
        <h5>Последние события</h5>
        <ul class="list-unstyled">
        {% for event in number.get_events()|slice(0,5) %}
          <li><strong>{{ event.get_time()|date("d.m.Y") }}</strong> {{ event.get_name() }}</li>
        {% endfor %}
        </ul>
      {% endif %}
		{% if initiator == 'number' %}
        <div>
        {{ number.get_flat().get_house().get_street().get_name() }}, дом №{{ number.get_flat().get_house().get_number() }}, кв.{{ number.get_flat().get_number() }}, {{ number.get_fio() }}(л/с №{{ number.get_number() }})
  				<ul>
            <li>Задолженость: {{ number.get_debt() }} руб.</li>
  					{% if number.get_telephone() %}<li>Телефон: {{ number.get_telephone() }}</li>{% endif %}
  					{% if number.get_cellphone() %}<li>Сотовый: {{ number.get_cellphone() }}</li>{% endif %}
  				</ul>
        </div>
		{% else %}
				<div>
					{{ house.get_street().get_name() }}, дом №{{ house.get_number() }}
				</div>
		{% endif %}
        <p><strong>Данные контактного лица по заявке</strong></p>
        <div class="dialog-addinfo">
            <div class="row form-group">
              <label class="col-lg-3 control-label">ФИО:</label>
              <div class="col-lg-5">
                <input type="text" class="form-control dialog-fio">
              </div>
            </div>
            <div class="row form-group">
              <label class="col-lg-3 control-label">Телефон:</label>
              <div class="col-lg-5">
                <input type="text" class="form-control dialog-telephone">
              </div>
            </div>
            <div class="row form-group">
              <label class="col-lg-3 control-label">Сот. телефон:</label>
              <div class="col-lg-5">
                <input type="text" class="form-control dialog-cellphone">
              </div>
            </div>
        </div>
        <div class="row">
          <div class="col-md-6 form-group">
            <label>Тип работ</label>
            <select class="form-control dialog-worktype">
            {% if query_work_types != false %}
              {% for workgroup in query_work_types %}
                <option value="{{ workgroup.get_id() }}">{{ workgroup.get_name() }}</option>
              {% endfor %}
            {% endif %}
            </select>
          </div>
          <div class="col-md-6 form-group">
            <label>Тип заявки</label>
            <select class="form-control dialog-query_type">
            {% for query_type in query_types %}
              <option value="{{ query_type.get_id() }}">{{ query_type.get_name()}}</option>
            {% endfor %}
            </select>
          </div>
        </div>
		<div class="form-group dialog-trouble">
      <label>Описание</label>
			<textarea class="form-control dialog-description" rows="5"></textarea>
		</div>
	</div>
	<div class="modal-footer">
		<div class="btn btn-primary create_query">Создать</div>
		<div class="btn btn-default close_dialog" data-dismiss="modal">Отмена</div>
	</div>
</div>
{% endblock html %}