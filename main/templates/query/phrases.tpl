<option></option>
{% for phrase in workgroup.get_phrases() %}
  <option>{{ phrase.get_text() }}</option>
{% endfor %}