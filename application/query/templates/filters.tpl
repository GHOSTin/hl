<!-- begin filters -->
<div class="filters">  
	<!-- begin filter status -->
	<div class="filter">
	    <div class="filter-name">по статусу заявки</div>
	    <div class="filter-content">
	        <label class="view-select">
	        <select class="filter-content-select-status">
	            <option value="all">Все заявки</option>
	            {% set statuses = {'open' : 'Открытые заявки', 'close' : 'Закрытые заявки', 'reopen' : 'В работе', 'reopen' : 'Переоткрытые'} %}
	            {% for key, status in statuses %}
	                <option value="{{key}}" 
	                {% if key == component.filters.status %}
	                 selected
	                {% endif %}
	                >{{status}}</option>
	            {% endfor %}
	        </select></label>
	    </div>
	</div>
	<!-- end filter status, begin filter street -->
	<div class="filter">
	    <div class="filter-name">по улице и дому</div>
	    <div class="filter-content">
	    	<label class="view-select">
	    		<select class="filter-content-select-street">
	    			<option value="">Все улицы</option>
	    			{% if component.streets != false %}
	    				{% for street in component.streets %}
	    					<option value="{{street.id}}">{{street.name}}</option>
	    				{% endfor %}
	    			{% endif %}
	       		</select>
	       	</label>
	    </div>
	</div>
	<!-- end filter street, begin filter department -->
	<div class="filter">
	    <div class="filter-name">по участку</div>
	    <div class="filter-content">
	        <label class="view-select">
	        	<select class="filter-content-select-department">
	        		<option value="">Все участки</option>
	        		{% if component.departments != false %}
	    				{% for department in component.departments %}
	    					<option value="{{department.id}}">{{department.name}}</option>
	    				{% endfor %}
	    			{% endif %}
	        	</select>
	        </label>
	    </div>
	</div>
	<!-- end filter department, begin filter worktype -->
	<div class="filter">
	    <div class="filter-name">по типу работ</div>
	    <div class="filter-content">
	    	<label class="view-select">
	    		<select class="filter-content-select-workType">
	    			<option value="">Все типы</option>
	    			{% if component.query_work_types != false %}
	    				{% for query_work_type in component.query_work_types %}
	    					<option value="{{query_work_type.id}}">{{query_work_type.name}}</option>
	    				{% endfor %}
	    			{% endif %}
	    		</select>
	    	</label>
	    </div>
	</div>
	<!-- end filter worktype, begin filter users -->
	<div class="filter">
	    <div class="filter-name">по пользователю</div>
	    <div class="filter-content">
	    	<label class="view-select">
	    		<select class="filter-content-select-user">
	            	<option value="all">Все пользователи</option>
	    			{% if component.users != false %}
	    				{% for user in component.users %}
	    					<option value="{{user.id}}">{{user.lastname}} {{user.firstname}}</option>
	    				{% endfor %}
	    			{% endif %}
	        	</select>
	        </label>
	    </div>
	</div>
	<!-- end filter users -->
</div>
<!-- end filters -->