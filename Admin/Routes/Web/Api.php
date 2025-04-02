<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   Modules
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

use Modules\AssetManagement\Controller\ApiController;
use Modules\AssetManagement\Models\PermissionCategory;
use phpOMS\Account\PermissionType;
use phpOMS\Router\RouteVerb;

return [
    '^.*/accounting/asset/find(\?.*$|$)' => [
        [
            'dest'       => '\Modules\AssetManagement\Controller\ApiController:apiAssetFind',
            'verb'       => RouteVerb::GET,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::ASSET,
            ],
        ],
    ],
    '^.*/accounting/asset(\?.*|$)$' => [
        [
            'dest'       => '\Modules\AssetManagement\Controller\ApiController:apiAssetCreate',
            'verb'       => RouteVerb::PUT,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionCategory::ASSET,
            ],
        ],
        [
            'dest'       => '\Modules\AssetManagement\Controller\ApiController:apiAssetUpdate',
            'verb'       => RouteVerb::SET,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::MODIFY,
                'state'  => PermissionCategory::ASSET,
            ],
        ],
    ],

    '^.*/accounting/asset/file(\?.*|$)$' => [
        [
            'dest'       => '\Modules\AssetManagement\Controller\ApiController:apiFileCreate',
            'verb'       => RouteVerb::PUT,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionCategory::ASSET,
            ],
        ],
    ],

    '^.*/accounting/asset/note(\?.*|$)$' => [
        [
            'dest'       => '\Modules\AssetManagement\Controller\ApiController:apiNoteCreate',
            'verb'       => RouteVerb::PUT,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionCategory::ASSET,
            ],
        ],
        [
            'dest'       => '\Modules\AssetManagement\Controller\ApiController:apiNoteUpdate',
            'verb'       => RouteVerb::SET,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::MODIFY,
                'state'  => PermissionCategory::ASSET,
            ],
        ],
        [
            'dest'       => '\Modules\AssetManagement\Controller\ApiController:apiNoteDelete',
            'verb'       => RouteVerb::DELETE,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::DELETE,
                'state'  => PermissionCategory::ASSET,
            ],
        ],
    ],
];