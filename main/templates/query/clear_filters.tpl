{% extends "ajax.tpl" %}

{% block js %}
$('.queries').html(get_hidden_content('._queries'));
$('.timeline').html(get_hidden_content('._timeline'));
$('.filter-content-select-status').val('all');
$('.filter-content-select-street').val('all');
$('.filter-content-select-house').html('<option value="all">Ожидание...</option>');
$('.filter-content-select-house').val('all');
$('.filter-content-select-house').attr('disabled', true);
$('.filter-content-select-department').val('all');
$('.filter-content-select-work_type').val('all');
$('.filter-content-select-query_type').val('all');
{% endblock %}

{% block html %}
<div class="_queries">
  <div class="muted">
    <small>Количество заявок: {{ queries|length }}</small>
  </div>
{% for query in queries %}
  <div class="query get_query_content" query_id="{{ query.get_id() }}">{% include 'query/build_query_title.tpl' %}</div>
{% else %}
  Нет заявок
{% endfor %}
</div>
<div class="_timeline">{% include 'query/timeline.tpl' %}</div>
{% endblock %}