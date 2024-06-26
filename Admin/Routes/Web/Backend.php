<?php declare(strict_types=1);

use Modules\AssetManagement\Controller\BackendController;
use Modules\AssetManagement\Models\PermissionCategory;
use phpOMS\Account\PermissionType;
use phpOMS\Router\RouteVerb;

return [
    '^/accounting/asset/attribute/type/list(\?.*$|$)' => [
        [
            'dest'       => '\Modules\AssetManagement\Controller\BackendController:viewAssetManagementAttributeTypeList',
            'verb'       => RouteVerb::GET,
            'active'     => true,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::ASSET,
            ],
        ],
    ],
    '^/accounting/asset/attribute/type/view(\?.*$|$)' => [
        [
            'dest'       => '\Modules\AssetManagement\Controller\BackendController:viewAssetManagementAttributeType',
            'verb'       => RouteVerb::GET,
            'active'     => true,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::ASSET,
            ],
        ],
    ],

    '^/accounting/asset/list(\?.*$|$)' => [
        [
            'dest'       => '\Modules\AssetManagement\Controller\BackendController:viewAssetManagementList',
            'verb'       => RouteVerb::GET,
            'active'     => true,
            'permission' => [
                'module' => BackendController::MODULE_NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::ASSET,
            ],
        ],
    ],
    '^/accounting/asset/view(\?.*$|$)' => [
        [
            'dest'       => '\Modules\AssetManagement\Controller\BackendController:viewAssetManagementView',
            'verb'       => RouteVerb::GET,
            'active'     => true,
            'permission' => [
                'module' => BackendController::MODULE_NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::ASSET,
            ],
        ],
    ],
    '^/accounting/asset/create(\?.*$|$)' => [
        [
            'dest'       => '\Modules\AssetManagement\Controller\BackendController:viewAssetManagementCreate',
            'verb'       => RouteVerb::GET,
            'active'     => true,
            'permission' => [
                'module' => BackendController::MODULE_NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionCategory::ASSET,
            ],
        ],
    ],
    '^/accounting/asset/table(\?.*$|$)' => [
        [
            'dest'       => '\Modules\AssetManagement\Controller\BackendController:viewAssetManagementAssetTable',
            'verb'       => RouteVerb::GET,
            'active'     => true,
            'permission' => [
                'module' => BackendController::MODULE_NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::ASSET,
            ],
        ],
    ],
];
