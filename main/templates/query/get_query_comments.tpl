{% extends "ajax.tpl" %}

{% block js %}
	$('.query[query_id = {{ query.get_id() }}]').find('.query-comments .ibox-content').append(get_hidden_content())
{% endblock js %}

{% block html %}
{% if query.get_status() in ['open', 'working', 'reopen'] %}
  <div class="m-b m-t-xs">
      <a class="btn btn-white btn-outline btn-w-m get_dialog_add_comment">Оставить комментарий</a>
  </div>
{% endif %}
    <div class="comments feed-activity-list">
    {% include 'query/query_comments.tpl'%}
    </div>
{% endblock html %}