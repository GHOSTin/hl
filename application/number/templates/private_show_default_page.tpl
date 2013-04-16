{% extends "default.tpl" %}
{% set streets = component.streets %}
{% block component %}
    <div class="navbar span6" style="position: fixed;">
        <div class="navbar-inner">
            <form class="navbar-search pull-left" id="filter-numbers">
                <input type="text" id="search-number" filter="streets">
            </form>
        </div>
    </div>
    {% include '@number/build_street_titles.tpl' %}
{% endblock component %}