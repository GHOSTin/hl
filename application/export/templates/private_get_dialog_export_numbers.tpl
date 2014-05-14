{% extends "ajax.tpl" %}
{% set query = component.queries[0] %}
{% block js %}
  $('.export-form').html(get_hidden_content());
{% endblock js %}
{% block html %}
  <h3>Экспорт жилфонда</h3>
  <div>
    <button class="btn btn-default export_numbers" type="button">Экспортировать жилфонд</button>
  </div>
{% endblock html %}