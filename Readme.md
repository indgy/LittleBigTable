# LittleBigTable

LittleBigTable is a simple AlpineJS 'plugin' to make any html table with a remote data source interactive.

It is ideal if you have a large set of data on a remote server and wish to display it piece by piece to your user.

I created this as too many table plugins render the table in an opinionated fashion, I wanted to be able to write the table in HTML and 'sprinkle' the JS to make it interactive.


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
