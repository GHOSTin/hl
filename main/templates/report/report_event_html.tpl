{% extends "print.tpl" %}

{% block css %}
<style>
  table tr td{
    border:1px solid black;
    vertical-align: top;
    white-space: nowrap;
  }
</style>
{% endblock %}

{% block component %}
<table>
  <tr>
    <td>Дата</td>
    <td>Событие</td>
    <td>улица</td>
    <td>дом</td>
    <td>квартира</td>
    <td>№ счета</td>
  </tr>
{% for event in events %}
  {% set number = event.get_number() %}
  <tr>
    <td>{{ event.get_time()|date("d.m.y") }}</td>
    <td>{{ event.get_name() }}</td>
    <td>{{ number.get_flat().get_house().get_street().get_name() }}</td>
    <td>{{ number.get_flat().get_house().get_number() }}</td>
    <td>{{ number.get_flat().get_number() }}</td>
    <td>{{ number.get_number() }}</td>
  </tr>
{% endfor %}
</table>
{% endblock %}