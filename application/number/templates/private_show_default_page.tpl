{% extends "default.tpl" %}
{% set streets = component.streets %}
{% block component %}
     {% include '@number/build_street_titles.tpl' %}
     <div class="span6" id="filter-numbers">
         <input id="search-number" filter="streets">
     </div>
{% endblock component %}