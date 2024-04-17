<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   Modules\AssetManagement\Models
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\AssetManagement\Models;

use Modules\AssetManagement\Models\Attribute\AssetAttributeMapper;
use Modules\Editor\Models\EditorDocMapper;
use Modules\Media\Models\MediaMapper;
use phpOMS\DataStorage\Database\Mapper\DataMapperFactory;

/**
 * Asset mapper class.
 *
 * @package Modules\AssetManagement\Models
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 *
 * @template T of Asset
 * @extends DataMapperFactory<T>
 */
final class AssetMapper extends DataMapperFactory
{
    /**
     * Columns.
     *
     * @var array<string, array{name:string, type:string, internal:string, autocomplete?:bool, readonly?:bool, writeonly?:bool, annotations?:array}>
     * @since 1.0.0
     */
    public const COLUMNS = [
        'assetmgmt_asset_id'          => ['name' => 'assetmgmt_asset_id',         'type' => 'int',      'internal' => 'id'],
        'assetmgmt_asset_name'        => ['name' => 'assetmgmt_asset_name',      'type' => 'string',   'internal' => 'name'],
        'assetmgmt_asset_number'      => ['name' => 'assetmgmt_asset_number',      'type' => 'string',   'internal' => 'number'],
        'assetmgmt_asset_status'      => ['name' => 'assetmgmt_asset_status',      'type' => 'int',   'internal' => 'status'],
        'assetmgmt_asset_info'        => ['name' => 'assetmgmt_asset_info',      'type' => 'string',   'internal' => 'info'],
        'assetmgmt_asset_unit'        => ['name' => 'assetmgmt_asset_unit',      'type' => 'int',   'internal' => 'unit'],
        'assetmgmt_asset_equipment'        => ['name' => 'assetmgmt_asset_equipment',      'type' => 'int',   'internal' => 'equipment'],
        'assetmgmt_asset_type'        => ['name' => 'assetmgmt_asset_type',      'type' => 'int',   'internal' => 'type'],
        'assetmgmt_asset_responsible' => ['name' => 'assetmgmt_asset_responsible',      'type' => 'int',   'internal' => 'responsible'],
        'assetmgmt_asset_created_at'  => ['name' => 'assetmgmt_asset_created_at', 'type' => 'DateTimeImmutable', 'internal' => 'createdAt', 'readonly' => true],
    ];

    /**
     * Has many relation.
     *
     * @var array<string, array{mapper:class-string, table:string, self?:?string, external?:?string, column?:string}>
     * @since 1.0.0
     */
    public const HAS_MANY = [
        'files' => [
            'mapper'   => MediaMapper::class,
            'table'    => 'assetmgmt_asset_media',
            'external' => 'assetmgmt_asset_media_media',
            'self'     => 'assetmgmt_asset_media_asset',
        ],
        'attributes' => [
            'mapper'   => AssetAttributeMapper::class,
            'table'    => 'assetmgmt_asset_attr',
            'self'     => 'assetmgmt_asset_attr_asset',
            'external' => null,
        ],
        'notes' => [
            'mapper'   => EditorDocMapper::class,       /* mapper of the related object */
            'table'    => 'assetmgmt_asset_note',         /* table of the related object, null if no relation table is used (many->1) */
            'external' => 'assetmgmt_asset_note_doc',
            'self'     => 'assetmgmt_asset_note_asset',
        ],
    ];

    /**
     * Has one relation.
     *
     * @var array<string, array{mapper:class-string, external:string, by?:string, column?:string, conditional?:bool}>
     * @since 1.0.0
     */
    public const OWNS_ONE = [
        'type' => [
            'mapper'   => AssetTypeMapper::class,
            'external' => 'assetmgmt_asset_type',
        ],
    ];

    /**
     * Primary table.
     *
     * @var string
     * @since 1.0.0
     */
    public const TABLE = 'assetmgmt_asset';

    /**
     * Created at.
     *
     * @var string
     * @since 1.0.0
     */
    public const CREATED_AT = 'assetmgmt_asset_created_at';

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    public const PRIMARYFIELD = 'assetmgmt_asset_id';
}
