{% extends "default.tpl" %}
{% block component %}
    <!-- begin left block -->
    <div class="span3">
        <div class="row-fluid">
            <div class="btn-group span12" style="margin-left: 4.564102564102564%;">
                {% if rules.createQuery == true %}
                <div class="get_dialog_create_query cm btn span7">Создать заявку</div>
                {% endif %}
                <div class="get_search cm btn span4">Поиск</div>
            </div>
            <div>
                <span class="view-toggle-filters">Фильтры</span>
                <span class="cm clear_filters absolute_hide">сбросить</span>
            </div>
            {% include '@query/filters.tpl' %}
        </div>
    </div>
    <!-- end left block, begin right block -->
    <div class="span9">
        <!-- begin timeline -->
        <nav class="timeline">
            {% include '@query/timeline.tpl' %}
        </nav>
        <!-- end timeline, begin queries -->
        <div class="queries">
            {% include '@query/query_titles.tpl' %}
        </div>
        <!-- end queries -->
    </div>
    <!-- end right block -->
{% endblock component %}
{% block javascript %}
    <script src="/templates/default/js/bootstrap-datepicker.js"></script>
{% endblock javascript %}
{% block css %}
    <link rel="stylesheet" href="/templates/default/css/datepicker.css" >
{% endblock css %}