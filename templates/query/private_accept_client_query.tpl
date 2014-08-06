{% extends "ajax.tpl" %}
{% set client_queries = response.client_queries %}
{% set queries = response.queries %}
{% block js %}
	$('.client_queries').html($('._hidden_content .client_queries').html());
  $('.queries').html($('._hidden_content .queries').html());
{% endblock js %}
{% block html %}
  <div class="client_queries">
    {% include '@query/build_client_query_titles.tpl' %}
  </div>
  <div class="queries">
    {% include '@query/query_titles.tpl'%}
  </div>
{% endblock html %}