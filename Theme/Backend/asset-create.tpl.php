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

use Modules\AssetManagement\Models\AssetStatus;
use phpOMS\Uri\UriFactory;

$assetStatus = AssetStatus::getConstants();

/**
 * @var \phpOMS\Views\View $this
 */
echo $this->data['nav']->render();
?>
<div class="row">
    <div class="col-xs-12 col-md-6">
        <section class="portlet">
        <form id="iUnitUploadForm" action="<?= UriFactory::build('{/api}accounting/asset?csrf={$CSRF}'); ?>" method="put">
            <div class="portlet-head"><?= $this->getHtml('Asset'); ?></div>
            <div class="portlet-body">
                <div class="form-group">
                    <label for="iAssetStatus"><?= $this->getHtml('Status'); ?></label>
                    <select id="iAssetStatus" name="asset_status">
                        <?php foreach ($assetStatus as $status) : ?>
                            <option value="<?= $status; ?>"><?= $this->getHtml(':status' . $status); ?>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="iAssetNumber"><?= $this->getHtml('Number'); ?></label>
                    <input type="text" id="iAssetNumber" name="number" value="">
                </div>

                <div class="form-group">
                    <label for="iAssetName"><?= $this->getHtml('Name'); ?></label>
                    <input type="text" id="iAssetName" name="name" value="" required>
                </div>

                <?php if ($this->data['hasEquipmentManagement'] ?? false) : ?>
                <div id="iEquipmentGroup" class="form-group vh">
                    <label for="iAssetEquipment"><?= $this->getHtml('Equipment'); ?></label>
                    <input type="number" id="iAssetEquipment" name="equipment" value="">
                </div>

                <div class="form-group">
                    <label class="checkbox" for="iAssetCreateEquipment">
                        <input id="iAssetCreateEquipment" type="checkbox" name="create_equipment" value="1"
                            data-action='[
                                {"key": 1, "listener": "change", "action": [
                                    {"key": 1, "type": "dom.get", "base": "", "selector": "#iAssetCreateEquipment"},
                                    {"key": 2, "type": "if", "conditions": [
                                        {
                                            "comp": "==",
                                            "value": "1",
                                            "jump": 3
                                        },
                                        {
                                            "comp": "",
                                            "value": "0",
                                            "jump": 5
                                        }
                                    ]},
                                    {"key": 3, "type": "dom.attr.change", "subtype": "add", "attr": "class", "value": "vh", "base": "", "selector": "#iEquipmentGroup"},
                                    {"key": 4, "type": "jump", "jump": 6},
                                    {"key": 5, "type": "dom.attr.change", "subtype": "remove", "attr": "class", "value": "vh", "base": "", "selector": "#iEquipmentGroup"}
                                ]}
                            ]' checked>
                        <span class="checkmark"></span>
                        <?= $this->getHtml('CreateEquipment'); ?>
                    </label>
                </div>
                <?php endif; ?>
            </div>
            <div class="portlet-foot">
                <input id="iCreateSubmit" type="Submit" value="<?= $this->getHtml('Create', '0', '0'); ?>">
            </div>
            </form>
        </section>
    </div>
</div>
