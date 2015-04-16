{% extends "ajax.tpl" %}

{% block js %}
	$('.query[query_id = {{ query.get_id() }}] .query-comments').append(get_hidden_content())
{% endblock js %}

{% block html %}
	<ul class="query-sub">
{% if query.get_status() in ['open', 'working', 'reopen'] %}
    <li>
      <a class="get_dialog_add_comment">Оставить комментарий</a>
    </li>
{% endif %}
    <li>
      <ul class="comments">
      {% include 'query/query_comments.tpl'%}
      </ul>
    </li>
	</ul>
{% endblock html %}