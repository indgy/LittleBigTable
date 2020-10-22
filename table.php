<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.1/css/bulma.min.css">
  <title>'littleTable' example</title>
  <meta name="description" content="An simple javascript interactive table built using Bulma and AlpineJS" />
</head>
<body>
    <section class="section">
        <div class="container">
            <h1 class="title is-3">LittleTable</h1>
            <div class="little-table" x-data="littleTable()" x-init="init()">
                <div class="level">
                    <div class="level-left">

                        <div class="field is-horizontal">
                            <div class="field-label is-normal">
                                <label class="label">Search</label>
                            </div>
                            <div class="field-body">
                                <div class="field is-narrow">
                                    <div class="control">
                                        <input class="input" type="text" placeholder="Start typing..." x-model="params.search" @keyup.debounce.350="doSearch()">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="level-right">
                        
                        <div class="field is-horizontal">
                            <div class="field-label is-normal">
                                <label class="label">Show</label>
                            </div>
                            <div class="field-body">
                                <div class="field is-narrow">
                                    <div class="control">
                                        <div class="select is-fullwidth">
                                            <select @change="setLimit()" x-model="params.limit">
                                                <option value="10">10 per page</option>
                                                <option value="15">15 per page</option>
                                                <option value="25">25 per page</option>
                                                <option value="50">50 per page</option>
                                                <option value="100">100 per page</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <table class="table is-fullwidth">
                    <thead>
                        <tr>
                            <th class="has-text-left">State <button class="button is-small" type="button" x-html="getSortIcon('state')" @click="doSort('state')"></th>
                            <th class="has-text-left">County</th>
                            <th>Year <button class="button is-small" type="button" x-html="getSortIcon('year')" @click="doSort('year')"></button></th>
                            <th>Capacity</th>
                            <th>Turbines</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="row in rows">
                            <tr>
                                <td x-text="row.state"></td>
                                <td x-text="row.county"></td>
                                <td class="has-text-centered" x-text="row.year"></td>
                                <td class="has-text-centered" x-text="row.turbine_capacity"></td>
                                <td class="has-text-centered" x-text="row.project_capacity"></td>
                            </tr>
                        </template>
                    </tbody>
                </table>
                <div class="level">
                    <div class="level-left">
                        <p x-html="meta.status"></p>
                        <!-- <p x-html="getPagerSummaryPages()"></p> -->
                        <!-- <p x-html="getPagerSummaryRows('rows')"></p> -->
                    </div>
                    <div class="level-right">
                        <nav class="pagination" role="navigation" aria-label="pagination">
                            <ul class="pagination-list">
                                <li>
                                    <a class="pagination-link" aria-label="Goto first page" @click="goFirstPage()" :disabled="getCurrentPage() == 1">|&lt;</a>
                                </li>
                                <li>
                                    <a class="pagination-previous" @click="goPrevPage()" :disabled="getCurrentPage() == 1">&lt;</a>
                                </li>
                                <li>
                                    
                                </li>
                                <li>
                                    <a class="pagination-next" @click="goNextPage()" :disabled="getCurrentPage() == getTotalPages()">&gt;</a>
                                </li>
                                <li>
                                    <a class="pagination-link" aria-label="Goto last page" @click="goLastPage()" :disabled="getCurrentPage() == getTotalPages()">&gt;|</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <p x-html="dd()"></p>
            </div>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.7.1/dist/alpine.min.js" defer></script>
    <script>
    function littleTable() {
        return {
            config: {
                url: 'http://localhost:8080/json.php',
                key_prefix: 'littleTable',
                multisort: false,
                messages: {
                    loading: 'Loading...',
                    failed: 'Loading data failed',
                    summary: 'rows'
                }
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
            formatters: function(column) {
                // special field formatters
                
            },
            getUrlParams: function() {
                let str = '?limit='+this.params.limit+'&offset='+this.params.offset;
                if (this.params.search) {
                    str+= '&search='+this.params.search;
                }
                if (this.params.sort) {
                    str+= '&sort='+this.params.sort;
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
                int = parseInt(parseInt(this.getCurrentPage() - 2) * parseInt(this.params.limit));
                return (int < 0)  ? 0 : int;
            },
            getNextPageOffset: function() {
                return parseInt(parseInt(this.getCurrentPage()) * parseInt(this.params.limit));
            },
            getLastPageOffset: function() {
                int = parseInt(parseInt(this.getTotalPages() - 1) * parseInt(this.params.limit));
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
                int = parseInt(this.params.offset) + parseInt(this.params.limit);
                if (int > this.params.total) {
                    return this.params.total;
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
                let none = '<title>Click to sort</title><g id="sort-none" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><polygon id="desc" fill="#979797" transform="translate(100.000000, 140.000000) scale(1, -1) translate(-100.000000, -140.000000) " points="100 110 160 170 40 170"></polygon><polygon id="asc" fill="#979797" points="100 30 160 90 40 90"></polygon></g>';
                let dsc = '<title>Sort descending</title><g id="sort-desc" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><polygon id="desc" fill="#979797" transform="translate(100.000000, 140.000000) scale(1, -1) translate(-100.000000, -140.000000) " points="100 110 160 170 40 170"></polygon></g>';
                let asc = '<title>Sort ascending</title><g id="sort-asc" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><polygon id="asc" fill="#979797" points="100 30 160 90 40 90"></polygon></g>';
                let img = (this.sort[col] == 'asc') ? asc : dsc;
                if (undefined == this.sort[col]) {
                    img = none;
                }
                str = '<img alt="" src="';
                str+= 'data:image/svg+xml;utf-8,';
                str+= '<?xml version="1.0" encoding="UTF-8"?>';
                str+= '<svg width="20px" height="20px" viewBox="0 0 200 200" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">';
                str+= img;
                str+= '</svg>"';
                str+= ' />';
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
            addRow: function(data) {
                // todo check for field formatter by name
                this.rows.push(data);
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
                if (this.params.search && this.params.search.length > 1) {
                    this.params.offset = 0;
                    this.fetch();
                }
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
                return JSON.stringify(this.params) + JSON.stringify(this.sort);
            }
        }
    }
    </script>
</body>
</html>
