{% extends "default.tpl" %}
{% block component %}
    <div class="row-fluid">
        <div class="span3">
            <h4>Виды импортов:</h4>
            <ul style="list-style:none">
                <li class="get_dialog_import_street"><a href="#">Импорт улицы</a></li>
                <li class="get_dialog_import_numbers"><a href="#">Импорт лицевых счетов</a></li>
                <li class="get_dialog_import_meters"><a href="#">Импорт счетчиков</a></li>
            </ul>
        </div>
        <div class="span9 import-form"></div>
    </div>
{% endblock component %}
{% block javascript %}
    <script src="/templates/default/js/jquery.ui.widget.js"></script>
    <script src="/templates/default/js/jquery.iframe-transport.js"></script>
    <script src="/templates/default/js/jupload.js"></script>
{% endblock javascript %}