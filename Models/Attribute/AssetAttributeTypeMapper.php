<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   Modules\AssetManagement\Models\Attribute
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\AssetManagement\Models\Attribute;

use Modules\Attribute\Models\AttributeType;
use phpOMS\DataStorage\Database\Mapper\DataMapperFactory;

/**
 * Asset mapper class.
 *
 * @package Modules\AssetManagement\Models\Attribute
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 *
 * @template T of AttributeType
 * @extends DataMapperFactory<T>
 */
final class AssetAttributeTypeMapper extends DataMapperFactory
{
    /**
     * Columns.
     *
     * @var array<string, array{name:string, type:string, internal:string, autocomplete?:bool, readonly?:bool, writeonly?:bool, annotations?:array}>
     * @since 1.0.0
     */
    public const COLUMNS = [
        'assetmgmt_attr_type_id'         => ['name' => 'assetmgmt_attr_type_id',       'type' => 'int',    'internal' => 'id'],
        'assetmgmt_attr_type_name'       => ['name' => 'assetmgmt_attr_type_name',     'type' => 'string', 'internal' => 'name', 'autocomplete' => true],
        'assetmgmt_attr_type_datatype'   => ['name' => 'assetmgmt_attr_type_datatype',   'type' => 'int',    'internal' => 'datatype'],
        'assetmgmt_attr_type_fields'     => ['name' => 'assetmgmt_attr_type_fields',   'type' => 'int',    'internal' => 'fields'],
        'assetmgmt_attr_type_custom'     => ['name' => 'assetmgmt_attr_type_custom',   'type' => 'bool',   'internal' => 'custom'],
        'assetmgmt_attr_type_repeatable' => ['name' => 'assetmgmt_attr_type_repeatable',   'type' => 'bool',   'internal' => 'repeatable'],
        'assetmgmt_attr_type_internal'   => ['name' => 'assetmgmt_attr_type_internal',   'type' => 'bool',   'internal' => 'isInternal'],
        'assetmgmt_attr_type_pattern'    => ['name' => 'assetmgmt_attr_type_pattern',  'type' => 'string', 'internal' => 'validationPattern'],
        'assetmgmt_attr_type_required'   => ['name' => 'assetmgmt_attr_type_required', 'type' => 'bool',   'internal' => 'isRequired'],
    ];

    /**
     * Has many relation.
     *
     * @var array<string, array{mapper:class-string, table:string, self?:?string, external?:?string, column?:string}>
     * @since 1.0.0
     */
    public const HAS_MANY = [
        'l11n' => [
            'mapper'   => AssetAttributeTypeL11nMapper::class,
            'table'    => 'assetmgmt_attr_type_l11n',
            'self'     => 'assetmgmt_attr_type_l11n_type',
            'column'   => 'content',
            'external' => null,
        ],
        'defaults' => [
            'mapper'   => AssetAttributeValueMapper::class,
            'table'    => 'assetmgmt_asset_attr_default',
            'self'     => 'assetmgmt_asset_attr_default_type',
            'external' => 'assetmgmt_asset_attr_default_value',
        ],
    ];

    /**
     * Model to use by the mapper.
     *
     * @var class-string<T>
     * @since 1.0.0
     */
    public const MODEL = AttributeType::class;

    /**
     * Primary table.
     *
     * @var string
     * @since 1.0.0
     */
    public const TABLE = 'assetmgmt_attr_type';

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    public const PRIMARYFIELD = 'assetmgmt_attr_type_id';
}
