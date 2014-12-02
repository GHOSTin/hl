{% extends "default.tpl" %}

{% block component %}
<h2>Заявки на дом {{ house.get_street.get_name() }}, №{{ house.get_number() }}</h2>
<ul>
  {% for query in house.get_queries %}
    <li>{% include 'number/build_query.tpl' %}</li>
  {% endfor %}
</ul>
{% endblock %}