{% extends "default.tpl" %}
{% set streets = component.streets %}
{% block component %}
    <div class="navbar col-12">
        <form class="navbar-form pull-left col-12" id="filter-numbers">
            <span style="display:inline-block">
                <input type="text" class="form-control" id="search-number" filter="streets" autocomplete="off">
            </span>
        </form>
    </div>
    {% include '@number/build_street_titles.tpl' %}
{% endblock component %}
{% block javascript %}
    <script src="/templates/default/js/bootstrap-datepicker.js"></script>
    <script src="/templates/default/js/typeahead.min.js"></script>
{% endblock javascript %}
{% block css %}
    <link rel="stylesheet" href="/templates/default/css/datepicker.css" >
{% endblock css %}