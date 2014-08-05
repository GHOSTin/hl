{% extends "default.tpl" %}
{% set user = component.user %}
{% block component %}
	<div class="profile">
		<div class="profile-content">
		</div>
	</div>
{% endblock component %}
{% block javascript %}
    <script src="/?js=component.js&p=profile"></script>
{% endblock javascript %}
