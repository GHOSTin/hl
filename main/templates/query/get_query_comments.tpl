{% extends "ajax.tpl" %}

{% block js %}
	$('.query[query_id = {{ query.get_id() }}] .query-comments').append(get_hidden_content())
{% endblock js %}

{% block html %}
	<ul class="query-sub">
    <li>
      <a class="get_dialog_add_comment cm">Оставить комментарий</a>
    </li>
    <li>
      <ul class="comments">
      {% include 'query/query_comments.tpl'%}
      </ul>
    </li>
	</ul>
{% endblock html %}