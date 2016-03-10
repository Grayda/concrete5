<?php
namespace Concrete\Core\Entity\Attribute\Key\Type;

use Concrete\Core\Entity\Attribute\Value\Value\SocialLinksValue;

/**
 * @Entity
 * @Table(name="SocialLinksAttributeKeyTypes")
 */
class SocialLinksType extends Type
{
    public function getAttributeValue()
    {
        return new SocialLinksValue();
    }

}
