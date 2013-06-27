{% extends "default.tpl" %}
{% set letters = component.letters %}
{% block component %}
    <div class="letters">
    {% include '@user/build_user_letters.tpl' %}
    </div>
{% endblock component %}