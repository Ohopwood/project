<?php
defined('C5_EXECUTE') or die("Access Denied.");
?>


<form method="post" action="<?php echo $action ?>" id="ccm-attribute-key-form">
    <?php

    $c = Page::getCurrentPage();

    $form = Loader::helper('form');
    $ih = Loader::helper("concrete/ui");
    $valt = Loader::helper('validation/token');
    $akName = '';
    $akIsSearchable = 1;
    $asID = 0;

    if (is_object($key)) {
        if (!isset($akHandle)) {
            $akHandle = $key->getAttributeKeyHandle();
        }
        $akName = $key->getAttributeKeyName();
        $akIsSearchable = $key->isAttributeKeySearchable();
        $akIsSearchableIndexed = $key->isAttributeKeyContentIndexed();
        $sets = $key->getAttributeSets();
        if (count($sets) == 1) {
            $asID = $sets[0]->getAttributeSetID();
        }
        echo $form->hidden('akID', $key->getAttributeKeyID());
    }
    ?>


    <fieldset>
        <legend><?php echo t('%s: Basic Details', $type->getAttributeTypeDisplayName()) ?></legend>

        <div class="form-group">
            <?php echo $form->label('akHandle', t('Handle')) ?>
            <div class="input-group">
                <?php echo $form->text('akHandle', $akHandle, ['autofocus' => 'autofocus']) ?>
                <span class="input-group-addon"><i class="fa fa-asterisk"></i></span>
            </div>
        </div>


        <div class="form-group">
            <?php echo $form->label('akName', t('Name')) ?>
            <div class="input-group">
                <?php echo $form->text('akName', $akName) ?>
                <span class="input-group-addon"><i class="fa fa-asterisk"></i></span>
            </div>
        </div>

        <?php if ($category && $category->getSetManager()->allowAttributeSets() == \Concrete\Core\Attribute\StandardSetManager::ASET_ALLOW_SINGLE) {
    ?>
            <div class="form-group">
                <?php echo $form->label('asID', t('Set')) ?>
                <div class="controls">
                    <?php
                    $sel = array('0' => t('** None'));
    $sets = $category->getSetManager()->getAttributeSets();
    foreach ($sets as $as) {
        $sel[$as->getAttributeSetID()] = $as->getAttributeSetDisplayName();
    }
    echo $form->select('asID', $sel, $asID);
    ?>
                </div>
            </div>
        <?php 
} ?>

        <div class="form-group">
            <label class="control-label"><?php echo t('Searchable') ?></label>

            <?php
            $keyword_label = t('Content included in search index.');
            $advanced_label = t('Field available in advanced search.');

            ?>
            <div class="checkbox"><label><?php echo $form->checkbox('akIsSearchableIndexed', 1,
                        $akIsSearchableIndexed) ?> <?php echo $keyword_label ?></label></div>
            <div class="checkbox"><label><?php echo $form->checkbox('akIsSearchable', 1,
                        $akIsSearchable) ?> <?php echo $advanced_label ?></label></div>
        </div>

    </fieldset>

    <?php echo $form->hidden('atID', $type->getAttributeTypeID()) ?>
    <?php if ($category && $category instanceof \Concrete\Core\Attribute\Category\StandardCategoryInterface) {
    ?>
        <?php echo $form->hidden('akCategoryID', $category->getCategoryEntity()->getAttributeKeyCategoryID());
    ?>

        <?php

        if ($category->getCategoryEntity()->getPackageID() > 0) {
            @Loader::packageElement('attribute/categories/' . $category->getCategoryEntity()->getAttributeKeyCategoryHandle(),
                $category->getCategoryEntity()->getPackageHandle(), array('key' => $key));
        } else {
            @Loader::element('attribute/categories/' . $category->getCategoryEntity()->getAttributeKeyCategoryHandle(),
                array('key' => $key));
        }
    ?>

    <?php 
} ?>

    <?php echo $valt->output('add_or_update_attribute') ?>
    <?php $type->render(new \Concrete\Core\Attribute\Context\AttributeTypeSettingsContext(), $key); ?>

    <?php if (!isset($back)) {
    $back = URL::page($c);
}
    ?>

    <div class="ccm-dashboard-form-actions-wrapper">
        <div class="ccm-dashboard-form-actions">
            <a href="<?php echo $back ?>" class="btn pull-left btn-default"><?php echo t('Back') ?></a>
            <?php if (is_object($key)) {
    ?>
                <button type="submit" class="btn btn-primary pull-right"><?php echo t('Save') ?></button>
            <?php 
} else {
    ?>
                <button type="submit" class="btn btn-primary pull-right"><?php echo t('Add') ?></button>
            <?php 
} ?>
        </div>
    </div>


</form>
