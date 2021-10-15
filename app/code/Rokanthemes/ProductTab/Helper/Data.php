<?php

namespace Rokanthemes\ProductTab\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
class Data extends AbstractHelper
{
    const TYPE_BEST_SELLER = 1;
    const TYPE_FEATURER = 2;
    const TYPE_MOST_VIEWED = 3;
    const TYPE_NEW = 4;
    const TYPE_TOP_RATE = 5;
    const TYPE_ON_SALE = 6;
    const TYPE_RANDOM = 7;
    public function getAllOptionTypes()
    {
        $result = [
            self::TYPE_BEST_SELLER => __('Best Seller'),
            self::TYPE_FEATURER => __('Featured'),
            self::TYPE_MOST_VIEWED => __('Most Viewed'),
            self::TYPE_NEW => __('New arrival'),
            self::TYPE_TOP_RATE => __('Top Rate'),
            self::TYPE_ON_SALE => __('On Sale'),
            self::TYPE_RANDOM => __('Random')
            ];
        return $result;
    }
    public function getTypeLabel($type)
    {
        $allTypes = $this->getAllOptionTypes();
        if(isset($allTypes[$type]))
        {
            return $allTypes[$type];
        }
        return $type;
    }
}
