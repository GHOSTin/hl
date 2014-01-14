{% extends "default.tpl" %}
{% block component %}
    <!-- begin left block -->
    <div class="span3">
        <div class="row-fluid">
            <div class="btn-group span12 query_controls">
                {% if rules['createQuery'] == true %}
                <div class="get_dialog_create_query cm btn span8">Создать заявку</div>
                {% endif %}
                <div class="get_search cm btn span4">Поиск</div>
            </div>
            <div  class="page-header span12">
                <h4 class="view-toggle-filters">Фильтры
                <small class="pull-right cm clear_filters absolute_hide">сбросить</small></h4>
            </div>
            {% include '@query/filters.tpl' %}
        </div>
    </div>
    <!-- end left block, begin right block -->
    <div class="span9">
        <div class="row-fluid">
            <!-- begin timeline -->
            <nav class="timeline row-fluid">
                {% include '@query/timeline.tpl' %}
            </nav>
            <!-- end timeline, begin queries -->
            <div class="queries row-fluid">
                {% include '@query/query_titles.tpl' %}
            </div>
            <!-- end queries -->
        </div>
    </div>
    <!-- end right block -->
{% endblock component %}
{% block javascript %}
    <script src="/?js=component.js&p=query"></script>
    <script src="/templates/default/js/bootstrap-datepicker.js"></script>
{% endblock javascript %}
{% block css %}
    <link rel="stylesheet" href="/?css=component.css&p=query" >
    <link rel="stylesheet" href="/templates/default/css/datepicker.css" >
{% endblock css %}