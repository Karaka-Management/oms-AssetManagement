<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   Modules\AssetManagement\Models
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\AssetManagement\Models;

use phpOMS\DataStorage\Database\Mapper\DataMapperFactory;

/**
 * Item mapper class.
 *
 * @package Modules\AssetManagement\Models
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 *
 * @template T of AssetType
 * @extends DataMapperFactory<T>
 */
final class AssetTypeMapper extends DataMapperFactory
{
    /**
     * Columns.
     *
     * @var array<string, array{name:string, type:string, internal:string, autocomplete?:bool, readonly?:bool, writeonly?:bool, annotations?:array}>
     * @since 1.0.0
     */
    public const COLUMNS = [
        'assetmgmt_asset_type_id'                    => ['name' => 'assetmgmt_asset_type_id',       'type' => 'int',    'internal' => 'id'],
        'assetmgmt_asset_type_name'                  => ['name' => 'assetmgmt_asset_type_name',     'type' => 'string', 'internal' => 'title', 'autocomplete' => true],
        'assetmgmt_asset_type_depreciation_duration' => ['name' => 'assetmgmt_asset_type_depreciation_duration',     'type' => 'int', 'internal' => 'depreciationDuration'],
        'assetmgmt_asset_type_industry'              => ['name' => 'assetmgmt_asset_type_industry',     'type' => 'int', 'internal' => 'industry'],

    ];

    /**
     * Has many relation.
     *
     * @var array<string, array{mapper:class-string, table:string, self?:?string, external?:?string, column?:string}>
     * @since 1.0.0
     */
    public const HAS_MANY = [
        'l11n' => [
            'mapper'   => AssetTypeL11nMapper::class,
            'table'    => 'assetmgmt_asset_type_l11n',
            'self'     => 'assetmgmt_asset_type_l11n_type',
            'column'   => 'content',
            'external' => null,
        ],
    ];

    /**
     * Model to use by the mapper.
     *
     * @var class-string<T>
     * @since 1.0.0
     */
    public const MODEL = AssetType::class;

    /**
     * Primary table.
     *
     * @var string
     * @since 1.0.0
     */
    public const TABLE = 'assetmgmt_asset_type';

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    public const PRIMARYFIELD = 'assetmgmt_asset_type_id';
}
