{% extends "ajax.tpl" %}

{% set statuses = {'open' : 'Открытые заявки', 'close' : 'Закрытые заявки', 'reopen' : 'В работе', 'reopen' : 'Переоткрытые', 'working' : 'В работе'} %}

{% block js %}
    $('.report-content').html(get_hidden_content());

    // датапикер
    $('.query_time_begin').datetimepicker({
      format: 'DD.MM.YYYY',
      locale: 'ru',
      defaultDate: moment.unix({{ filters.time_open_begin }})
    }).on('dp.change', function(e){
        $.get('/reports/queries/set_time_begin/',{
            time: e.date.format('DD.MM.YYYY')
            });
    });

    $('.query_time_end').datetimepicker({
      format: 'DD.MM.YYYY',
      locale: 'ru',
      defaultDate: moment.unix({{ filters.time_open_end }})
    }).on('dp.change', function(e){
        $.get('/reports/queries/set_time_end/',{
          time: e.date.format('DD.MM.YYYY')
        }) ;
    });

    // изменяет фильтр статуса
    $('.filter-status').change(function(){
        $.get('/reports/queries/set_status/', {
            status: $('.filter-status').val()
        }, function(r){
            init_content(r);
        });
    });

    // изменяет фильтр участка
    $('.filter-select-department').change(function(){
        $.get('/reports/queries/set_department/', {
            id: $('.filter-select-department').val()
        }, function(r){
            init_content(r);
        });
    });

    // изменяет фильтр типа работ
    $('.filter-select-worktype').change(function(){
        $.get('/reports/queries/set_worktype/', {
            id: $('.filter-select-worktype').val()
        }, function(r){
            init_content(r);
        });
    });

    // изменяет фильтр типа заявки
    $('.filter-select-query_type').change(function(){
        $.get('queries/set_query_type', {
            id: $('.filter-select-query_type').val()
        }, function(r){
            init_content(r);
        });
    });

    // изменяет фильтр улиц
    $('.filter-select-street').change(function(){
      $.get('/reports/queries/set_street/', {
        id: $('.filter-select-street').val()
      }, function(r){
        init_content(r);
      });
    });

    // изменяет фильтр домов
    $('.filter-select-house').change(function(){
      $.get('/reports/queries/set_house/', {
        id: $('.filter-select-house').val()
      }, function(r){
        init_content(r);
      });
    });

    // сбрасывает фильтры
    $('.clear_filter_query').click(function(){
        $.get('/reports/queries/clear_filters/', {
        }, function(r){
            init_content(r);
        });
    });
{% endblock %}

{% block html %}
<h4>Отчеты по заявкам</h4>
<div class="row">
  <div class="col-sm-3 col-lg-3">
    <div class="col-xs-12">
      <h4>Фильтры <small><a class="pull-right clear_filter_query">сбросить</a></small></h4>
    </div>
    <ul class="list-unstyled filters">
      <li>
        <label>по дате</label>
        <div class="row form-group">
          <label class="control-label col-xs-1">с</label><div class="col-xs-10">
          <input type="text" class="form-control query_time_begin"></div>
        </div>
        <div class="row form-group">
          <label class="control-label col-xs-1">по</label><div class="col-xs-10">
          <input type="text" class="form-control query_time_end"></div>
        </div>
      </li>
      <li class="form-group">
        <label>по статусу заявки</label>
        <select class="filter-status form-control">
          <option value="all">Все заявки</option>
          {% for key, status in statuses %}
          <option value="{{ key }}"{% if key == filters.status %} selected{% endif %}>{{status}}</option>
          {% endfor %}
        </select>
      </li>
      <li class="form-group">
        <label>по типу заявки</label>
        <select class="filter-select-query_type form-control">
          <option value="all">Все типы</option>
          {% for query_type in query_types %}
          <option value="{{ query_type.get_id() }}"{% if query_type.get_id() == filters.query_types %} selected{% endif %}>{{ query_type.get_name() }}</option>
          {% endfor %}
        </select>
      </li>
      <li class="form-group">
        <label>по дому и улице</label>
        <select class="filter-select-street form-control">
          <option value="all">Все улицы</option>
          {% for street in streets %}
          <option value="{{ street.get_id() }}"{% if street.get_id() == filters.street %} selected{% endif %}>{{ street.get_name() }}</option>
          {% endfor %}
        </select>
        {% if houses is not empty %}
        <select class="filter-select-house form-control" style="margin-top:10px">
          <option value="all">Выберите дом...</option>
          {% for house in houses %}
          <option value="{{ house.get_id() }}"{% if house.get_id() == filters.house %} selected{% endif %}>дом №{{ house.get_number() }}</option>
          {% endfor %}
        </select>
        {% else %}
        <select class="filter-select-house form-control" style="margin-top:10px" disabled="disabled">
          <option>Ожидание...</option>
        </select>
        {% endif %}
      </li>
      <li class="form-group">
        <label>по участку</label>
        <select class="filter-select-department form-control">
          <option value="all">Все участки</option>
          {% for department in departments %}
          <option value="{{ department.get_id() }}"{% if department.get_id() in filters.department %} selected{% endif %}>{{ department.get_name() }}</option>
          {% endfor %}
        </select>
      </li>
      <li class="form-group">
        <label>по типу работ</label>
        <select class="filter-select-worktype form-control">
          <option value="all">Все работы</option>
          {% for query_work_type in query_work_types %}
          <option value="{{ query_work_type.get_id() }}"{% if query_work_type.get_id() == filters.work_type %} selected{% endif %}>{{ query_work_type.get_name() }}</option>
          {% endfor %}
        </select>
      </li>
    </ul>
  </div>
  <div class="col-xs-12 col-sm-9 col-lg-9">
    <ul class="list-unstyled">
      <li>Отчет №1
        <div>
          <a class="btn btn-link" href="/reports/queries/report1/" target="_blank">Просмотреть</a>
          <a class="btn btn-link" href="/reports/queries/report1/noclose/" target="_blank">Только не закрытые</a>
          <a class="btn btn-link" href="/reports/queries/report1/xls/" target="_blank">Выгрузить</a>
        </div>
      </li>
    </ul>
  </div>
</div>
{% endblock %}