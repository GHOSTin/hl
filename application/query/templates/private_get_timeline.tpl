{% extends "ajax.tpl" %}
{% if component.queries != false %}
	{% block js %}
		$('.queries').html($('._hidden_content ._queries').html());
		$('.timeline').html($('._hidden_content ._timeline').html());
	{% endblock js %}
	{% block html %}
	<div class="_timeline">
		{% include '@query/timeline.tpl' %}
	</div>
	<div class="_queries">
		{% include '@query/query_titles.tpl' %}
	</div>
	{% endblock html %}
{% endif %}