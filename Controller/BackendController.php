<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   Modules\AssetManagement
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\AssetManagement\Controller;

use Modules\AssetManagement\Models\AssetMapper;
use Modules\AssetManagement\Models\AssetTypeMapper;
use Modules\AssetManagement\Models\Attribute\AssetAttributeTypeL11nMapper;
use Modules\AssetManagement\Models\Attribute\AssetAttributeTypeMapper;
use Modules\Media\Models\MediaMapper;
use Modules\Media\Models\MediaTypeMapper;
use Modules\Organization\Models\UnitMapper;
use phpOMS\Contract\RenderableInterface;
use phpOMS\DataStorage\Database\Query\Builder;
use phpOMS\Message\RequestAbstract;
use phpOMS\Message\ResponseAbstract;
use phpOMS\Views\View;

/**
 * Budgeting controller class.
 *
 * @package Modules\AssetManagement
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class BackendController extends Controller
{
    /**
     * Routing end-point for application behavior.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return RenderableInterface
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewAssetManagementList(RequestAbstract $request, ResponseAbstract $response, $data = null) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);
        $view->setTemplate('/Modules/AssetManagement/Theme/Backend/asset-list');
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1006601001, $request, $response);

        $list = AssetMapper::getAll()
            ->with('type')
            ->with('type/l11n')
            ->where('type/l11n/language', $response->header->l11n->language)
            ->sort('id', 'DESC')
            ->execute();

        $view->data['assets'] = $list;

        return $view;
    }

    /**
     * Routing end-point for application behavior.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return RenderableInterface
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewAssetManagementAttributeType(RequestAbstract $request, ResponseAbstract $response, $data = null) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);
        $view->setTemplate('/Modules/AssetManagement/Theme/Backend/asset-view');
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1006601001, $request, $response);

        /** @var \Modules\Attribute\Models\AttributeType $attribute */
        $attribute = AssetAttributeTypeMapper::get()
            ->with('l11n')
            ->where('id', (int) $request->getData('id'))
            ->where('l11n/language', $response->header->l11n->language)
            ->execute();

        $l11ns = AssetAttributeTypeL11nMapper::getAll()
            ->where('ref', $attribute->id)
            ->execute();

        $view->data['attribute'] = $attribute;
        $view->data['l11ns']     = $l11ns;

        return $view;
    }

    /**
     * Routing end-point for application behavior.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return RenderableInterface Returns a renderable object
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewAssetManagementAssetView(RequestAbstract $request, ResponseAbstract $response, array $data = []) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);

        $view->setTemplate('/Modules/AssetManagement/Theme/Backend/asset-view');
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1008402001, $request, $response);

        $asset = AssetMapper::get()
            ->with('attributes')
            ->with('attributes/type')
            ->with('attributes/value')
            ->with('attributes/type/l11n')
            //->with('attributes/value/l11n')
            ->with('files')
            ->with('files/types')
            ->with('type')
            ->with('type/l11n')
            ->where('id', (int) $request->getData('id'))
            ->where('type/l11n/language', $response->header->l11n->language)
            ->where('attributes/type/l11n/language', $response->header->l11n->language)
            //->where('attributes/value/l11n/language', $response->header->l11n->language)
            ->execute();

        $view->data['asset'] = $asset;

        $query   = new Builder($this->app->dbPool->get());
        $results = $query->selectAs(AssetMapper::HAS_MANY['files']['external'], 'file')
            ->from(AssetMapper::TABLE)
            ->leftJoin(AssetMapper::HAS_MANY['files']['table'])
                ->on(AssetMapper::HAS_MANY['files']['table'] . '.' . AssetMapper::HAS_MANY['files']['self'], '=', AssetMapper::TABLE . '.' . AssetMapper::PRIMARYFIELD)
            ->leftJoin(MediaMapper::TABLE)
                ->on(AssetMapper::HAS_MANY['files']['table'] . '.' . AssetMapper::HAS_MANY['files']['external'], '=', MediaMapper::TABLE . '.' . MediaMapper::PRIMARYFIELD)
             ->leftJoin(MediaMapper::HAS_MANY['types']['table'])
                ->on(MediaMapper::TABLE . '.' . MediaMapper::PRIMARYFIELD, '=', MediaMapper::HAS_MANY['types']['table'] . '.' . MediaMapper::HAS_MANY['types']['self'])
            ->leftJoin(MediaTypeMapper::TABLE)
                ->on(MediaMapper::HAS_MANY['types']['table'] . '.' . MediaMapper::HAS_MANY['types']['external'], '=', MediaTypeMapper::TABLE . '.' . MediaTypeMapper::PRIMARYFIELD)
            ->where(AssetMapper::HAS_MANY['files']['self'], '=', $asset->id)
            ->where(MediaTypeMapper::TABLE . '.' . MediaTypeMapper::getColumnByMember('name'), '=', 'asset_profile_image');

        $assetImage = MediaMapper::get()
            ->with('types')
            ->where('id', $results)
            ->limit(1)
            ->execute();

        $view->data['assetImage'] = $assetImage;

        $assetTypes = AssetTypeMapper::getAll()
            ->with('l11n')
            ->where('l11n/language', $response->header->l11n->language)
            ->execute();

        $view->data['types'] = $assetTypes;

        $units = UnitMapper::getAll()
            ->execute();

        $view->data['units'] = $units;

        $view->data['attributeView']                               = new \Modules\Attribute\Theme\Backend\Components\AttributeView($this->app->l11nManager, $request, $response);
        $view->data['attributeView']->data['default_localization'] = $this->app->l11nServer;

        $view->data['media-upload'] = new \Modules\Media\Theme\Backend\Components\Upload\BaseView($this->app->l11nManager, $request, $response);
        $view->data['asset-notes']  = new \Modules\Editor\Theme\Backend\Components\Compound\BaseView($this->app->l11nManager, $request, $response);

        return $view;
    }
}
