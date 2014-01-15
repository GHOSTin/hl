{% set services = {'cold_water':'Холодное водоснабжение',
    'hot_water':'Горячее водоснабжение', 'electrical':'Электроэнергия'} %}
{% set rates = ['однотарифный', 'двухтарифный', 'трехтарифный'] %}
{% set places = {'kitchen':'Кухня', 'toilet':'Туалет', 'bathroom':'Ванна'} %}
{% set statuses = {'enabled':'Активный', 'disabled':'Отключен'} %}
<button class="btn btn-default get_dialog_delete_meter">Удалить счетчик и показания</button>
<h5>Основные параметры счетчика</h5>
<ul class="unstyled" style="padding-left:20px">
    <li>Название: {{ services[meter.get_service()] }} {{ meter.get_name() }} <a class="get_dialog_change_meter">изменить</a></li>
    <li>Статус: {{ statuses[meter.get_status()] }} <a class="get_dialog_edit_meter_status">изменить</a></li>
    {% if meter.get_service() == 'cold_water' or meter.get_service() == 'hot_water' %}
    <li>Место установки: {{ places[meter.get_place()] }} <a class="get_dialog_edit_meter_place">изменить</a></li>
    {% endif %}
    <li>Серийный номер: {{ meter.get_serial() }} <a class="get_dialog_edit_serial">изменить</a></li>
    <li>Тарифность: {{ rates[meter.get_rates() - 1] }}</li>
    <li>Разрядность: {{ meter.get_capacity() }}</li>
</ul>
<h5>Параметры поверки счетчика</h5>
<ul class="unstyled" style="padding-left:20px">
    <li>Дата производства: {{ meter.get_date_release()|date('d.m.Y') }} <a class="get_dialog_edit_date_release">изменить</a></li>
    <li>Дата установки: {{ meter.get_date_install()|date('d.m.Y') }} <a class="get_dialog_edit_date_install">изменить</a></li>
    <li>Дата последней поверки: {{ meter.get_date_checking()|date('d.m.Y') }} <a class="get_dialog_edit_date_checking">изменить</a></li>
    <li>Период: {{ meter.get_period() // 12 }} г {{ meter.get_period() % 12 }} мес. <a class="get_dialog_edit_period">изменить</a></li>
    <li>Дата следующей поверки: {{ meter.get_date_next_checking()|date('d.m.Y') }}</li>
</ul>
<h5>Дополнительное описание</h5>
<div style="padding-left:20px">
    Комментарий: {{ meter.get_comment() }} <a class="get_dialog_edit_meter_comment">изменить</a>
</div>