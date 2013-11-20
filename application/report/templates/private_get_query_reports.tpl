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
    <div class="row-fluid">
        <div class="span3">Фильтры <a class="clear_filter_query">сбросить</a>
            <ul class="unstyled filters">
                <li>
                    <div>по дате</div>
                    c <input type="text" class="input-small query_time_begin" value="{{ filters.time_open_begin|date('d.m.Y') }}"><br>
                    по <input type="text" class="input-small query_time_end" value="{{ filters.time_open_end|date('d.m.Y') }}">
                </li>
                <li>
                    <div>по статусу заявки</div>
                    <select class="filter-status">
                        <option value="all">Все заявки</option>
                        {% set statuses = {'open' : 'Открытые заявки', 'close' : 'Закрытые заявки', 'reopen' : 'В работе', 'reopen' : 'Переоткрытые', 'working' : 'В работе'} %}
                        {% for key, status in statuses %}
                            <option value="{{ key }}"{% if key == filters.status %} selected{% endif %}>{{status}}</option>
                        {% endfor %}
                    </select>
                </li>
                <li>
                    <div>по дому и улице</div>
                    <select class="filter-select-street">
                        <option value="all">Все улицы</option>
                        {% if component.streets != false %}
                            {% for street in component.streets %}
                                <option value="{{ street.get_id() }}"{% if street.get_id() == filters.street %} selected{% endif %}>{{ street.get_name() }}</option>
                            {% endfor %}
                        {% endif %}
                    </select>
                    {% if houses is not empty %}
                        <select class="filter-select-house">
                            <option value="all">Выберите дом...</option>
                            {% for house in houses %}
                            <option value="{{ house.get_id() }}"{% if house.get_id() == filters.house %} selected{% endif %}>дом №{{ house.get_number() }}</option>
                            {% endfor %}
                        </select>
                    {% else %}
                        <select class="filter-select-house" disabled="disabled">
                            <option>Ожидание...</option>
                        </select>
                    {% endif %}
                    
                </li>
                <li>
                    <div>по участку</div>
                    <select class="filter-select-department">
                        <option value="all">Все участки</option>
                    {% for department in component.departments %}
                        <option value="{{ department.get_id() }}"{% if department.get_id() == filters.department[0] %} selected{% endif %}>{{ department.get_name() }}</option>
                    {% endfor %}
                    </select>
                </li>
                <li>
                    <div>по типу работ</div>
                    <select class="filter-select-worktype">
                        <option value="all">Все работы</option>
                    {% for query_work_type in component.query_work_types %}
                        <option value="{{ query_work_type.get_id() }}"{% if query_work_type.get_id() == filters.work_type %} selected{% endif %}>{{ query_work_type.get_name() }}</option>
                    {% endfor %}
                    </select>
                </li>
            </ul>
        </div>
        <div class="span9">
            <ul class="unstyled">
                <li>Отчет №1 
                    <div>
                        <a href="/report/report_query_one" target="_blank">Просмотреть</a> <a href="/report/report_query_one_xls" target="_blank">Выгрузить</a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
{% endblock html %}