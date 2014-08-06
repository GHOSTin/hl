{% extends "ajax.tpl" %}
{% set house = response.house %}
{% block js %}
    $('.house[house = {{ house.get_id() }}]').append(get_hidden_content())
{% endblock js %}
{% block html %}
  <div class="house-content">
    <div class="house-content-menu">
      <ul class="nav nav-pills">
        <li><a class="get_house_numbers">Лицевые счета</a></li>
        <li><a class="get_house_information">Информация</a></li>
      </ul>
    </div>
    <div class="house-content-content">
      {% include '@number/build_number_titles.tpl' %}
    </div>
  </div>
{% endblock html %}