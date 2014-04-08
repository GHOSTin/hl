{% extends "default.tpl" %}
{% block component %}
    <div class="row">
        <div class="col-md-3">
            <h4>Виды импортов:</h4>
            <ul class="list-unstyled">
                <li class="get_dialog_import_street"><a href="#">Импорт улицы</a></li>
                <li class="get_dialog_import_house"><a href="#">Импорт дома</a></li>
                <li class="get_dialog_import_flats"><a href="#">Импорт квартир</a></li>
                <li class="get_dialog_import_numbers"><a href="#">Импорт лицевых счетов</a></li>
                <li class="get_dialog_import_accruals"><a href="#">Импорт начислений</a></li>
            </ul>
        </div>
        <div class="col-md-9 import-form"></div>
    </div>
{% endblock component %}
{% block javascript %}
    <script src="/templates/default/js/jquery.ui.widget.js"></script>
    <script src="/templates/default/js/jquery.iframe-transport.js"></script>
    <script src="/templates/default/js/jupload.js"></script>
    <script src="/templates/default/js/bootstrap-datepicker.js"></script>
    <script src="/?js=component.js&p=import"></script>
{% endblock javascript %}