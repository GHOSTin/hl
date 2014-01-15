{% extends "default.tpl" %}
{% block component %}
    <!-- begin left block -->
    <div class="col-sm-3 col-lg-3">
        <div class="row">
            <div class="btn-group col-xs-12 query_controls">
                {% if rules['createQuery'] == true %}
                <div class="get_dialog_create_query btn btn-default col-xs-8">Создать заявку</div>
                {% endif %}
                <div class="get_search btn btn-default col-xs-4">Поиск</div>
            </div>
            <div  class="page-header col-xs-12 col-lg-10 col-lg-push-1">
                <h4 class="view-toggle-filters">Фильтры
                <small class="pull-right cm clear_filters absolute_hide">сбросить</small></h4>
            </div>
            {% include '@query/filters.tpl' %}
        </div>
    </div>
    <!-- end left block, begin right block -->
    <div class="col-sm-9 col-lg-9">
            <!-- begin timeline -->
            <nav class="timeline row">
                {% include '@query/timeline.tpl' %}
            </nav>
            <!-- end timeline, begin queries -->
            <div class="queries row">
                {% include '@query/query_titles.tpl' %}
            </div>
            <!-- end queries -->
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