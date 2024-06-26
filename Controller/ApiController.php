<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   Modules\AssetManagement
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\AssetManagement\Controller;

use Modules\AssetManagement\Models\Asset;
use Modules\AssetManagement\Models\AssetMapper;
use Modules\AssetManagement\Models\AssetStatus;
use Modules\AssetManagement\Models\PermissionCategory;
use Modules\Media\Models\NullCollection;
use Modules\Media\Models\PathSettings;
use phpOMS\Account\PermissionType;
use phpOMS\Localization\NullBaseStringL11nType;
use phpOMS\Message\Http\RequestStatusCode;
use phpOMS\Message\NotificationLevel;
use phpOMS\Message\RequestAbstract;
use phpOMS\Message\ResponseAbstract;

/**
 * AssetManagement class.
 *
 * @package Modules\AssetManagement
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class ApiController extends Controller
{
    /**
     * Api method to create a asset
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiAssetCreate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateAssetCreate($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidCreateResponse($request, $response, $val);

            return;
        }

        /** @var Asset $asset */
        $asset = $this->createAssetFromRequest($request);
        $this->createModel($request->header->account, $asset, AssetMapper::class, 'asset', $request->getOrigin());

        if (!empty($request->files)
            || !empty($request->getDataJson('media'))
        ) {
            $this->createAssetMedia($asset, $request);
        }

        $this->createStandardCreateResponse($request, $response, $asset);
    }

    /**
     * Method to create asset from request.
     *
     * @param RequestAbstract $request Request
     *
     * @return Asset Returns the created asset from the request
     *
     * @since 1.0.0
     */
    public function createAssetFromRequest(RequestAbstract $request) : Asset
    {
        $asset         = new Asset();
        $asset->name   = $request->getDataString('name') ?? '';
        $asset->info   = $request->getDataString('info') ?? '';
        $asset->type   = new NullBaseStringL11nType((int) ($request->getDataInt('type') ?? 0));
        $asset->status = AssetStatus::tryFromValue($request->getDataInt('status')) ?? AssetStatus::INACTIVE;
        $asset->unit   = $request->getDataInt('unit') ?? $this->app->unitId;
        $asset->equipment = $request->getDataInt('equipment');

        return $asset;
    }

    /**
     * Create media files for asset
     *
     * @param Asset           $asset   Asset
     * @param RequestAbstract $request Request incl. media do upload
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function createAssetMedia(Asset $asset, RequestAbstract $request) : void
    {
        $path = $this->createAssetDir($asset);

        if (!empty($request->files)) {
            $this->app->moduleManager->get('Media', 'Api')->uploadFiles(
                names: [],
                fileNames: [],
                files: $request->files,
                account: $request->header->account,
                basePath: __DIR__ . '/../../../Modules/Media/Files' . $path,
                virtualPath: $path,
                pathSettings: PathSettings::FILE_PATH,
                rel: $asset->id,
                mapper: AssetMapper::class,
                field: 'files'
            );
        }

        if (!empty($media = $request->getDataJson('media'))) {
            $this->app->moduleManager->get('Media', 'Api')->addMediaToCollectionAndModel(
                $request->header->account,
                $media,
                $asset->id,
                AssetMapper::class,
                'files',
                $path
            );
        }
    }

    /**
     * Validate asset create request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool> Returns the validation array of the request
     *
     * @since 1.0.0
     */
    private function validateAssetCreate(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['name'] = !$request->hasData('name'))
            || ($val['type'] = !$request->hasData('type'))
        ) {
            return $val;
        }

        return [];
    }

    /**
     * Api method to create a bill
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiMediaAddToAsset(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateMediaAddToAsset($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidAddResponse($request, $response, $val);

            return;
        }

        /** @var \Modules\AssetManagement\Models\Asset $asset */
        $asset = AssetMapper::get()->where('id', (int) $request->getData('asset'))->execute();
        $path  = $this->createAssetDir($asset);

        $uploaded = new NullCollection();
        if (!empty($request->files)) {
            $uploaded = $this->app->moduleManager->get('Media', 'Api')->uploadFiles(
                names: [],
                fileNames: [],
                files: $request->files,
                account: $request->header->account,
                basePath: __DIR__ . '/../../../Modules/Media/Files' . $path,
                virtualPath: $path,
                pathSettings: PathSettings::FILE_PATH,
                hasAccountRelation: false,
                readContent: $request->getDataBool('parse_content') ?? false,
                type: $request->getDataInt('type'),
                rel: $asset->id,
                mapper: AssetMapper::class,
                field: 'files'
            );
        }

        if (!empty($media = $request->getDataJson('media'))) {
            $this->app->moduleManager->get('Media', 'Api')->addMediaToCollectionAndModel(
                $request->header->account,
                $media,
                $asset->id,
                AssetMapper::class,
                'files',
                $path
            );
        }

        $this->fillJsonResponse($request, $response, NotificationLevel::OK, '', $this->app->l11nManager->getText($response->header->l11n->language, '0', '0', 'SuccessfulAdd'), [
            'upload' => $uploaded->sources,
            'media'  => $media,
        ]);
    }

    /**
     * Create media directory path
     *
     * @param Asset $asset Asset
     *
     * @return string
     *
     * @since 1.0.0
     */
    private function createAssetDir(Asset $asset) : string
    {
        return '/Modules/AssetManagement/Asset/'
            . $this->app->unitId . '/'
            . $asset->id;
    }

    /**
     * Method to validate bill creation from request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool>
     *
     * @since 1.0.0
     */
    private function validateMediaAddToAsset(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['media'] = (!$request->hasData('media') && empty($request->files)))
            || ($val['asset'] = !$request->hasData('asset'))
        ) {
            return $val;
        }

        return [];
    }

    /**
     * Api method to create notes
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiNoteCreate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateNoteCreate($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidCreateResponse($request, $response, $val);

            return;
        }

        $request->setData('virtualpath', '/Modules/AssetManagement/Asset/' . $request->getData('id'), true);
        $this->app->moduleManager->get('Editor', 'Api')->apiEditorCreate($request, $response, $data);

        if ($response->header->status !== RequestStatusCode::R_200) {
            return;
        }

        $responseData = $response->getDataArray($request->uri->__toString());
        if (!\is_array($responseData)) {
            return;
        }

        $model = $responseData['response'];
        $this->createModelRelation($request->header->account, (int) $request->getData('id'), $model->id, AssetMapper::class, 'notes', '', $request->getOrigin());
    }

    /**
     * Validate note create request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool>
     *
     * @since 1.0.0
     */
    private function validateNoteCreate(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['id'] = !$request->hasData('id'))
        ) {
            return $val;
        }

        return [];
    }

    /**
     * Api method to update Asset
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiAssetFind(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
    }

    /**
     * Api method to update Asset
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiAssetUpdate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateAssetUpdate($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidUpdateResponse($request, $response, $val);

            return;
        }

        /** @var \Modules\AssetManagement\Models\Asset $old */
        $old = AssetMapper::get()->where('id', (int) $request->getData('id'))->execute();
        $new = $this->updateAssetFromRequest($request, clone $old);

        $this->updateModel($request->header->account, $old, $new, AssetMapper::class, 'asset', $request->getOrigin());
        $this->createStandardUpdateResponse($request, $response, $new);
    }

    /**
     * Method to update Asset from request.
     *
     * @param RequestAbstract $request Request
     * @param Asset           $new     Model to modify
     *
     * @return Asset
     *
     * @todo Implement API update function
     *
     * @since 1.0.0
     */
    public function updateAssetFromRequest(RequestAbstract $request, Asset $new) : Asset
    {
        $new->name   = $request->getDataString('name') ?? $new->name;
        $new->info   = $request->getDataString('info') ?? $new->info;
        $new->type   = $request->hasData('type') ? new NullBaseStringL11nType((int) ($request->getDataInt('type') ?? 0)) : $new->type;
        $new->status = AssetStatus::tryFromValue($request->getDataInt('status')) ?? $new->status;
        $new->unit   = $request->getDataInt('unit') ?? $this->app->unitId;

        return $new;
    }

    /**
     * Validate Asset update request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool>
     *
     * @todo Implement API validation function
     *
     * @since 1.0.0
     */
    private function validateAssetUpdate(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['id'] = !$request->hasData('id'))) {
            return $val;
        }

        return [];
    }

    /**
     * Api method to delete Asset
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiAssetDelete(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateAssetDelete($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidDeleteResponse($request, $response, $val);

            return;
        }

        /** @var \Modules\AssetManagement\Models\Asset $asset */
        $asset = AssetMapper::get()->where('id', (int) $request->getData('id'))->execute();
        $this->deleteModel($request->header->account, $asset, AssetMapper::class, 'asset', $request->getOrigin());
        $this->createStandardDeleteResponse($request, $response, $asset);
    }

    /**
     * Validate Asset delete request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool>
     *
     * @since 1.0.0
     */
    private function validateAssetDelete(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['id'] = !$request->hasData('id'))) {
            return $val;
        }

        return [];
    }

    /**
     * Api method to update Note
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiNoteUpdate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        $accountId = $request->header->account;
        if (!$this->app->accountManager->get($accountId)->hasPermission(
            PermissionType::MODIFY, $this->app->unitId, $this->app->appId, self::NAME, PermissionCategory::ASSET_NOTE, $request->getDataInt('id'))
        ) {
            $this->fillJsonResponse($request, $response, NotificationLevel::HIDDEN, '', '', []);
            $response->header->status = RequestStatusCode::R_403;

            return;
        }

        $this->app->moduleManager->get('Editor', 'Api')->apiEditorUpdate($request, $response, $data);
    }

    /**
     * Api method to delete Note
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiNoteDelete(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        $accountId = $request->header->account;
        if (!$this->app->accountManager->get($accountId)->hasPermission(
            PermissionType::DELETE, $this->app->unitId, $this->app->appId, self::NAME, PermissionCategory::ASSET_NOTE, $request->getDataInt('id'))
        ) {
            $this->fillJsonResponse($request, $response, NotificationLevel::HIDDEN, '', '', []);
            $response->header->status = RequestStatusCode::R_403;

            return;
        }

        $this->app->moduleManager->get('Editor', 'Api')->apiEditorDelete($request, $response, $data);
    }
}
