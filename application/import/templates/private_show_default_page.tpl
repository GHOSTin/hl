{% extends "default.tpl" %}
{% block component %}
    <div class="row-fluid">
        <div class="span3">
            <h4>Виды импортов:</h4>
            <ul style="list-style:none">
                <li class="get_dialog_import_numbers"><a href="#">Импорт лицевых счетов</a></li>
                <li class="get_dialog_import_meters"><a href="#">Импорт счетчиков</a></li>
            </ul>
        </div>
        <div class="span9 import-window"></div>
    </div>
{% endblock component %}