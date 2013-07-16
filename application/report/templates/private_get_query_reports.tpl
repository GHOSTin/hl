{% extends "ajax.tpl" %}
{% block js %}
    $('.report-content').html(get_hidden_content());
{% endblock js %}
{% block html %}
    <h4>Отчеты по заявкам</h4>
    <div class="row-fluid">
        <div class="span3">Фильтры <a>сбросить</a>
            <ul class="unstyled">
                <li>
                    <div>по дате</div>
                    c <input type="text" class="input-small"><br>
                    по <input type="text" class="input-small">
                </li>
                <li>
                    <div>по статусу заявки</div>
                    <select>
                        <option value="all">Все заявки</option>
                    </select>
                </li>
                <li>
                    <div>по дому и улице</div>
                    <select>
                        <option value="all">Все улицы</option>
                    </select>
                    <select>
                        <option value="all">Все дома</option>
                    </select>
                </li>
                <li>
                    <div>по участку</div>
                    <select>
                        <option value="all">Все участки</option>
                    </select>
                </li>
                <li>
                    <div>по типу работ</div>
                    <select>
                        <option value="all">Все работы</option>
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