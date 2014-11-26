{% extends "ajax.tpl" %}

{% block js %}
  $('.house[house = {{ house.get_id() }}]').append(get_hidden_content())
{% endblock %}

{% block html %}
<div class="house-content">
  <div class="house-content-content">
  {% include 'number/build_house_content.tpl'%}
  </div>
</div>
{% endblock %}