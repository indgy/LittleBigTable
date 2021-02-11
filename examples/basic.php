<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>'littleBIGtable' Basic example</title>
  <meta name="description" content="littleBIGtable is a small (~4k gzipped) javascript table using AlpineJS" />
  <!-- <meta http-equiv="Content-Security-Policy" content="base-uri: 'none'; object-src: 'none'; script-src 'self' 'unsafe-eval' 'unsafe-inline' 'nonce-abc123'"> -->
  <style>
    .table, .pager {
        width:100%;
        max-width:600px;
        text-align:center;
    }
    .pager ul li {
        display:inline-block;
    }
    .pager ul li a:disabled {
        display:none;
    }
    .icon {
        display:block;
        width:2rem;
        height:2rem;
    }
    
  </style>
</head>
<body>
    <h1>littleBIGtable</h1>
    <p>This is the basic example with no styling, using the default settings, only the url must be specified.</p>
    <div x-data="littleBIGtable({url:'/examples/json.php'})" x-init="init()">
        <table class="table">
            <thead>
                <tr>
                    <th>County</th>
                    <th>Year</th>
                    <th>Output</th>
                </tr>
            </thead>
            <tbody>
                <template x-for="row in rows">
                    <tr>
                        <td x-text="row.county"></td>
                        <td x-text="row.year"></td>
                        <td x-text="row.project_capacity"></td>
                    </tr>   
                </template>
            </tbody>
        </table>
        <nav class="pager" role="navigation" aria-label="pagination">
            <ul>
                <li>
                    <a aria-label="Goto first page" @click="goFirstPage()" :disabled="getCurrentPage() == 1">
                        <svg class="icon"><use xlink:href="../dist/icons.svg#page-first"></use></svg>
                    </a>
                </li>
                <li>
                    <a @click="goPrevPage()" :disabled="getCurrentPage() == 1">
                        <svg class="icon"><use xlink:href="../dist/icons.svg#page-prev"></use></svg>
                    </a>
                </li>
                <li>
                    <a @click="goNextPage()" :disabled="getCurrentPage() == getTotalPages()">
                        <svg class="icon"><use xlink:href="../dist/icons.svg#page-next"></use></svg>
                    </a>
                </li>
                <li>
                    <a aria-label="Goto last page" @click="goLastPage()" :disabled="getCurrentPage() == getTotalPages()">
                        <svg class="icon"><use xlink:href="../dist/icons.svg#page-last"></use></svg>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
    <script src="../dist/alpine.min.js" defer></script>
    <script src="../dist/littleBIGtable.min.js"></script>
</body>
</html>
