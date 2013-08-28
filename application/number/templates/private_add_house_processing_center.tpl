 {% extends "ajax.tpl" %}
{% set house = component.house %}
{% block js %}
    $('.house[house = {{ house.id }}] .house-content-content').html(get_hidden_content())
{% endblock js %}
{% block html %}
      {% include '@number/build_house_information.tpl' %}
{% endblock html %}