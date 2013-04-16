{% extends "default.tpl" %}
{% set streets = component.streets %}
{% block component %}
     {% include '@number/build_street_titles.tpl' %}
     <div class="span6" id="filter-numbers" style="position: fixed; right:0;">
         <input type="text" id="search-number" filter="streets">
     </div>
{% endblock component %}