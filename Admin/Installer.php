<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   Modules\AssetManagement\Admin
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\AssetManagement\Admin;

use phpOMS\Application\ApplicationAbstract;
use phpOMS\Config\SettingsInterface;
use phpOMS\Message\Http\HttpRequest;
use phpOMS\Message\Http\HttpResponse;
use phpOMS\Module\InstallerAbstract;
use phpOMS\Module\ModuleInfo;
use phpOMS\Uri\HttpUri;

/**
 * Installer class.
 *
 * @package Modules\AssetManagement\Admin
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class Installer extends InstallerAbstract
{
    /**
     * Path of the file
     *
     * @var string
     * @since 1.0.0
     */
    public const PATH = __DIR__;

    /**
     * {@inheritdoc}
     */
    public static function install(ApplicationAbstract $app, ModuleInfo $info, SettingsInterface $cfgHandler) : void
    {
        parent::install($app, $info, $cfgHandler);

        /* Attributes */
        $fileContent = \file_get_contents(__DIR__ . '/Install/attributes.json');
        if ($fileContent === false) {
            return;
        }

        /** @var array $attributes */
        $attributes = \json_decode($fileContent, true);
        $attrTypes  = self::createAttributeTypes($app, $attributes);
        $attrValues = self::createAttributeValues($app, $attrTypes, $attributes);

        /* Asset types */
        $fileContent = \file_get_contents(__DIR__ . '/Install/assettype.json');
        if ($fileContent === false) {
            return;
        }

        /** @var array $types */
        $types          = \json_decode($fileContent, true);
        $assetTypes = self::createAssetTypes($app, $types);
    }

    /**
     * Install asset type
     *
     * @param ApplicationAbstract $app   Application
     * @param array               $types Attribute definition
     *
     * @return array
     *
     * @since 1.0.0
     */
    private static function createAssetTypes(ApplicationAbstract $app, array $types) : array
    {
        /** @var array<string, array> $assetTypes */
        $assetTypes = [];

        /** @var \Modules\AssetManagement\Controller\ApiAssetTypeController $module */
        $module = $app->moduleManager->getModuleInstance('AssetManagement', 'ApiAssetType');

        /** @var array $type */
        foreach ($types as $type) {
            $response = new HttpResponse();
            $request  = new HttpRequest(new HttpUri(''));

            $request->header->account = 1;
            $request->setData('duration', $type['duration']);
            $request->setData('industry', $type['industry']);
            $request->setData('title', \reset($type['l11n']));
            $request->setData('language', \array_keys($type['l11n'])[0] ?? 'en');

            $module->apiAssetTypeCreate($request, $response);

            $responseData = $response->getData('');
            if (!\is_array($responseData)) {
                continue;
            }

            $assetType = \is_array($responseData['response'])
                ? $responseData['response']
                : $responseData['response']->toArray();

            $assetTypes[] = $assetType;

            $isFirst = true;
            foreach ($type['l11n'] as $language => $l11n) {
                if ($isFirst) {
                    $isFirst = false;
                    continue;
                }

                $response = new HttpResponse();
                $request  = new HttpRequest(new HttpUri(''));

                $request->header->account = 1;
                $request->setData('title', $l11n);
                $request->setData('language', $language);
                $request->setData('type', $assetType['id']);

                $module->apiAssetTypeL11nCreate($request, $response);
            }
        }

        return $assetTypes;
    }

    /**
     * Install inspection type
     *
     * @param ApplicationAbstract $app   Application
     * @param array               $types Attribute definition
     *
     * @return array
     *
     * @since 1.0.0
     */
    private static function createInspectionTypes(ApplicationAbstract $app, array $types) : array
    {
        /** @var array<string, array> $inspectionTypes */
        $inspectionTypes = [];

        /** @var \Modules\AssetManagement\Controller\ApiInspectionTypeController $module */
        $module = $app->moduleManager->getModuleInstance('AssetManagement', 'ApiInspectionType');

        /** @var array $type */
        foreach ($types as $type) {
            $response = new HttpResponse();
            $request  = new HttpRequest(new HttpUri(''));

            $request->header->account = 1;
            $request->setData('name', $type['name'] ?? '');
            $request->setData('title', \reset($type['l11n']));
            $request->setData('language', \array_keys($type['l11n'])[0] ?? 'en');

            $module->apiInspectionTypeCreate($request, $response);

            $responseData = $response->getData('');
            if (!\is_array($responseData)) {
                continue;
            }

            $inspectionTypes[$type['name']] = \is_array($responseData['response'])
                ? $responseData['response']
                : $responseData['response']->toArray();

            $isFirst = true;
            foreach ($type['l11n'] as $language => $l11n) {
                if ($isFirst) {
                    $isFirst = false;
                    continue;
                }

                $response = new HttpResponse();
                $request  = new HttpRequest(new HttpUri(''));

                $request->header->account = 1;
                $request->setData('title', $l11n);
                $request->setData('language', $language);
                $request->setData('type', $inspectionTypes[$type['name']]['id']);

                $module->apiInspectionTypeL11nCreate($request, $response);
            }
        }

        return $inspectionTypes;
    }

    /**
     * Install default attribute types
     *
     * @param ApplicationAbstract $app        Application
     * @param array               $attributes Attribute definition
     *
     * @return array
     *
     * @since 1.0.0
     */
    private static function createAttributeTypes(ApplicationAbstract $app, array $attributes) : array
    {
        /** @var array<string, array> $itemAttrType */
        $itemAttrType = [];

        /** @var \Modules\AssetManagement\Controller\ApiAssetAttributeController $module */
        $module = $app->moduleManager->getModuleInstance('AssetManagement', 'ApiAssetAttribute');

        /** @var array $attribute */
        foreach ($attributes as $attribute) {
            $response = new HttpResponse();
            $request  = new HttpRequest(new HttpUri(''));

            $request->header->account = 1;
            $request->setData('name', $attribute['name'] ?? '');
            $request->setData('title', \reset($attribute['l11n']));
            $request->setData('language', \array_keys($attribute['l11n'])[0] ?? 'en');
            $request->setData('is_required', $attribute['is_required'] ?? false);
            $request->setData('custom', $attribute['is_custom_allowed'] ?? false);
            $request->setData('validation_pattern', $attribute['validation_pattern'] ?? '');
            $request->setData('datatype', (int) $attribute['value_type']);

            $module->apiAssetAttributeTypeCreate($request, $response);

            $responseData = $response->getData('');
            if (!\is_array($responseData)) {
                continue;
            }

            $itemAttrType[$attribute['name']] = \is_array($responseData['response'])
                ? $responseData['response']
                : $responseData['response']->toArray();

            $isFirst = true;
            foreach ($attribute['l11n'] as $language => $l11n) {
                if ($isFirst) {
                    $isFirst = false;
                    continue;
                }

                $response = new HttpResponse();
                $request  = new HttpRequest(new HttpUri(''));

                $request->header->account = 1;
                $request->setData('title', $l11n);
                $request->setData('language', $language);
                $request->setData('type', $itemAttrType[$attribute['name']]['id']);

                $module->apiAssetAttributeTypeL11nCreate($request, $response);
            }
        }

        return $itemAttrType;
    }

    /**
     * Create default attribute values for types
     *
     * @param ApplicationAbstract                                                                                                                                                              $app          Application
     * @param array                                                                                                                                                                            $itemAttrType Attribute types
     * @param array<array{name:string, l11n?:array<string, string>, is_required?:bool, is_custom_allowed?:bool, validation_pattern?:string, value_type?:string, values?:array<string, mixed>}> $attributes   Attribute definition
     *
     * @return array
     *
     * @since 1.0.0
     */
    private static function createAttributeValues(ApplicationAbstract $app, array $itemAttrType, array $attributes) : array
    {
        /** @var array<string, array> $itemAttrValue */
        $itemAttrValue = [];

        /** @var \Modules\AssetManagement\Controller\ApiAssetAttributeController $module */
        $module = $app->moduleManager->getModuleInstance('AssetManagement', 'ApiAssetAttribute');

        foreach ($attributes as $attribute) {
            $itemAttrValue[$attribute['name']] = [];

            /** @var array $value */
            foreach ($attribute['values'] as $value) {
                $response = new HttpResponse();
                $request  = new HttpRequest(new HttpUri(''));

                $request->header->account = 1;
                $request->setData('value', $value['value'] ?? '');
                $request->setData('unit', $value['unit'] ?? '');
                $request->setData('default', true); // always true since all defined values are possible default values
                $request->setData('type', $itemAttrType[$attribute['name']]['id']);

                if (isset($value['l11n']) && !empty($value['l11n'])) {
                    $request->setData('title', \reset($value['l11n']));
                    $request->setData('language', \array_keys($value['l11n'])[0] ?? 'en');
                }

                $module->apiAssetAttributeValueCreate($request, $response);

                $responseData = $response->getData('');
                if (!\is_array($responseData)) {
                    continue;
                }

                $attrValue = \is_array($responseData['response'])
                    ? $responseData['response']
                    : $responseData['response']->toArray();

                $itemAttrValue[$attribute['name']][] = $attrValue;

                $isFirst = true;
                foreach (($value['l11n'] ?? []) as $language => $l11n) {
                    if ($isFirst) {
                        $isFirst = false;
                        continue;
                    }

                    $response = new HttpResponse();
                    $request  = new HttpRequest(new HttpUri(''));

                    $request->header->account = 1;
                    $request->setData('title', $l11n);
                    $request->setData('language', $language);
                    $request->setData('value', $attrValue['id']);

                    $module->apiAssetAttributeValueL11nCreate($request, $response);
                }
            }
        }

        return $itemAttrValue;
    }
}
