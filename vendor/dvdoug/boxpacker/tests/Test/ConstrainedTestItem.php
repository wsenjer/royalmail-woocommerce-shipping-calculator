<?php
/**
 * Box packing (3D bin packing, knapsack problem).
 *
 * @author Doug Wright
 */
namespace WPRuby\RoyalMailLite\DVDoug\BoxPacker\Test;

use WPRuby\RoyalMailLite\DVDoug\BoxPacker\Box;
use WPRuby\RoyalMailLite\DVDoug\BoxPacker\ConstrainedItem;
use WPRuby\RoyalMailLite\DVDoug\BoxPacker\Item;
use WPRuby\RoyalMailLite\DVDoug\BoxPacker\ItemList;

class ConstrainedTestItem extends TestItem implements ConstrainedItem
{
    /**
     * @var int
     */
    public static $limit = 3;

    /**
     * @param ItemList $alreadyPackedItems
     * @param Box            $box
     *
     * @return bool
     */
    public function canBePackedInBox(ItemList $alreadyPackedItems, Box $box)
    {
        $alreadyPackedType = array_filter(
            iterator_to_array($alreadyPackedItems, false),
            function (Item $item) {
                return $item->getDescription() === $this->getDescription();
            }
        );

        return count($alreadyPackedType) + 1 <= static::$limit;
    }
}
