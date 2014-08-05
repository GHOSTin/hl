<div><a class="get_dialog_add_processing_center">Добавить идентификатор</a></div>
<ul>
{% for center in number.get_processing_centers() %}
  <li center="{{ center.get_id() }}" identifier="{{ center.get_identifier() }}">{{ center.get_name() }} ({{ center.get_identifier() }}) <a class="get_dialog_exclude_processing_center">исключить</a></li>
{% endfor %}