<?php

namespace source\helpers;

final class HTTPStatusHelper
{
    const OK = 200;
    const CREATED = 201;
    
    const BAD_REQUEST = 400;
    const UNAUTHORIZED = 401;
    const NOT_FOUND = 404;
    const METHOD_NOT_ALLOWED = 405;

    const ERROR_SERVER = 500;
}