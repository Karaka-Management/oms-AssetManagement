<?php declare(strict_types=1);

use Modules\AssetManagement\Controller\BackendController;
use Modules\AssetManagement\Models\PermissionCategory;
use phpOMS\Account\PermissionType;
use phpOMS\Router\RouteVerb;

return [
    '^.*/accounting/attribute/type/list(\?.*$|$)' => [
        [
            'dest'       => '\Modules\EquipmentManagement\Controller\BackendController:viewEquipmentManagementAttributeTypeList',
            'verb'       => RouteVerb::GET,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::ASSET,
            ],
        ],
    ],
    '^.*/accounting/attribute/type(\?.*$|$)' => [
        [
            'dest'       => '\Modules\EquipmentManagement\Controller\BackendController:viewEquipmentManagementAttributeType',
            'verb'       => RouteVerb::GET,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::ASSET,
            ],
        ],
    ],

    '^.*/accounting/asset/list(\?.*$|$)' => [
        [
            'dest'       => '\Modules\AssetManagement\Controller\BackendController:viewAssetManagementList',
            'verb'       => RouteVerb::GET,
            'permission' => [
                'module' => BackendController::MODULE_NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::ASSET,
            ],
        ],
    ],
    '^.*/accounting/asset/view(\?.*$|$)' => [
        [
            'dest'       => '\Modules\AssetManagement\Controller\BackendController:viewAssetManagementView',
            'verb'       => RouteVerb::GET,
            'permission' => [
                'module' => BackendController::MODULE_NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::ASSET,
            ],
        ],
    ],
    '^.*/accounting/asset/entry/list(\?.*$|$)' => [
        [
            'dest'       => '\Modules\AssetManagement\Controller\BackendController:viewAssetManagementEntryList',
            'verb'       => RouteVerb::GET,
            'permission' => [
                'module' => BackendController::MODULE_NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::ASSET,
            ],
        ],
    ],
    '^.*/accounting/asset/entry/view(\?.*$|$)' => [
        [
            'dest'       => '\Modules\AssetManagement\Controller\BackendController:viewAssetManagementEntryView',
            'verb'       => RouteVerb::GET,
            'permission' => [
                'module' => BackendController::MODULE_NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::ASSET,
            ],
        ],
    ],
    '^.*/accounting/asset/create(\?.*$|$)' => [
        [
            'dest'       => '\Modules\AssetManagement\Controller\BackendController:viewAssetManagementCreate',
            'verb'       => RouteVerb::GET,
            'permission' => [
                'module' => BackendController::MODULE_NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::ASSET,
            ],
        ],
    ],
    '^.*/accounting/asset/table(\?.*$|$)' => [
        [
            'dest'       => '\Modules\AssetManagement\Controller\BackendController:viewAssetManagementAssetTable',
            'verb'       => RouteVerb::GET,
            'permission' => [
                'module' => BackendController::MODULE_NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::ASSET,
            ],
        ],
    ],
];
