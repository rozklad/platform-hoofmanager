<script type="text/template" data-grid="apilog" data-template="results">

	<% _.each(results, function(r) { %>

		<tr data-grid-row>
			<td><input content="id" input data-grid-checkbox="" name="entries[]" type="checkbox" value="<%= r.id %>"></td>
			<td><a href="<%= r.edit_uri %>" href="<%= r.edit_uri %>"><%= r.id %></a></td>
			<td><%= r.method %></td>
			<td><%= r.call %></td>
			<td><%= r.data %></td>
			<td><%= r.status %></td>
			<td><%= r.source %></td>
			<td><%= r.created_at %></td>
		</tr>

	<% }); %>

</script>
