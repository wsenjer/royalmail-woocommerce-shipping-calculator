<?php
/**
 * Box packing (3D bin packing, knapsack problem).
 *
 * @author Doug Wright
 */
namespace WPRuby\RoyalMailLite\DVDoug\BoxPacker\Test;

use WPRuby\RoyalMailLite\DVDoug\BoxPacker\Box;
use WPRuby\RoyalMailLite\DVDoug\BoxPacker\ConstrainedPlacementItem;
use WPRuby\RoyalMailLite\DVDoug\BoxPacker\PackedItem;
use WPRuby\RoyalMailLite\DVDoug\BoxPacker\PackedItemList;

class ConstrainedPlacementNoStackingTestItem extends TestItem implements ConstrainedPlacementItem
{
    /**
     * Hook for user implementation of item-specific constraints, e.g. max <x> batteries per box.
     *
     * @param  Box            $box
     * @param  PackedItemList $alreadyPackedItems
     * @param  int            $proposedX
     * @param  int            $proposedY
     * @param  int            $proposedZ
     * @param  int            $width
     * @param  int            $length
     * @param  int            $depth
     * @return bool
     */
    public function canBePacked(
        Box $box,
        PackedItemList $alreadyPackedItems,
        $proposedX,
        $proposedY,
        $proposedZ,
        $width,
        $length,
        $depth
    ) {
        $alreadyPackedType = array_filter(
            iterator_to_array($alreadyPackedItems, false),
            function (PackedItem $item) {
                return $item->getItem()->getDescription() === $this->getDescription();
            }
        );

        /** @var PackedItem $alreadyPacked */
        foreach ($alreadyPackedType as $alreadyPacked) {
            if (
                $alreadyPacked->getZ() + $alreadyPacked->getDepth() === $proposedZ &&
                $proposedX >= $alreadyPacked->getX() && $proposedX <= ($alreadyPacked->getX() + $alreadyPacked->getWidth()) &&
                $proposedY >= $alreadyPacked->getY() && $proposedY <= ($alreadyPacked->getY() + $alreadyPacked->getLength())) {
                return false;
            }
        }

        return true;
    }
}
