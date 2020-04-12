define([], function() {
    function KF(X0, P0) {
        this['x'] = X0;
        this['P'] = P0;
    }

    KF.prototype = {
        'x': null,
        'P': null,
        //
        'A': null,
        'B': null,
        'C': null,
        //
        'u': null,
        'w': null,
        'Q': null,
        //
        'update': function (ob) {

            // x_k: Predicted State vector / Estimated signal
            // X_k: State vector
            var x = this['x']; // Get prev state

            // p_k: Predicted Covariance Matrix
            // P_k: Covariance Matrix
            var P = this['P']; // Get prev cov

            // A: Format Matrix
            // B: Format Matrix
            // C: Format Matrix
            // H: Control Matrix
            var A = ob['A'];
            var B = ob['B'];
            var C = ob['C'];
            var H = ob['H'];

            // U_k: Control Variable Matrix
            var u = ob['u'];

            // W_k: Predicted State noise
            var w = ob['w'];

            // Q_k: Process-Noise (Keeps state Cov-Matrix from becoming too small)
            var Qk = ob['Q'];

            // y_k: New Observation
            // Y_k: Observed value
            var y = ob['y'];

            // Z_k: Measure noise, the noise we expect the measurement was generated on
            var z = ob['z'];

            // K: Kalman Gain
            // R: Sensor Noise Covariance
            var R = ob['R'];

            // Predict State
            // x_k = A * X_{k-1} + B * U_k + W_k
            var xhat = A.multiply(x).add(B.multiply(u));
            console.log('xhat');

            if (w) {
                xhat = xhat.add(w);
            }
console.log('ahyet');
            // Predicted process Covariance Matrix
            // P_k = A * P_{k-1} * A^t + Q_k
            var Phat = A.multiply(P).multiply(A.transpose());

            if (Qk) {
                Phat = Phat.add(Qk);
            }

            // Kalman Gain, weight for measurement and model
            // K = P_k * H^t * (H * p_k * H^t + R)^-1
            var K = Phat.multiply(H.transpose());
            var T = H.multiply(K).add(R);


            K = K.multiply(T.inverse()); // TODO: OPTIMIZE!


            // New Observation
            // y_k = C * Y_k + Z_k
            var yk = C.multiply(y);
            console.log('C y');
            console.log(C);
            console.log(y);
            console.log('yk');
            console.log(yk);
            if (z) {
                yk = yk.add(z);
            }

            // Update state
            // X_k = x_k + K(y_k - H * x_k)
            x = xhat.add(K.multiply(yk.subtract(H.multiply(xhat))));

            // Update process Covariance Matrix
            // P_k = (I - K * H) * p_k   = OR =   p_k - K * H * p_k
            P = Phat.subtract(K.multiply(H).multiply(Phat));

            // Set Current state
            this['x'] = x;
            this['P'] = P;
        },
        'setProcessNoise': function (noise) {
            this['Q'] = noise;
        },
        'getState': function () {
            return this['X'];
        }
    };

    return KF;
});
