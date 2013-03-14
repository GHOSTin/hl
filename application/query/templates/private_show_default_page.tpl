{% extends "default.tpl" %}
{% block component %}
    <!-- begin left block -->
    <div style="display:inline-block; vertical-align:top;">
        {% if rules.createQuery == true %}
        <div class="get_dialog_create_query cm">Создать заявку</div>
        {% endif %}
        <div class="get_search cm">Поиск</div>
        <div>
            <span class="view-toggle-filters">Фильтры</span>
            <span class="cm clear_filters absolute_hide">сбросить</span>
        </div> 
        {% include '@query/filters.tpl' %}
    </div>
    <!-- end left block, begin right block -->
    <div style="display:inline-block; vertical-align:top;">
        <!-- begin timeline -->
        {% set day = timeline %}
        {% set month = timeline|date('n') %}
        {% set months = {1 : 'Январь', 2 : 'Февраль', 3 : 'Март', 4: 'Апрель', 
            5 : 'Май', 6 : 'Июнь', 7 : 'Июль', 8 : 'Август', 9 : 'Сентябрь',
            10 : 'Октябрь', 11 : 'Ноябрь', 12 : 'Декабрь'} %}
        <nav class="timeline">
            <div class="timeline-month">
                {% if month in months|keys %}
                    {{months[month]}}
                {% endif %}
                {{timeline|date('Y')}}</div>
            {% for i in range(1, timeline|date('t')) %}
                <div class="timeline-day" time="{{day}}">{{i}}</div>
                {% set day = day + 86400 %}
            {% endfor %}
        </nav>
        <!-- end timeline, begin queries -->
        <div class="queries">
            {% include '@query/private_get_day.tpl' %}
        </div>
        <!-- end queries -->
    </div>
    <!-- end right block -->
{% endblock component %}