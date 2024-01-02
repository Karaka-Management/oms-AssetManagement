<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   Modules\ClientManagement
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

use Modules\AssetManagement\Models\AssetStatus;
use Modules\AssetManagement\Models\NullAsset;
use Modules\Media\Models\NullMedia;
use phpOMS\Uri\UriFactory;

$countryCodes    = \phpOMS\Localization\ISO3166TwoEnum::getConstants();
$countries       = \phpOMS\Localization\ISO3166NameEnum::getConstants();
$assetStatus = AssetStatus::getConstants();

/**
 * @var \Modules\AssetManagement\Models\Asset $asset
 */
$asset       = $this->data['asset'] ?? new NullAsset();
$files           = $asset->files;
$assetImage  = $this->data['assetImage'] ?? new NullMedia();
$assetTypes  = $this->data['types'] ?? [];
$attributeView   = $this->data['attributeView'];

/**
 * @var \phpOMS\Views\View $this
 */
echo $this->data['nav']->render();
?>
<div class="tabview tab-2">
    <div class="box">
        <ul class="tab-links">
            <li><label for="c-tab-1"><?= $this->getHtml('Profile'); ?></label>
            <li><label for="c-tab-2"><?= $this->getHtml('Attributes'); ?></label>
            <li><label for="c-tab-3"><?= $this->getHtml('Files'); ?></label>
            <li><label for="c-tab-4"><?= $this->getHtml('Notes'); ?></label>
            <li><label for="c-tab-5"><?= $this->getHtml('Inspections'); ?></label>
            <li><label for="c-tab-8"><?= $this->getHtml('Costs'); ?></label>
        </ul>
    </div>
    <div class="tab-content">
        <input type="radio" id="c-tab-1" name="tabular-2"<?= $this->request->uri->fragment === 'c-tab-1' ? ' checked' : ''; ?>>
        <div class="tab">
            <div class="row">
                <div class="col-xs-12 col-md-6">
                    <section class="portlet">
                        <div class="portlet-head"><?= $this->getHtml('Profile'); ?></div>
                        <div class="portlet-body">
                            <div class="form-group">
                                <label for="iAssetAssetProfileName"><?= $this->getHtml('Name'); ?></label>
                                <input type="text" id="iAssetAssetProfileName" name="name" value="<?= $this->printHtml($asset->name); ?>">
                            </div>

                            <div class="form-group">
                                <label for="iAssetDriver"><?= $this->getHtml('Driver'); ?></label>
                                <input type="text" id="iAssetDriver" name="driver" value="" disabled>
                            </div>

                            <div class="form-group">
                                <label for="iAssetEin"><?= $this->getHtml('EIN'); ?></label>
                                <input type="text" id="iAssetEin" name="vin" value="<?= $this->printHtml($asset->getAttribute('vin')->value->getValue()); ?>">
                            </div>

                            <div class="form-group">
                                <label for="iAssetStatus"><?= $this->getHtml('Status'); ?></label>
                                <select id="iAssetStatus" name="asset_status">
                                    <?php foreach ($assetStatus as $status) : ?>
                                        <option value="<?= $status; ?>"<?= $status === $asset->status ? ' selected' : ''; ?>><?= $this->getHtml(':status' . $status); ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="iAssetEnd"><?= $this->getHtml('Type'); ?></label>
                                <select id="iAssetEnd" name="asset_type">
                                    <?php foreach ($assetTypes as $type) : ?>
                                        <option value="<?= $type->id; ?>"<?= $asset->type->id === $type->id ? ' selected' : ''; ?>><?= $this->printHtml($type->getL11n()); ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="iAssetMake"><?= $this->getHtml('Make'); ?></label>
                                <input type="text" id="iAssetMake" name="make" value="<?= $this->printHtml($asset->getAttribute('maker')->value->getValue()); ?>">
                            </div>

                            <div class="form-group">
                                <label for="iAssetModel"><?= $this->getHtml('Model'); ?></label>
                                <input type="text" id="iAssetModel" name="asset_model" value="<?= $this->printHtml($asset->getAttribute('asset_model')->value->getValue()); ?>">
                            </div>

                            <div class="form-group">
                                <label for="iAssetStart"><?= $this->getHtml('Start'); ?></label>
                                <input type="datetime-local" id="iAssetStart" name="ownership_start" value="<?= $asset->getAttribute('ownership_start')->value->getValue()?->format('Y-m-d\TH:i') ?? $asset->createdAt->format('Y-m-d\TH:i'); ?>">
                            </div>

                            <div class="form-group">
                                <label for="iAssetEnd"><?= $this->getHtml('End'); ?></label>
                                <input type="datetime-local" id="iAssetEnd" name="ownership_end" value="<?= $asset->getAttribute('ownership_end')->value->getValue()?->format('Y-m-d\TH:i'); ?>">
                            </div>

                            <div class="form-group">
                                <label for="iAssetPrice"><?= $this->getHtml('PurchasePrice'); ?></label>
                                <input type="number" step="0.01" id="iAssetPrice" name="purchase_price" value="<?= $this->printHtml($asset->getAttribute('purchase_price')->value->getValue()); ?>">
                            </div>

                            <div class="form-group">
                                <label for="iAssetPrice"><?= $this->getHtml('LeasingFee'); ?></label>
                                <input type="number" step="0.01" id="iAssetPrice" name="leasing_fee" value="<?= $this->printHtml($asset->getAttribute('leasing_fee')->value->getValue()); ?>">
                            </div>
                        </div>
                        <div class="portlet-foot">
                            <?php if ($asset->id === 0) : ?>
                                <input id="iCreateSubmit" type="Submit" value="<?= $this->getHtml('Create', '0', '0'); ?>">
                            <?php else : ?>
                                <input id="iSaveSubmit" type="Submit" value="<?= $this->getHtml('Save', '0', '0'); ?>">
                            <?php endif; ?>
                        </div>
                    </section>
                </div>

                <div class="col-xs-12 col-md-6">
                    <section class="portlet">
                        <div class="portlet-body">
                            <img width="100%" src="<?= $assetImage->id === 0
                                ? 'Web/Backend/img/logo_grey.png'
                                : UriFactory::build($assetImage->getPath()); ?>"></a>
                        </div>
                    </section>
                </div>
            </div>
        </div>

        <input type="radio" id="c-tab-2" name="tabular-2"<?= $this->request->uri->fragment === 'c-tab-2' ? ' checked' : ''; ?>>
        <div class="tab">
            <div class="row">
                <?= $attributeView->render(
                    $asset->attributes,
                    $this->data['attributeTypes'] ?? [],
                    $this->data['units'] ?? [],
                    '{/api}fleet/asset/attribute',
                    $asset->id
                    );
                ?>
            </div>
        </div>

        <input type="radio" id="c-tab-3" name="tabular-2"<?= $this->request->uri->fragment === 'c-tab-3' ? ' checked' : ''; ?>>
        <div class="tab col-simple">
            <?= $this->data['media-upload']->render('asset-file', 'files', '', $asset->files); ?>
        </div>

        <input type="radio" id="c-tab-4" name="tabular-2"<?= $this->request->uri->fragment === 'c-tab-4' ? ' checked' : ''; ?>>
        <div class="tab">
            <?= $this->data['asset-notes']->render('asset-notes', '', $asset->notes); ?>
        </div>

        <input type="radio" id="c-tab-5" name="tabular-2"<?= $this->request->uri->fragment === 'c-tab-5' ? ' checked' : ''; ?>>
        <div class="tab">
            <div class="row">
                <a class="button" href="<?= UriFactory::build('{/base}/fleet/inspection/create?asset=' . $asset->id); ?>"><?= $this->getHtml('Create', '0', '0'); ?></a>
            </div>
            <div class="row">
                <div class="col-xs-12 col-md-6">
                    <section class="portlet">
                        <div class="portlet-head"><?= $this->getHtml('Upcoming'); ?></div>
                        <table id="upcomingInspections" class="default sticky">
                            <thead>
                                <tr>
                                    <td><?= $this->getHtml('Date'); ?>
                                    <td class="wf-100"><?= $this->getHtml('Type'); ?>
                                    <td><?= $this->getHtml('Responsible'); ?>
                            <tbody>
                            <?php foreach ($this->data['inspections'] as $inspection) :
                                // @todo handle old inspections in the past? maybe use a status?!
                                if ($inspection->next === null) {
                                    continue;
                                }
                            ?>
                                <tr>
                                    <td><?= $inspection->next->format('Y-m-d H:i'); ?>
                                    <td><?= $this->printHtml($inspection->type->getL11n()); ?>
                                    <td>
                            <?php endforeach; ?>
                        </table>
                    </section>
                </div>

                <div class="col-xs-12 col-md-6">
                    <section class="portlet">
                        <div class="portlet-head"><?= $this->getHtml('History'); ?></div>
                        <table id="historicInspections" class="default sticky">
                            <thead>
                                <tr>
                                    <td><?= $this->getHtml('Date'); ?>
                                    <td class="wf-100"><?= $this->getHtml('Type'); ?>
                                    <td><?= $this->getHtml('Responsible'); ?>
                            <tbody>
                            <?php foreach ($this->data['inspections'] as $inspection) : ?>
                                <tr>
                                    <td><?= $inspection->date->format('Y-m-d H:i'); ?>
                                    <td><?= $this->printHtml($inspection->type->getL11n()); ?>
                                    <td>
                            <?php endforeach; ?>
                        </table>
                    </section>
                </div>
            </div>
        </div>

        <input type="radio" id="c-tab-8" name="tabular-2"<?= $this->request->uri->fragment === 'c-tab-8' ? ' checked' : ''; ?>>
        <div class="tab">
            <div class="row">
                <div class="col-xs-12 col-md-6">
                </div>
            </div>
        </div>
    </div>
</div>