<h5>Общая информация</h5>
<ul class="unstyled">
  <li>ID: {{ house.get_id() }}</li>
  <li>Номер: {{ house.get_number() }}</li>
</ul>
<h5>Процессинговые центры</h5>
<a class="get_dialog_add_house_processing_center">Добавить</a>
<ul class="house2pc">
  {% for h2c in house.get_processing_centers() %}
    {% set center = h2c[0] %}
    <li center="{{ center.get_id() }}">{{ center.get_name() }}({{ h2c[1] }}) <a class="get_dialog_remove_house_processing_center">удалить</a></li>
  {% endfor %}
</ul>