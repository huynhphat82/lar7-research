<?php

namespace Tests\Common\Traits;

Trait Asserts
{
    /**
     * Assert the expected values are contained in target array
     *
     * @param  array $expectedParts
     * @param  mixed $haystack
     * @return void
     */
    public function assertContainsValuePartialOfArray($expectedParts, array $haystack)
    {
        $errors = [];

        if (!is_array($expectedParts)) {
            $expectedParts = [$expectedParts];
        }

        foreach ($expectedParts as $expectedValue) {
            if (!empty($errors)) {
                break;
            }
            if (is_object($expectedValue)) {
                $hasExist = false;
                // check values whether they exist in target array
                foreach ($haystack as $k => $v) {
                    if (!is_object($v)) {
                        $errors[] = true;
                        break;
                    } else {
                        $expectedValueArr = (array) $expectedValue;
                        $value = (array) $v;
                        $valuesChecked = array_intersect_assoc($expectedValueArr, $value);
                        if (count($expectedValueArr) === count($valuesChecked)) {
                            $hasExist = true;
                            break;
                        }
                    }
                }
                if (!$hasExist) {
                    $errors[] = true;
                    break;
                }
            } else {
                if (!in_array($expectedValue, $haystack)) {
                    $errors[] = true;
                    break;
                }
            }
        }
        $this->assertTrue(empty($errors));
    }
}
