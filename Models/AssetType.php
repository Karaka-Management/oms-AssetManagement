<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   Modules\AssetManagement\Models
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\AssetManagement\Models;

use phpOMS\Localization\BaseStringL11nType;

/**
 * Asset class.
 *
 * @package Modules\Attribute\Models
 * @license OMS License 2.2
 * @link    https://jingga.app
 * @since   1.0.0
 */
class AssetType extends BaseStringL11nType
{
    public int $depreciationDuration = 0;

    public int $industry = 0;
}
