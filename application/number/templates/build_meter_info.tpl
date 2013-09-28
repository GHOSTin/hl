{% set services = {'cold_water':'Холодное водоснабжение',
    'hot_water':'Горячее водоснабжение', 'electrical':'Электроэнергия'} %}
{% set rates = ['однотарифный', 'двухтарифный', 'трехтарифный'] %}
{% set places = {'kitchen':'Кухня', 'toilet':'Туалет', 'bathroom':'Ванна'} %}
{% set statuses = {'enabled':'Активный', 'disabled':'Отключен'} %}
<button class="btn get_dialog_delete_meter">Удалить счетчик и показания</button>
<h5>Основные параметры счетчика</h5>
<ul class="unstyled" style="padding-left:20px">
    <li>Статус: {{ statuses[n2m.get_status()] }} <a class="get_dialog_edit_meter_status">изменить</a></li>
    <li>Услуга: {{ services[n2m.get_service()] }}</li>
    {% if n2m.get_service() == 'cold_water' or n2m.get_service() == 'hot_water' %}
    <li>Место установки: {{ places[n2m.get_place()] }} <a class="get_dialog_edit_meter_place">изменить</a></li>
    {% endif %}
    <li>Название: {{ n2m.get_meter().get_name() }} <a class="get_dialog_change_meter">заменить счетчик</a></li>
    <li>Серийный номер: {{ n2m.get_serial() }} <a class="get_dialog_edit_serial">изменить</a></li>
    <li>Тарифность: {{ rates[n2m.get_meter().get_rates() - 1] }}</li>
    <li>Разрядность: {{ n2m.get_meter().get_capacity() }}</li>
</ul>
<h5>Параметры поверки счетчика</h5>
<ul class="unstyled" style="padding-left:20px">
    <li>Дата производства: {{ n2m.get_date_release()|date('d.m.Y') }} <a class="get_dialog_edit_date_release">изменить</a></li>
    <li>Дата установки: {{ n2m.get_date_install()|date('d.m.Y') }} <a class="get_dialog_edit_date_install">изменить</a></li>
    <li>Дата последней поверки: {{ n2m.get_date_checking()|date('d.m.Y') }} <a class="get_dialog_edit_date_checking">изменить</a></li>
    <li>Период: {{ n2m.get_period() // 12 }} г {{ n2m.get_period() % 12 }} мес. <a class="get_dialog_edit_period">изменить</a></li>
    <li>Дата следующей поверки: {{ n2m.get_date_next_checking()|date('d.m.Y') }}</li>
</ul>
<h5>Дополнительное описание</h5>
<div style="padding-left:20px">
    Комментарий: {{ n2m.get_comment() }} <a class="get_dialog_edit_meter_comment">изменить</a>
</div>