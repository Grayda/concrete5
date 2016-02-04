<?php
namespace Concrete\Core\Package\Item\Manager;

use Concrete\Core\Entity\Package;
use Concrete\Controller\Element\Package\ThemeItemList;

defined('C5_EXECUTE') or die("Access Denied.");

class AttributeKey extends AbstractItem
{

    public function getItemCategoryDisplayName()
    {
        return t('Attribute Keys');
    }

    public function getItemName($key)
    {
        return $key->getAttributeKeyDisplayName();
    }

    public function getPackageItems(Package $package)
    {
        return array();
    }

}