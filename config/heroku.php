<?php

$url = parse_url(getenv("JAWSDB_URL") ?: getenv("CLEARDB_DATABASE_URL") ?: getenv("DATABASE_URL"));

return [
    'host' => $url["host"] ?? null,
    'port' => $url["port"] ?? null,
    'database' => ltrim($url["path"] ?? "", "/"),
    'username' => $url["user"] ?? null,
    'password' => $url["pass"] ?? null,
];
