{% for center in centers %} 
    <option value="{{ center.id }}">{{ center.name }}</option>
{% endfor %}