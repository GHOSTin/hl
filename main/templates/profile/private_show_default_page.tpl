{% extends "default.tpl" %}
{% set user = response.user %}
{% block component %}
	<div class="profile">
		<div class="profile-content">
		</div>
	</div>
{% endblock component %}
{% block javascript %}
    <script src="/js/profile.js"></script>
{% endblock javascript %}
