Advanced usage
==============

Used / remaining space
----------------------

After packing it is possible to see how much physical space in each ``PackedBox`` is taken up with items,
and how much space was unused (air). This information might be useful to determine whether it would be useful to source
alternative/additional sizes of box.

At a high level, the ``getVolumeUtilisation()`` method exists which calculates how full the box is as a percentage of volume.

Lower-level methods are also available for examining this data in detail either using ``getUsed[Width|Length|Depth()]``
(a hypothetical box placed around the items) or ``getRemaining[Width|Length|Depth()]`` (the difference between the dimensions of
the actual box and the hypothetical box).

.. note::

    BoxPacker will always try to pack items into the smallest box available

Example - warning on a massively oversized box
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

.. code-block:: php

    <?php

        // assuming packing already took place
        foreach ($packedBoxes as $packedBox) {
            if ($packedBox->getVolumeUtilisation() < 20) {
                // box is 80% air, log a warning
            }
        }

Positional information
----------------------
It is also possible to see the precise positional and dimensional information of each item as packed. This is exposed as x,y,z
co-ordinates from origin, alongside width/length/depth in the packed orientation. ::

           z    y
           |   /
           |  /
           | /
           |/
           0------x

Example
^^^^^^^

.. code-block:: php

    <?php

        // assuming packing already took place
        foreach ($packedBoxes as $packedBox) {
            $packedItems = $packedBox->getItems();
            foreach ($packedItems as $packedItem) { // $packedItem->getItem() is your own item object
                echo $packedItem->getItem()->getDescription() .  ' was packed at co-ordinate ' ;
                echo '(' . $packedItem->getX() . ', ' . $packedItem->getY() . ', ' . $packedItem->getZ() . ') with ';
                echo 'l' . $packedItem->getLength() . ', w' . $packedItem->getWidth() . ', d' . $packedItem->getDepth();
                echo PHP_EOL;
            }
        }

Custom Constraints
------------------

For more advanced use cases where greater control over the contents of each box is required (e.g. legal limits on the number of
hazardous items per box, or perhaps fragile items requiring an extra-strong outer box) you may implement the ``BoxPacker\ConstrainedPlacementItem``
interface which contains an additional callback method allowing you to decide whether to allow an item may be packed into a box
or not.

As with all other library methods, the objects passed into this callback are your own - you have access to their full range of
properties and methods to use when evaluating a constraint, not only those defined by the standard ``BoxPacker\Item`` interface.

Example - only allow 2 batteries per box
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

.. code-block:: php

    <?php
        use DVDoug\BoxPacker\Box;
        use DVDoug\BoxPacker\Item;
        use DVDoug\BoxPacker\ItemList;

        class LithiumBattery implements ConstrainedPlacementItem
        {

            /**
             * Max 2 batteries per box.
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
                int $proposedX,
                int $proposedY,
                int $proposedZ,
                int $width,
                int $length,
                int $depth
            ) {
                $batteriesPacked = 0;
                foreach ($alreadyPackedItems as $packedItem) {
                  if ($packedItem instanceof LithiumBattery) {
                      $batteriesPacked++;
                  }
                }

                if ($batteriesPacked < 2) {
                    return true;  // allowed to pack
                } else {
                    return false; // 2 batteries already packed, no more allowed in this box
                }
            }
        }

Example - don't allow batteries to be stacked
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

.. code-block:: php

    <?php
        use DVDoug\BoxPacker\Box;
        use DVDoug\BoxPacker\Item;
        use DVDoug\BoxPacker\ItemList;

        class LithiumBattery implements ConstrainedPlacementItem
        {

            /**
             * Batteries cannot be stacked on top of each other.
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
                int $proposedX,
                int $proposedY,
                int $proposedZ,
                int $width,
                int $length,
                int $depth
            ) {
                $alreadyPackedType = array_filter(
                    iterator_to_array($alreadyPackedItems, false),
                    function (Item $item) {
                        return $item->getDescription() === 'Battery';
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
