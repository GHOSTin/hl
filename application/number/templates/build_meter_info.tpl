{% set services = {'cold_water':'Холодное водоснабжение',
    'hot_water':'Горячее водоснабжение', 'electrical':'Электроэнергия'} %}
{% set rates = ['однотарифный', 'двухтарифный', 'трехтарифный'] %}
<button class="btn get_dialog_delete_meter">Удалить счетчик и показания</button>
<h5>Основные параметры счетчика</h5>
<ul class="unstyled" style="padding-left:20px">
    <li>Услуга: {{ services[meter.service] }}</li>
    <li>Название: {{ meter.name }} <a class="get_dialog_change_meter">заменить счетчик</a></li>
    <li>Серийный номер: {{ meter.serial }} <a class="get_dialog_edit_serial">изменить</a></li>
    <li>Тарифность: {{ rates[meter.rates - 1] }}</li>
    <li>Разрядность: {{ meter.capacity }}</li>
</ul>
<h5>Параметры поверки счетчика</h5>
<ul class="unstyled" style="padding-left:20px">
    <li>Дата производства: {{ meter.date_release|date('d.m.Y') }} <a class="get_dialog_edit_date_release">изменить</a></li>
    <li>Дата установки: {{ meter.date_install|date('d.m.Y') }} <a class="get_dialog_edit_date_install">изменить</a></li>
    <li>Дата последней поверки: {{ meter.date_checking|date('d.m.Y') }} <a class="get_dialog_edit_date_checking">изменить</a></li>
    <li>Период: {{ meter.period // 12 }} г {{ meter.period % 12 }} мес. <a class="get_dialog_edit_period">изменить</a></li>
    <li>Дата следующей поверки: {{ meter.date_next_checking|date('d.m.Y') }}</li>
</ul>
<h5>Дополнительное описание</h5>
<div style="padding-left:20px">
    Комментарий: {{ meter.comment }} <a class="get_dialog_edit_meter_comment">изменить</a>
</div>