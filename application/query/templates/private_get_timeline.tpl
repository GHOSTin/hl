{% extends "ajax.tpl" %}
{% if component.queries != false %}
	{% block js %}
	//alert($('._hidden_content ._queries').html());
		$('.queries').html($('._hidden_content ._queries').html());
		$('.timeline').html($('._hidden_content ._timeline').html());
	{% endblock js %}
	{% block html %}
	<div class="_timeline">
		{% include '@query/timeline.tpl' %}
	</div>
	<div class="_queries">
		{% include '@query/private_get_day.tpl' %}
	</div>
	{% endblock html %}
{% endif %}