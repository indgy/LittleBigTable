<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.1/css/bulma.min.css">
  <title>'LittleBigTable' Basic example</title>
  <meta name="description" content="LittleBigTable is a small (~4k gzipped) javascript table using AlpineJS" />
  <!-- <meta http-equiv="Content-Security-Policy" content="base-uri: 'none'; object-src: 'none'; script-src 'self' 'unsafe-eval' 'unsafe-inline' 'nonce-abc123'"> -->
</head>
<body>
    <section class="section">
        <div class="container">
            <h1 class="title is-3">LittleBigTable</h1>
            <p class="content is-size-5">This is the basic example using the default settings, only the url is specified.</p>
            <div x-data="LittleBigTable({url:'http://localhost:8080/examples/json.php'})" x-init="init()">
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
                <table class="table is-fullwidth is-hoverable">
                    <thead>
                        <tr>
                            <th class="has-text-left">State <button class="button is-small is-text" type="button" x-html="getSortIcon('state')" @click="doSort('state')"></th>
                            <th class="has-text-left">County <button class="button is-small is-text" type="button" x-html="getSortIcon('county')" @click="doSort('county')"></th>
                            <th class="has-text-centered">Year <button class="button is-small is-text" type="button" x-html="getSortIcon('year')" @click="doSort('year')"></button></th>
                            <th class="has-text-centered">Capacity <button class="button is-small is-text" type="button" x-html="getSortIcon('turbine_capacity')" @click="doSort('turbine_capacity')"></th>
                            <th class="has-text-centered">Turbines <button class="button is-small is-text" type="button" x-html="getSortIcon('project_capacity')" @click="doSort('project_capacity')"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="row in rows">
                            <tr>
                                <td x-html="row.state"></td>
                                <td x-text="row.county"></td>
                                <td class="has-text-centered" x-text="row.year"></td>
                                <td class="has-text-centered" x-html="row.turbine_capacity"></td>
                                <td class="has-text-centered" x-text="row.project_capacity"></td>
                            </tr>
                        </template>
                    </tbody>
                </table>
                <div class="level">
                    <div class="level-left">
                        <p x-html="meta.status"></p>
                    </div>
                    <div class="level-right">
                        <nav class="pagination" role="navigation" aria-label="pagination">
                            <ul class="pagination-list">
                                <li>
                                    <a class="pagination-link" aria-label="Goto first page" @click="goFirstPage()" :disabled="getCurrentPage() == 1">
                                        <svg class="icon"><use xlink:href="../dist/icons.svg#page-first"></use></svg>
                                    </a>
                                </li>
                                <li>
                                    <a class="pagination-previous" @click="goPrevPage()" :disabled="getCurrentPage() == 1">
                                        <svg class="icon"><use xlink:href="../dist/icons.svg#page-prev"></use></svg>
                                    </a>
                                </li>
                                <li>
                                    
                                </li>
                                <li>
                                    <a class="pagination-next" @click="goNextPage()" :disabled="getCurrentPage() == getTotalPages()">
                                        <svg class="icon"><use xlink:href="../dist/icons.svg#page-next"></use></svg>
                                    </a>
                                </li>
                                <li>
                                    <a class="pagination-link" aria-label="Goto last page" @click="goLastPage()" :disabled="getCurrentPage() == getTotalPages()">
                                        <svg class="icon"><use xlink:href="../dist/icons.svg#page-last"></use></svg>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="../dist/alpine.min.js" defer></script>
    <script src="../dist/LittleBigTable.min.js"></script>
</body>
</html>
