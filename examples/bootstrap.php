<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
  <title>'LittleBigTable' Bootstrap example</title>
  <meta name="description" content="A simple AlpineJS interactive table" />
</head>
<body>
    <section class="section">
        <div class="container">
            <h1 class="display-4">LittleBigTable with Bootstrap</h1>
            <div x-data="LittleBigTable(options)" x-init="init()">
                <div class="py-4">
                    <div class="float-left">

                        <div class="form-group row">
                            <label for="search" class="col-sm-2 col-form-label">Search</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="search" placeholder="Start typing..." x-model="params.search" @keyup.debounce.350="doSearch()">
                            </div>
                        </div>

                    </div>
                    <div class="float-right">
                        
                        <div class="form-group row">
                            <label for="limit" class="col-sm-2 col-form-label">Show</label>
                            <div class="col-sm-10">
                                <select class="custom-select" id="limit" @change="setLimit()" x-model="params.limit">
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
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>State <button class="btn btn-link" type="button" x-html="getSortIcon('state')" @click="doSort('state')"></th>
                            <th>County <button class="btn btn-link" type="button" x-html="getSortIcon('county')" @click="doSort('county')"></th>
                            <th>Year <button class="btn btn-link" type="button" x-html="getSortIcon('year')" @click="doSort('year')"></button></th>
                            <th>Capacity <button class="btn btn-link" type="button" x-html="getSortIcon('turbine_capacity')" @click="doSort('turbine_capacity')"></th>
                            <th>Turbines <button class="btn btn-link" type="button" x-html="getSortIcon('project_capacity')" @click="doSort('project_capacity')"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="row in rows">
                            <tr>
                                <td x-html="row.state"></td>
                                <td x-text="row.county"></td>
                                <td x-text="row.year"></td>
                                <td x-html="row.turbine_capacity"></td>
                                <td x-text="row.project_capacity"></td>
                            </tr>
                        </template>
                    </tbody>
                </table>
                <div class="py-4">
                    <div class="float-left">
                        <p x-html="meta.status"></p>
                    </div>
                    <div class="float-right">
                        <nav aria-label="Page navigation example">
                            <ul class="pagination">
                                <li class="page-item"><a class="page-link" aria-label="Goto first page" @click="goFirstPage()" :disabled="getCurrentPage() == 1" href="#">|&lt;</a></li>
                                <li class="page-item"><a class="page-link" aria-label="Goto previous page"@click="goPrevPage()" :disabled="getCurrentPage() == 1" href="#">&lt;</a></li>
                                <li class="page-item"><a class="page-link" aria-label="Goto next page" @click="goNextPage()" :disabled="getCurrentPage() == getTotalPages()" href="#">&gt;</a></li>
                                <li class="page-item"><a class="page-link" aria-label="Goto last page" @click="goLastPage()" :disabled="getCurrentPage() == getTotalPages()" href="#">&gt;|</a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="../dist/alpine.min.js" defer></script>
    <script src="../dist/LittleBigTable.min.js"></script>
    <script nonce="abc123">
        options = {
            'url': 'http://localhost:8080/examples/json.php',
            'limit': 15,
            'formatters': {
                'state': function(value, row) {
                    return '<strong>' + value + '</strong>';
                },
                'turbine_capacity': function(value, row) {
                    if (parseInt(value) < 1500) {
                        return '<span class="text-warning font-weight-bolder">' + value  + "</span>";
                    }
                    if (parseInt(value) > 2000) {
                        return '<span class="text-success font-weight-bolder">' + value  + "</span>";
                    }
                    return '<span class="text-primary font-weight-bolder">' + value  + "</span>";
                }
            }
        }
    </script>
</body>
</html>
