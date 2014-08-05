{% extends "ajax.tpl" %}
{% set query = component.queries[0] %}
{% block js %}
  $('.export-form').html(get_hidden_content());
{% endblock js %}
{% block html %}
  <h3>Экспорт жилфонда</h3>
  <div>
    <a class="btn btn-default export_numbers" href="/export/export_numbers">Экспортировать жилфонд</a>
  </div>
{% endblock html %}