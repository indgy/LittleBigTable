<?php
$data = json_decode(file_get_contents('data.json'));

// emulate network delay
// sleep(1);

$dir = SORT_ASC;
$sort = 0;
$limit = 10;
$offset = 0;
if (isset($_GET['limit'])) {
    $limit = (int) $_GET['limit'];
    if ($limit < 1) {
        $limit = 1;
    }
}
if (isset($_GET['offset'])) {
    $offset = (int) $_GET['offset'];
    if ($offset < 0) {
        $offset = 0;
    }
}
if (isset($_GET['sort'])) {
    $sort = preg_replace('/[^a-z0-9\.\_\-]/i', '', $_GET['sort']);
}
// do filters
if (isset($_GET['search'])) {
    $search = preg_replace('/[^a-z0-9\s\.\_\-]/i', '', $_GET['search']);
    $data = array_filter($data, function($o) use ($search) {
        foreach (get_object_vars($o) as $k=>$v) {
            if (stristr($v, $search)) {
                return true;
            }
        }
        return false;
    });
}

// do sort
if ($sort) {
    $cols = array_column($data, $sort);
    array_multisort($cols, $dir, $data);
}

// get total
$count = count($data);
// do slice for pagination
$data = array_slice($data, $offset, $limit);

header('Access-Control-Allow-Origin', 'http://localhost:8080');
echo json_encode([
    'data' => $data,
    'total' => $count,
]);
