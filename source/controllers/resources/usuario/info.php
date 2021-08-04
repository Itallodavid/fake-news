<?php

namespace source\controllers\resources;

use source\helpers\HTTPResponseHelper;
use source\helpers\SessionHelper;

HTTPResponseHelper::ok(SessionHelper::obterInfoSessao());