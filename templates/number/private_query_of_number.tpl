{% extends "default.tpl" %}

{% set number = response.number %}

{% block component %}
<h2>Заявки на дом {{ number.get_flat().get_house().get_street.get_name() }}, №{{ number.get_flat().get_house().get_number() }}, кв. {{ number.get_flat().get_number() }}, №{{ number.get_number() }}</h2>
<ul>
  {% for query in number.get_queries %}
    <li>{% include 'number/build_query.tpl' %}</li>
  {% endfor %}
</ul>
{% endblock %}