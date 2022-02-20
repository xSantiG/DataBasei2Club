<?php

declare(strict_types=1);

namespace PhpMyAdmin\Controllers\Table;

use PhpMyAdmin\Controllers\AbstractController;
use PhpMyAdmin\DbTableExists;
use PhpMyAdmin\Url;
use PhpMyAdmin\Util;

use function __;

final class DropColumnConfirmationController extends AbstractController
{
    public function __invoke(): void
    {
        global $db, $table, $urlParams, $errorUrl, $cfg;

        $selected = $_POST['selected_fld'] ?? null;

        if (empty($selected)) {
            $this->response->setRequestStatus(false);
            $this->response->addJSON('message', __('No column selected.'));

            return;
        }

        Util::checkParameters(['db', 'table']);

        $urlParams = ['db' => $GLOBALS['db'], 'table' => $GLOBALS['table']];
        $errorUrl = Util::getScriptNameForOption($cfg['DefaultTabTable'], 'table');
        $errorUrl .= Url::getCommon($urlParams, '&');

        DbTableExists::check($db, $table);

        $this->render('table/structure/drop_confirm', [
            'db' => $GLOBALS['db'],
            'table' => $GLOBALS['table'],
            'fields' => $selected,
        ]);
    }
}
