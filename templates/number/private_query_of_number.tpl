{% extends "default.tpl" %}

{% set number = response.number %}

{% block component %}
<h2>Заявки на лицевой счет {{ number.get_flat().get_house().get_street.get_name() }}, №{{ number.get_flat().get_house().get_number() }}, кв. {{ number.get_flat().get_number() }}, №{{ number.get_number() }}</h2>
<ul>
  {% for query in number.get_queries %}
    {% if query.get_initiator() == 'number' %}
    <li>{% include 'number/build_query.tpl' %}</li>
    {% endif %}
  {% endfor %}
</ul>
{% endblock %}