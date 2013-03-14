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
        <nav class="timeline">
            {% include '@query/timeline.tpl' %}
        </nav>
        <!-- end timeline, begin queries -->
        <div class="queries">
            {% include '@query/private_get_day.tpl' %}
        </div>
        <!-- end queries -->
    </div>
    <!-- end right block -->
{% endblock component %}