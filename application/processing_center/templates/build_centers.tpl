{% for center in centers %} 
    <li class="processing-center" processing-center="{{ center.id }}">
        <div class="get_processing_center_content processing-center-title">{{ center.name }}</div>
    </li>
{% endfor %}