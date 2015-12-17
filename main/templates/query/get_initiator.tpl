{% extends "ajax.tpl" %}

{% set w = null %}

{% if initiator == 'number' %}
  {% set house = number.get_flat().get_house() %}
{% endif %}

{% block js %}
	show_dialog(get_hidden_content());
  $('.dialog-cellphone').inputmask("mask", {"mask": "(999) 999-99-99"});
  $('.dialog-telephone').inputmask("mask", {"mask": "99-99-99"});
  $('.i-checks').iCheck({
    checkboxClass: 'icheckbox_square-green',
    radioClass: 'iradio_square-green',
  });
  $('.dialog-phrase').change(function(){
    $('.dialog-description').text($('.dialog-phrase').val());
  });
  $('.dialog-worktype').change(function(){
    if(!_.isEmpty($(this).val())) {
      var id = $(this).val();
      var id = $('.dialog-worktype :selected').val();
      $.get('/api/workgroups/' + id + '/phrases/')
        .done(function(res){
          var template = Twig.twig({
            href: '/templates/query/phrases.tpl',
            async: false
          });
          $('.dialog-phrase').html(template.render(res));
        });
    }
  });
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
			description: $('.dialog-description').val(),
      checkbox: $('.dialog-checkbox-contacts').prop("checked")
			},function(r){
				$('.queries').html(r);
				$('.dialog').modal('hide');
			});

    {% if initiator == 'number' and user.check_access("queries/save_contacts")%}
      $.get('update_contacts',{
          id: {{ number.get_id() }},
          telephone: $('.dialog-telephone').val(),
          cellphone: $('.dialog-cellphone').val(),
          checked: $('.dialog-checkbox-contacts').prop("checked")
          });
    {% endif %}
	});
{% endblock %}

{% block html %}
<div class="modal-dialog modal-lg">
  <div class="modal-content animated fadeIn">
      <div class="modal-header">
          {% if initiator == 'number' %}
              <h2 class="m-t-xs">{{ number.get_address() }}, {{ number.get_fio() }} (л/с №{{ number.get_number() }})</h2>
              <h3>Задолженость: {{ number.get_debt()|number_format(2, '.', ' ') }} руб.</h3>
          {% else %}
            <h2>{{ house.get_address() }}</h2>
          {% endif %}
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-6">
            {% if initiator == 'number' %}
              <div class="dialog-addinfo">
                <h4>Данные контактного лица по заявке</h4>
                <div class="form-group">
                  <label class=" control-label">ФИО:</label>
                  <input type="text" class="form-control dialog-fio" value="{{ number.get_fio() }}">
                </div>
                <div class="form-group">
                  <label class="control-label">Телефон:</label>
                  <input type="text" class="form-control dialog-telephone" value="{{ number.get_telephone() }}">
                </div>
                <div class="form-group">
                  <label class="control-label">Сот. телефон:</label>
                  <input type="text" class="form-control dialog-cellphone" value="{{ number.get_cellphone() }}">
                </div>
                {% if user.check_access("queries/save_contacts") %}
                  <div class="i-checks m-b-sm">
                    <label>
                      <input type="checkbox" class="dialog-checkbox-contacts" value=""> <i></i> Использовать контакты как основные
                    </label>
                  </div>
                {% endif %}
              </div>
            {% else %}
              <div class="dialog-addinfo">
                <h4>Данные контактного лица по заявке</h4>
                <div class="form-group">
                  <label class=" control-label">ФИО:</label>
                  <input type="text" class="form-control dialog-fio" value="">
                </div>
                <div class="form-group">
                  <label class="control-label">Телефон:</label>
                  <input type="text" class="form-control dialog-telephone" value="">
                </div>
                <div class="form-group">
                  <label class="control-label">Сот. телефон:</label>
                  <input type="text" class="form-control dialog-cellphone" value="">
                </div>
              </div>
            {% endif %}
                <div class="form-group">
                  <label>Тип заявки</label>
                  <select class="form-control dialog-query_type">
                    {% for query_type in query_types %}
                      <option value="{{ query_type.get_id() }}">{{ query_type.get_name()}}</option>
                    {% endfor %}
                  </select>
                </div>
                <div class="form-group">
                  <label>Тип работ</label>
                  <select class="form-control dialog-worktype">
                    <option value=""></option>
                    {% for workgroup in query_work_types %}
                      {% if loop.first %}
                        {% set w = workgroup %}
                      {% endif%}
                      <option value="{{ workgroup.get_id() }}">{{ workgroup.get_name() }}</option>
                    {% endfor %}
                  </select>
                </div>
              </div>
          <div class="col-sm-6">
            {% if queries is not empty %}
              <h4>Последние заявки на этот дом</h4>
              <ul class="list-unstyled old-queries">
                {% for query in queries  %}
                  <li class="query_status_{{ query.get_status() }}">
                    <h5>
                      {% if query.get_initiator() == 'number' %}
                        <i class="fa fa-user notification-center-icon" style="font-size:12px" alt="Заявка на личевой счет"></i>
                      {% else %}
                        <i class="fa fa-home notification-center-icon" style="font-size:12px" alt="Заявка на дом"></i>
                      {% endif %}
                      <strong> №{{ query.get_number() }}</strong>
                      <time datetime="{{ query.get_time_open()|date('d.m.Y') }}" id="query_time_{{ query.get_id() }}" class="pull-right">
                        <script>moment.locale('ru'); $("#query_time_{{ query.get_id() }}").text(moment.unix({{ query.get_time_open() }}).local().fromNow());</script>
                      </time>
                    </h5> {{ query.get_description() }}
                    {% if query.get_initiator() == 'number' %}
                      {% for number in query.get_numbers() %}
                        <div> кв.{{ number.get_flat().get_number() }} {{ number.get_number() }} ({{ number.get_fio() }})</div>
                      {% endfor %}
                    {% endif %}
                    {% if query.get_status() in ['close', 'reopen'] %}
                      <p class="label label-default">{{ query.get_close_reason() }}</p>
                    {% endif %}
                  </li>
                {% endfor %}
              </ul>
            {% endif %}
            {% if initiator == 'number' and number.get_events() is not empty %}
              <h4>Последние события</h4>
              <ul class="list-unstyled">
                {% for event in number.get_events()|slice(0,5) %}
                  <li><strong>{{ event.get_time()|date("d.m.Y") }}</strong> {{ event.get_name() }}</li>
                {% endfor %}
              </ul>
            {% endif %}
            <h4>Последние 5 отключений</h4>
            <ul>
              {% for outage in house.get_outages().slice(0, 5) %}
                <li>
                  c {{ outage.get_begin()|date("d.m.Y") }} по {{ outage.get_target()|date("d.m.Y") }} {{ outage.get_category().get_name() }}: {{ outage.get_description() }}
                </li>
              {% endfor %}
            </ul>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 form-group">
            <label>Типовые фразы</label>
            <select class="form-control dialog-phrase">
              <option></option>
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
</div>
{% endblock html %}