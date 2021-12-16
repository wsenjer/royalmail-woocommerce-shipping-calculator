<?php

/**
 * Box packing (3D bin packing, knapsack problem).
 *
 * @author Doug Wright
 */
namespace WPRuby\RoyalMailLite\DVDoug\BoxPacker;

/**
 * Class ItemTooLargeException
 * Exception used when an item is too large to pack into any box.
 */
class ItemTooLargeException extends NoBoxesAvailableException
{
}
