function littleTable() {
    return {
        config: {
            url: 'http://localhost:8080/json.php',
            key_prefix: 'littleTable',
            multisort: false,
            messages: {
                loading: 'Loading...',
                failed: 'Loading failed',
                summary: 'rows'
            },
            formatters: {
                'state': function(value, row) {
                    return '<strong>' + value + '</strong>';
                },
                'turbine_capacity': function(value, row) {
                    if (parseInt(value) < 1500) {
                        return '<span class="has-text-warning has-text-weight-medium">' + value  + "</span>";
                    }
                    if (parseInt(value) > 2000) {
                        return '<span class="has-text-success has-text-weight-medium">' + value  + "</span>";
                    }
                    return '<span class="has-text-link has-text-weight-medium">' + value  + "</span>";
                }
            },
        },
        meta: {
            loading: false,
            status: null,
        },
        params: {
            limit: 15,
            offset: 0,
            search: null,
            total: 0,
        },
        rows: [
            // stores the rows
        ],
        sort: {
            // stores the columns being sorted
            // e.g. column: dir
        },
        init: function() {
            // set preferences from localStorage
            this.params.limit = localStorage.getItem(this.config.key_prefix + '.limit');
            if (this.params.limit < 10 || this.params.limit > 100) {
                this.params.limit = 25;
            }
            this.fetch();
        },
        fetch: function() {
            // fetch and populate data using current state for filter/search
            this.meta.loading = true;
            this.setStatus(this.config.messages.loading);
            fetch(this.config.url + this.getUrlParams(), {
                headers: {
                    'Origin': 'http://localhost:8080',
                    'Content-type': 'application/x-www-form-urlencoded'
                }
            }).then(response => response.json()).then(json => {
                  this.rows = [];
                  this.params.total = json.total;
                  for (i in json.data){
                      this.addRow(json.data[i]);
                  }
              }).then(() => {
                  this.meta.loading = false;
                  this.setStatus(this.getSummary(this.config.messages.summary));
              }).catch(error => {
                  console.error('Network fetch failed:', error);
                  this.setStatus(this.config.messages.failed);
              });
        },
        addRow: function(data) {
            // todo check for field formatter by name
            let row = {};
            for (i in data) {
                if (typeof this.config.formatters[i] == 'function') {
                    fn = this.config.formatters[i];
                    row[i] = fn(data[i], data);
                } else {
                    row[i] = data[i];
                }
            }
            this.rows.push(row);
        },
        getUrlParams: function() {
            let str = '?limit='+this.params.limit+'&offset='+this.params.offset;
            if (this.params.search) {
                str+= '&search='+this.params.search;
            }

            let sort = null;
            for (i in this.sort) {
                sort = i + ':' + this.sort[i];
            }
            if (sort) {
                str+= '&sort='+sort;
            }

            return str;
        },
        getCurrentPage: function() {
            if (this.params.offset == 0) {
                return 1;
            }
            return parseInt(parseInt(this.params.offset) / parseInt(this.params.limit) + 1);
        },
        getTotalPages: function() {
            return parseInt(Math.ceil(parseInt(this.params.total) / parseInt(this.params.limit)));
        },
        getTotalRows: function() {
            return parseInt(this.params.total);
        },
        getFirstPageOffset: function() {
            return 0;
        },
        getPrevPageOffset: function() {
            let int = parseInt(parseInt(this.getCurrentPage() - 2) * parseInt(this.params.limit));
            return (int < 0)  ? 0 : int;
        },
        getNextPageOffset: function() {
            let int = parseInt(parseInt(this.getCurrentPage()) * parseInt(this.params.limit));
            return int;
        },
        getLastPageOffset: function() {
            let int = parseInt(parseInt(this.getTotalPages() - 1) * parseInt(this.params.limit));
            return (int < 0)  ? 0 : int;
        },
        getOffsetForPage: function() {
            // determine correct offset boundary for the current page
            // loop through pages, if (offset between prev and next) recalculate
            for (i=0; i<parseInt(this.params.total); i+=parseInt(this.params.limit)) {
                if (i >= this.getPrevPageOffset() && i <= this.getNextPageOffset()) {
                    return parseInt(i) + parseInt(this.params.limit);
                }
            }
            return this.getLastPageOffset();
        },
        getFirstDisplayedRow: function() {
            return this.params.offset + 1;
        },
        getLastDisplayedRow: function() {
            let int = parseInt(this.params.offset) + parseInt(this.params.limit);
            if (int > this.params.total) {
                int = this.params.total;
            }
            return int;
        },
        getSummary: function(type='rows', name='results') {
            if ( ! this.rows.length) {
                return 'No results';
            }
            if (type.toLowerCase() == 'pages') {
                return 'Showing page <strong>' + this.getCurrentPage() + '</strong> of <strong>' + this.getTotalPages() + '</strong>';
            }
            return 'Showing <strong>' + this.getFirstDisplayedRow() + '</strong> to <strong>' + this.getLastDisplayedRow() + '</strong> of <strong>' + this.getTotalRows() + '</strong> ' + name;
        },
        getSortIcon: function(col) {
            // checks for name in sort and displays the correct sort icon
            let str = '<svg width="20px" height="20px" viewBox="0 0 200 200" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">';
            if (undefined == this.sort[col]) {
                str+= '<title>Click to sort</title><g id="sort-none" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><polygon id="desc" fill="#979797" transform="translate(100.000000, 140.000000) scale(1, -1) translate(-100.000000, -140.000000) " points="100 110 160 170 40 170"></polygon><polygon id="asc" fill="#979797" points="100 30 160 90 40 90"></polygon></g>';
            } else {
                if (this.sort[col] == 'asc') {
                    str+= '<title>Sort ascending</title><g id="sort-asc" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><polygon id="asc" fill="#979797" points="100 30 160 90 40 90"></polygon></g>';
                } else {
                    str+= '<title>Sort descending</title><g id="sort-desc" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><polygon id="desc" fill="#979797" transform="translate(100.000000, 140.000000) scale(1, -1) translate(-100.000000, -140.000000) " points="100 110 160 170 40 170"></polygon></g>';
                }
            }
            str+= '</svg>';
            return str;
        },
        setLimit: function() {
            // sanity check input
            if (this.params.limit < 10 || this.params.limit > 100) {
                this.params.limit = 25;
            }
            // reset offset and fetch
            // determine current position, if greater than last page, go to last page
            // get currentpageoffset
            this.params.offset = this.getOffsetForPage();
            // store preference
            localStorage.setItem('littleTable.limit', this.params.limit);
            this.fetch();
        },
        setStatus: function(str) {
            this.meta.status = str;
        },
        toggleSortColumn: function(col) {
            if (undefined == this.sort[col]) {
                this.sort[col] = 'asc';
            } else if (this.sort[col] == 'asc') {
                this.sort[col] = 'dsc';
            } else if (this.sort[col] == 'dsc') {
                delete this.sort[col];
            }
        },
        goFirstPage: function() {
            this.params.offset = this.getFirstPageOffset();
            this.fetch();
        },
        goLastPage: function() {
            this.params.offset = this.getLastPageOffset();
            this.fetch();
        },
        goNextPage: function() {
            this.params.offset = this.getNextPageOffset();
            this.fetch();
        },
        goPrevPage: function() {
            this.params.offset = this.getPrevPageOffset();
            this.fetch();
        },
        goToPage: function() {
            // todo jump to a particular page number
        },
        doSearch: function() {
            this.params.offset = 0;
            this.fetch();
        },
        doSort: function(col) {
            if (false == this.config.multisort) {
                let state = this.sort[col];
                this.sort = {};
                this.sort[col] = state;
            }
            this.toggleSortColumn(col);
            this.fetch();
        },
        dd: function() {
            return JSON.stringify(this.params) + JSON.stringify(this.meta) + JSON.stringify(this.sort);

        }
    }
}