<?php
namespace Vkr\Kalman\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;
use Vkr\Kalman\Api\Data\AttributeInterface;
use Vkr\Kalman\Model\ResourceModel\Attribute as AttributeResource;

class KalmanCalculate
{

    public function update($object)
    {
        // x_k: Predicted State vector / Estimated signal
        // X_k: State vector
        $x = $object['x']; // Get prev state


        // p_k: Predicted Covariance Matrix
        // P_k: Covariance Matrix
        $P = $object['P']; // Get prev cov

        // A: Format Matrix
        // B: Format Matrix
        // C: Format Matrix
        // H: Control Matrix
        $A = $object['A'];
        $B = $object['B'];
        $C = $object['C'];
        $H = $object['H'];

        // U_k: Control Variable Matrix
        $u = $object['u'];

        // W_k: Predicted State noise
        $w = $object['w'];

        // Q_k: Process-Noise (Keeps state Cov-Matrix from becoming too small)
        $Qk = $object['Q'];

        // y_k: New Observation
        // Y_k: Observed value
        $y = $object['y'];

        // Z_k: Measure noise, the noise we expect the measurement was generated on
        $z = $object['z'];

        // K: Kalman Gain
        // R: Sensor Noise Covariance
        $R = $object['R'];

        // Predict State
        // x_k = A * X_{k-1} + B * U_k + W_k
        $xhat = ($A->multiply($x))->add($B->multiply($u));

        if ($w) {
            $xhat = $xhat->add($w);
        }

        // Predicted process Covariance Matrix
        // P_k = A * P_{k-1} * A^t + Q_k
        $Phat = ($A->multiply($P))->multiply($A->transpose());


        if ($Qk) {
            $Phat = $Phat->add($Qk);
        }

        // Kalman Gain, weight for measurement and model
        // K = P_k * H^t * (H * p_k * H^t + R)^-1
        $K = $Phat->multiply($H->transpose());
        $T = ($H->multiply($K))->add($R);

        $K = $K->multiply($T->inverse());

        // New Observation
        // y_k = C * Y_k + Z_k
        $yk = $C->multiply($y);

        if ($z) {
            $yk = $yk->add($z);
        }

        // Update state
        // X_k = x_k + K(y_k - H * x_k)
        $x = $xhat->add($K->multiply($yk->subtract($H->multiply($xhat))));

        // Update process Covariance Matrix
        // P_k = (I - K * H) * p_k   = OR =   p_k - K * H * p_k
        $P = $Phat->subtract($K->multiply($H)->multiply($Phat));

        return [
            'x' => $x->getMatrix(),
            'P' => $P->getMatrix()
        ];
    }

}
