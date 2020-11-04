<?php

function decodeResource($requestUri) {
    return str_replace('api/', '', str_replace('index.php', '', $requestUri));
}