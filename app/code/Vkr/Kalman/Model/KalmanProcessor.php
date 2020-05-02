<?php
namespace Vkr\Kalman\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;
use MathPHP\LinearAlgebra\MatrixFactory;
use Vkr\Kalman\Api\Data\AttributeInterface;
use Vkr\Kalman\Model\ResourceModel\Attribute as AttributeResource;

class KalmanProcessor
{
    protected $kalmanCalculate;

    public function __construct(
        \Vkr\Kalman\Model\KalmanCalculate $kalmanCalculate
    ) {
        $this->kalmanCalculate = $kalmanCalculate;
    }

    public function getKalmanValue($clearValues, $oldValueX, $newValueX, $newValueP)
    {
        if (!$newValueX || !$newValueP) {
            $newValue = null;
            $u = null;
        } else {
            $newValue['x'] = unserialize($newValueX);
            $newValue['P'] = unserialize($newValueP);
        }
        $u = null;
        if ($oldValueX && $newValueX) {
            $oldValueX = unserialize($oldValueX);
            $newValueX = unserialize($newValueX);
            if (count($oldValueX) != count($newValueX)) {
                $u = null;
            } else {
                $u = [];
                for ($i = 0; $i < count($oldValueX); ++$i) {
                    $u[] = $newValueX[$i] - $oldValueX[$i];
                }
            }
        }

        $result = $this->kalmanCalculate->update($this->prepareMatrix($newValue, $u, $clearValues));

        return $result;
    }

    protected function prepareMatrix($oldValue, $u, $clearValues)
    {
        $clearValues = unserialize($clearValues);
        $n = count($clearValues);
        if (!$oldValue) {
            $oldValue['x'] = MatrixFactory::Zero($n, 1);
            $oldValue['P'] = MatrixFactory::eye($n, $n, 0);
        } else {
            $oldValue['x'] = MatrixFactory::create($oldValue['x']);
            $oldValue['P'] = MatrixFactory::create($oldValue['P']);
        }
        if (is_null($u)) {
            $u = MatrixFactory::Zero($n, 1);
        } else {
            $u = MatrixFactory::create($u);
        }

        $ob = [
            'A' => MatrixFactory::eye($n, $n, 0),
            'B' => MatrixFactory::Zero($n, $n),
            'H' => MatrixFactory::eye($n, $n, 0),
            'C' => MatrixFactory::eye($n, $n, 0),
            'Q' => MatrixFactory::eye($n, $n, 0)->scalarMultiply(1e-11), //шум
            'R' => MatrixFactory::eye($n, $n, 0)->scalarMultiply(0.00001),// ошибка
            'z' => null,
            'w' => null,
            'y' => MatrixFactory::create($this->getMatrixY($clearValues)),//[[15], [10]]
            'u' => $u, // скорость, управляющее воздействие
            'x' => $oldValue['x'],
            'P' => $oldValue['P'],
        ];

        return $ob;
    }

    protected function getMatrixY($clearValues)
    {
        $result = [];
        foreach ($clearValues as $value) {
            $result[] = [$value];
        }
        return $result;
    }



}
