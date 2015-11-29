{% extends "ajax.tpl" %}

{% block js %}
	$('.query[query_id = {{ query.get_id() }}]').find('.query-works .ibox-content').append(get_hidden_content())
{% endblock js %}

{% block html %}
  {% if query.get_status() in ['open', 'working', 'reopen'] %}
    <div class="m-t-xs">
      <a class="btn btn-default btn-outline btn-w-m get_dialog_add_work">добавить</a>
    </div>
  {% endif %}
  <ol class="works">
    {% include 'query/query_works.tpl' %}
  </ol>
{% endblock html %}