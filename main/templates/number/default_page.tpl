{% extends "default.tpl" %}

{% block component %}
    <div class="navbar navbar-default col-xs-12">
        <form class="navbar-form pull-left col-xs-12" id="filter-numbers">
            <span style="display:inline-block">
                <input type="text" class="form-control" id="search-number" filter="streets" autocomplete="off">
            </span>
        </form>
    </div>
    {% include 'number/build_street_titles.tpl' %}
{% endblock component %}

{% block javascript %}
    <script src="/js/number.js"></script>
    <script src="/js/bootstrap-datepicker.js"></script>
    <script src="/js/typeahead.min.js"></script>
    <script src="/js/inputmask.js" type="text/javascript"></script>
    <script src="/js/jquery.inputmask.js" type="text/javascript"></script>
{% endblock javascript %}

{% block css %}
    <link rel="stylesheet" href="/css/number.css" >
    <link rel="stylesheet" href="/css/datepicker.css" >
{% endblock css %}