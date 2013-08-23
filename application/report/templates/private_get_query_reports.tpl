{% extends "ajax.tpl" %}
{% set filters = component.filters %}
{% set houses = component.houses %}
{% block js %}
    $('.report-content').html(get_hidden_content());

    // датапикер
    $('.query_time_begin').datepicker({format: 'dd.mm.yyyy', language: 'ru'}).on('changeDate', function(){
        $('.query_time_begin').datepicker('hide');
        $.get('set_time_begin',{
            time: $('.query_time_begin').val()
            },function(r){
                init_content(r);
            });
    });

    $('.query_time_end').datepicker({format: 'dd.mm.yyyy', language: 'ru'}).on('changeDate', function(){
        $('.query_time_end').datepicker('hide');
        $.get('set_time_end',{
            time: $('.query_time_end').val()
            }, function(r){
                init_content(r);
            });
    });
    
    // изменяет фильтр статуса
    $('.filter-status').change(function(){
        $.get('set_filter_query_status', {
            status: $('.filter-status').val()
        }, function(r){
            init_content(r);
        });
    });

    // изменяет фильтр участка
    $('.filter-select-department').change(function(){
        $.get('set_filter_query_department', {
            id: $('.filter-select-department').val()
        }, function(r){
            init_content(r);
        });
    });

    // изменяет фильтр типа работ
    $('.filter-select-worktype').change(function(){
        $.get('set_filter_query_worktype', {
            id: $('.filter-select-worktype').val()
        }, function(r){
            init_content(r);
        });
    });

    // изменяет фильтр улиц
    $('.filter-select-street').change(function(){
        $.get('set_filter_query_street', {
            id: $('.filter-select-street').val()
        }, function(r){
            init_content(r);
        });
    });

    // изменяет фильтр домов
    $('.filter-select-house').change(function(){
        $.get('set_filter_query_house', {
            id: $('.filter-select-house').val()
        }, function(r){
            init_content(r);
        });
    });

    // сбрасывает фильтры
    $('.clear_filter_query').click(function(){
        $.get('clear_filter_query', {
        }, function(r){
            init_content(r);
        });
    });
{% endblock js %}
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
                    <div class="row form-group"><label class="control-label col-xs-1">с</label><div class="col-xs-10"><input type="text" class="form-control query_time_begin" value="{{ filters.time_begin|date('d.m.Y') }}"></div></div>
                    <div class="row form-group"><label class="control-label col-xs-1">по</label><div class="col-xs-10"><input type="text" class="form-control query_time_end" value="{{ filters.time_end|date('d.m.Y') }}"></div></div>
                </li>
                <li class="form-group">
                    <label>по статусу заявки</label>
                    <select class="filter-status form-control">
                        <option value="all">Все заявки</option>
                        {% set statuses = {'open' : 'Открытые заявки', 'close' : 'Закрытые заявки', 'reopen' : 'В работе', 'reopen' : 'Переоткрытые', 'working' : 'В работе'} %}
                        {% for key, status in statuses %}
                            <option value="{{ key }}"
                            {% if key == filters.status %}
                             selected
                            {% endif %}
                            >{{status}}</option>
                        {% endfor %}
                    </select>
                </li>
                <li class="form-group">
                    <label>по дому и улице</label>
                    <select class="filter-select-street form-control">
                        <option value="all">Все улицы</option>
                        {% if component.streets != false %}
                            {% for street in component.streets %}
                                <option value="{{street.id}}"{% if street.id == filters.street_id %} selected{% endif %}>{{street.name}}</option>
                            {% endfor %}
                        {% endif %}
                    </select>
                    {% if houses is not empty %}
                        <select class="filter-select-house form-control">
                            <option value="all">Выберите дом...</option>
                            {% for house in houses %}
                            <option value="{{ house.id }}"{% if house.id == filters.house_id %} selected{% endif %}>дом №{{ house.number }}</option>
                            {% endfor %}
                        </select>
                    {% else %}
                        <select class="filter-select-house form-control" disabled="disabled">
                            <option>Ожидание...</option>
                        </select>
                    {% endif %}
                    
                </li>
                <li class="form-group">
                    <label>по участку</label>
                    <select class="filter-select-department form-control">
                        <option value="all">Все участки</option>
                        {% if component.departments != false %}
                            {% for department in component.departments %}
                                <option value="{{ department.id }}"
                                {% if department.id == filters.department_id %}
                                selected
                                {% endif %}
                                >{{ department.name }}</option>
                            {% endfor %}
                        {% endif %}
                    </select>
                </li>
                <li class="form-group">
                    <label>по типу работ</label>
                    <select class="filter-select-worktype form-control">
                        <option value="all">Все работы</option>
                        {% if component.query_work_types != false %}
                            {% for query_work_type in component.query_work_types %}
                                <option value="{{query_work_type.id}}"
                                {% if query_work_type.id == filters.worktype_id %}
                                selected
                                {% endif %}
                                >{{query_work_type.name}}</option>
                            {% endfor %}
                        {% endif %}
                    </select>
                </li>
            </ul>
        </div>
        <div class="col-xs-12 col-sm-9 col-lg-9">
            <ul class="list-unstyled">
                <li>Отчет №1 
                    <div>
                        <a class="btn btn-link" href="/report/report_query_one" target="_blank">Просмотреть</a>
                        <a class="btn btn-link" href="/report/report_query_one_xls" target="_blank">Выгрузить</a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
{% endblock html %}