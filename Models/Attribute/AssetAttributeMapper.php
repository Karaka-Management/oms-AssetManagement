<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   Modules\AssetManagement\Models\Attribute
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\AssetManagement\Models\Attribute;

use Modules\Attribute\Models\Attribute;
use phpOMS\DataStorage\Database\Mapper\DataMapperFactory;

/**
 * Fleet mapper class.
 *
 * @package Modules\AssetManagement\Models\Attribute
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 *
 * @template T of Attribute
 * @extends DataMapperFactory<T>
 */
final class AssetAttributeMapper extends DataMapperFactory
{
    /**
     * Columns.
     *
     * @var array<string, array{name:string, type:string, internal:string, autocomplete?:bool, readonly?:bool, writeonly?:bool, annotations?:array}>
     * @since 1.0.0
     */
    public const COLUMNS = [
        'assetmgmt_asset_attr_id'    => ['name' => 'assetmgmt_asset_attr_id',    'type' => 'int', 'internal' => 'id'],
        'assetmgmt_asset_attr_asset' => ['name' => 'assetmgmt_asset_attr_asset',  'type' => 'int', 'internal' => 'ref'],
        'assetmgmt_asset_attr_type'  => ['name' => 'assetmgmt_asset_attr_type',  'type' => 'int', 'internal' => 'type'],
        'assetmgmt_asset_attr_value' => ['name' => 'assetmgmt_asset_attr_value', 'type' => 'int', 'internal' => 'value'],
    ];

    /**
     * Has one relation.
     *
     * @var array<string, array{mapper:class-string, external:string, by?:string, column?:string, conditional?:bool}>
     * @since 1.0.0
     */
    public const OWNS_ONE = [
        'type' => [
            'mapper'   => AssetAttributeTypeMapper::class,
            'external' => 'assetmgmt_asset_attr_type',
        ],
        'value' => [
            'mapper'   => AssetAttributeValueMapper::class,
            'external' => 'assetmgmt_asset_attr_value',
        ],
    ];

    /**
     * Model to use by the mapper.
     *
     * @var class-string<T>
     * @since 1.0.0
     */
    public const MODEL = Attribute::class;

    /**
     * Primary table.
     *
     * @var string
     * @since 1.0.0
     */
    public const TABLE = 'assetmgmt_asset_attr';

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    public const PRIMARYFIELD = 'assetmgmt_asset_attr_id';
}
