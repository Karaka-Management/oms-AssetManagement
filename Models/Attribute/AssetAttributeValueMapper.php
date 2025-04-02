<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   Modules\AssetManagement\Models\Attribute
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\AssetManagement\Models\Attribute;

use Modules\Attribute\Models\AttributeValue;
use phpOMS\DataStorage\Database\Mapper\DataMapperFactory;

/**
 * Asset mapper class.
 *
 * @package Modules\AssetManagement\Models\Attribute
 * @license OMS License 2.2
 * @link    https://jingga.app
 * @since   1.0.0
 *
 * @template T of AttributeValue
 * @extends DataMapperFactory<T>
 */
final class AssetAttributeValueMapper extends DataMapperFactory
{
    /**
     * Columns.
     *
     * @var array<string, array{name:string, type:string, internal:string, autocomplete?:bool, readonly?:bool, writeonly?:bool, annotations?:array}>
     * @since 1.0.0
     */
    public const COLUMNS = [
        'assetmgmt_attr_value_id'       => ['name' => 'assetmgmt_attr_value_id',       'type' => 'int',      'internal' => 'id'],
        'assetmgmt_attr_value_default'  => ['name' => 'assetmgmt_attr_value_default',  'type' => 'bool',     'internal' => 'isDefault'],
        'assetmgmt_attr_value_valueStr' => ['name' => 'assetmgmt_attr_value_valueStr', 'type' => 'string',   'internal' => 'valueStr'],
        'assetmgmt_attr_value_valueInt' => ['name' => 'assetmgmt_attr_value_valueInt', 'type' => 'int',      'internal' => 'valueInt'],
        'assetmgmt_attr_value_valueDec' => ['name' => 'assetmgmt_attr_value_valueDec', 'type' => 'float',    'internal' => 'valueDec'],
        'assetmgmt_attr_value_valueDat' => ['name' => 'assetmgmt_attr_value_valueDat', 'type' => 'DateTime', 'internal' => 'valueDat'],
        'assetmgmt_attr_value_unit'     => ['name' => 'assetmgmt_attr_value_unit', 'type' => 'string', 'internal' => 'unit'],
        'assetmgmt_attr_value_deptype'  => ['name' => 'assetmgmt_attr_value_deptype', 'type' => 'int', 'internal' => 'dependingAttributeType'],
        'assetmgmt_attr_value_depvalue' => ['name' => 'assetmgmt_attr_value_depvalue', 'type' => 'int', 'internal' => 'dependingAttributeValue'],
    ];

    /**
     * Has many relation.
     *
     * @var array<string, array{mapper:class-string, table:string, self?:?string, external?:?string, column?:string}>
     * @since 1.0.0
     */
    public const HAS_MANY = [
        'l11n' => [
            'mapper'   => AssetAttributeValueL11nMapper::class,
            'table'    => 'assetmgmt_attr_value_l11n',
            'self'     => 'assetmgmt_attr_value_l11n_value',
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
    public const MODEL = AttributeValue::class;

    /**
     * Primary table.
     *
     * @var string
     * @since 1.0.0
     */
    public const TABLE = 'assetmgmt_attr_value';

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    public const PRIMARYFIELD = 'assetmgmt_attr_value_id';
}
