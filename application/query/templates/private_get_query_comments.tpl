{% extends "ajax.tpl" %}
{% set query = component.query %}
{% block js %}
	$('.query[query_id = {{ query.get_id() }}] .query-comments').append(get_hidden_content())
{% endblock js %}
{% block html %}
	<ul class="query-sub">
    <li>
      <a class="get_dialog_add_comment cm">Оставить комментарий</a>
    </li>
    <li>
      <ul>
      {% for comment in query.get_comments() %}
        <li>{{ comment.get_message() }}</li>
      {% endfor %}
      </ul>
    </li>
	</ul>
{% endblock html %}