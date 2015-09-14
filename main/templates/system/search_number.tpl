{% extends "default.tpl" %}

{% block component %}
<div class="row">
  <div class="col-md-12">
    <h2>Результаты поиска по номеру {{ search }}</h2>
    {% if number %}
      {{ number.get_flat().get_house().get_street().get_name() }}, дом №{{ number.get_flat().get_house().get_number() }}, кв.№ {{ number.get_flat().get_number() }}, {{ number.get_fio() }}
    {% else %}
      Ничего не найдено
    {% endif %}
    <div>
      <a href="/system/search/number/">искать еще</a>
    </div>
  </div>
</div>
{% endblock %}