<?php

namespace source\controllers\resources;

use source\helpers\HTTPResponseHelper;
use source\helpers\SessionHelper;

if(!SessionHelper::destruirSessao()) HTTPResponseHelper::methodNotAllowed(['mensagem' => 'você não está autenticado']);

HTTPResponseHelper::ok(null);