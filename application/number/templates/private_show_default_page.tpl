{% extends "default.tpl" %}
{% set streets = component.streets %}
{% block component %}
    <div class="navbar span12">
        <div class="navbar-inner">
            <form class="navbar-search pull-left" id="filter-numbers">
                <input type="text" id="search-number" filter="streets" autocomplete="off">
            </form>
        </div>
    </div>
    {% include '@number/build_street_titles.tpl' %}
{% endblock component %}
{% block javascript %}
    <script src="/?js=component.js&p=number"></script>
    <script src="/templates/default/js/bootstrap-datepicker.js"></script>
{% endblock javascript %}
{% block css %}
    <link rel="stylesheet" href="/?css=component.css&p=number" >
    <link rel="stylesheet" href="/templates/default/css/datepicker.css" >
{% endblock css %}