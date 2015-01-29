{% extends "private.tpl" %}

{% block content %}
<div class="content row">{% include 'queries/build_queries_titles.tpl' %}</div>
{% endblock %}

{% block js %}
<script src="/js/queries.js"></script>
{% endblock js %}