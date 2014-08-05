{% extends "default.tpl" %}
{% set client_queries = component.client_queries %}
{% block component %}
    <div class="alert alert-success">Внимание. Теперь комментарии если они есть в заявке показываются в зеленом квардрате. Теперь нет смысла писать "смотри коментарии".</div>
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
                </h4><a class="pull-right cm clear_filters absolute_hide">сбросить</a>
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
            <div class="client_queries row">
                {% include '@query/build_client_query_titles.tpl' %}
            </div>
            <!-- end timeline, begin queries -->
            <div class="queries row">
                {% include '@query/query_titles.tpl' %}
            </div>
            <!-- end queries -->
    </div>
    <!-- end right block -->
{% endblock component %}
{% block javascript %}
    <script src="/js/query.js"></script>
    <script src="/js/bootstrap-datepicker.js"></script>
{% endblock javascript %}
{% block css %}
    <link rel="stylesheet" href="/css/query.css" >
    <link rel="stylesheet" href="/css/datepicker.css" >
{% endblock css %}