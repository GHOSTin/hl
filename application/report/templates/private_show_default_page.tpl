{% extends "default.tpl" %}
{% block component %}
<div class="row-fluid">
    <div class="span2">
        <h4>Виды отчетов</h4>
        <ul class="unstyled">
            <li><a class="get_query_reports">Отчеты по заявкам</a></li>
        </ul>
    </div>
    <div class="span10 report-content"></div>
</div>
{% endblock component %}
{% block javascript %}
    <script src="/templates/default/js/bootstrap-datepicker.js"></script>
{% endblock javascript %}
{% block css %}
    <link rel="stylesheet" href="/templates/default/css/datepicker.css" >
{% endblock css %}