{% for outage in outages %}
<li class="outage" outage="{{ outage.get_id() }}" style="padding-top:20px">{% include "outage/outage.tpl" %}</li>
{% endfor %}