{% set centers = component.centers %}
<div><a class="get_dialog_add_processing_center">Добавить идентификатор</a></div>
<ul>
    {% for center in centers %}
        <li center="{{ center.processing_center_id}}" identifier="{{ center.identifier}}">{{ center.processing_center_name }} ({{ center.identifier }}) <a class="get_dialog_exclude_processing_center">исключить</a></li>
    {% endfor %}
</ul>