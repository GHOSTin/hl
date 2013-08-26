{% extends "ajax.tpl" %}
{% set numbers = component.numbers %}
{% block js %}
    $('.house[house = {{component.house.id}}]').append(get_hidden_content())
{% endblock js %}
{% block html %}
  <div class="house-content">
    <div class="house-content-menu">
      <ul class="nav nav-pills">
        <li><a class="get_house_numbers">Лицевые счета</a></li>
        <li><a class="get_house_processing_centers">Процессинговые центры</a></li>
      </ul>
    </div>
    <div class="house-content-content">
      {% include '@number/build_number_titles.tpl' %}
    </div>
  </div>
{% endblock html %}