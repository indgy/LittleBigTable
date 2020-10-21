objectives

plain table using html to layout

additional features - search, column filter, pagination are optional.


js handles fetch and population of state
js handles sorting



<table x-data="littleBigTable">
	<thead>
		<tr>
			<td>
				Name
				<button type="button" class="toggle" x-html="getSortIcon('name')" @click="sortBy('name')"></button>
			</td>
		</tr>
	</thead>
	<tfoot>
	</tfoot>
	<tbody>
		<template>
			<tr x-for="row in data">
			
			</tr>
		</template>
	</tbody>
</table>

return {
	data: {
		// stores the rows
	},
	formatters: function(column) {
		// special field formatters
	},
	search: {
		// stores the search query
		param: 'search',
		
	},	
	sort: {
		// stores the columns being sorted
		// e.g. column: dir
		param: 'sort',
	},
	sortBy: function(name) {
		// handles the change of sort
	},
	fetch: function() {
		// fetch and populate data using current state for filter/search
	},
	getSortIcon: function(name) {
		// checks for name in sort and displays the correct sort icon
	}
	
}
