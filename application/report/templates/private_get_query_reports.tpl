{% extends "ajax.tpl" %}
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
    });

{% endblock js %}
{% block html %}
    <h4>Отчеты по заявкам</h4>
    <div class="row-fluid">
        {{ component.filters.time_begin }}
        <div class="span3">Фильтры <a>сбросить</a>
            <ul class="unstyled">
                <li>
                    <div>по дате</div>
                    c <input type="text" class="input-small query_time_begin" value="{{ "now"|date('d.m.Y') }}"><br>
                    по <input type="text" class="input-small query_time_end" value="{{ "now"|date('d.m.Y') }}">
                </li>
                <li>
                    <div>по статусу заявки</div>
                    <select>
                        <option value="all">Все заявки</option>
                        {% set statuses = {'open' : 'Открытые заявки', 'close' : 'Закрытые заявки', 'reopen' : 'В работе', 'reopen' : 'Переоткрытые', 'working' : 'В работе'} %}
                        {% for key, status in statuses %}
                            <option value="{{ key }}"
                            {% if key == component.filters.status %}
                             selected
                            {% endif %}
                            >{{status}}</option>
                        {% endfor %}
                    </select>
                </li>
                <li>
                    <div>по дому и улице</div>
                    <select>
                        <option value="all">Все улицы</option>
                        {% if component.streets != false %}
                            {% for street in component.streets %}
                                <option value="{{street.id}}"
                                {% if street.id == component.filters.street_id %}
                                    selected
                                {% endif %}
                                >{{street.name}}</option>
                            {% endfor %}
                        {% endif %}
                    </select>
                    <select>
                        <option value="all">Все дома</option>
                    </select>
                </li>
                <li>
                    <div>по участку</div>
                    <select>
                        <option value="all">Все участки</option>
                        {% if component.departments != false %}
                            {% for department in component.departments %}
                                <option value="{{ department.id }}"
                                {% if department.id == component.filters.department_id %}
                                selected
                                {% endif %}
                                >{{ department.name }}</option>
                            {% endfor %}
                        {% endif %}
                    </select>
                </li>
                <li>
                    <div>по типу работ</div>
                    <select>
                        <option value="all">Все работы</option>
                        {% if component.query_work_types != false %}
                            {% for query_work_type in component.query_work_types %}
                                <option value="{{query_work_type.id}}"
                                {% if query_work_type.id == component.filters.worktype_id %}
                                selected
                                {% endif %}
                                >{{query_work_type.name}}</option>
                            {% endfor %}
                        {% endif %}
                    </select>
                </li>
            </ul>
        </div>
        <div class="span9">
            <ul class="unstyled">
                <li>Отчет №1 
                    <div>
                        <a>Просмотреть</a> <a>Выгрузить</a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
{% endblock html %}