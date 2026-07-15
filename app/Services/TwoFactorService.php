<?php

namespace App\Services;

class TwoFactorService
{
    public function generateSecret(int $length = 16): string
    {
        $bytes = random_bytes($length);

        return $this->base32Encode($bytes);
    }

    public function generateCode(string $secret, ?int $timestamp = null): string
    {
        $timestamp = $timestamp ?? time();
        $counter = (int) floor($timestamp / 30);

        $key = $this->base32Decode($secret);
        $packed = pack('N', $counter);
        $hash = hash_hmac('sha1', $packed, $key, true);
        $offset = ord($hash[strlen($hash) - 1]) & 0x0F;
        $binary = unpack('N', substr($hash, $offset, 4))[1] & 0x7FFFFFFF;

        return str_pad((string) ($binary % 1000000), 6, '0', STR_PAD_LEFT);
    }

    public function verifyCode(string $secret, string $code, int $window = 1): bool
    {
        if (!is_string($secret) || empty($secret) || !preg_match('/^\d{6}$/', $code)) {
            return false;
        }

        $currentTime = time();

        for ($i = -$window; $i <= $window; $i++) {
            if ($this->generateCode($secret, $currentTime + ($i * 30)) === $code) {
                return true;
            }
        }

        return false;
    }

    public function generateRecoveryCodes(int $count = 8): array
    {
        $codes = [];

        for ($i = 0; $i < $count; $i++) {
            $codes[] = strtoupper(substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 8));
        }

        return $codes;
    }

    public function verifyRecoveryCode(array $codes, string $code): bool
    {
        return in_array(strtoupper($code), $codes, true);
    }

    public function getProvisioningUri(string $email, string $secret, string $issuer = 'NVG Prime Movers'): string
    {
        $label = urlencode($issuer . ':' . $email);
        $secret = urlencode($secret);

        return 'otpauth://totp/' . $label . '?secret=' . $secret . '&issuer=' . urlencode($issuer);
    }

    private function base32Encode(string $data): string
    {
        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $output = '';
        $buffer = 0;
        $bits = 0;

        for ($i = 0; $i < strlen($data); $i++) {
            $buffer = ($buffer << 8) | ord($data[$i]);
            $bits += 8;

            while ($bits >= 5) {
                $bits -= 5;
                $output .= $alphabet[($buffer >> $bits) & 31];
            }
        }

        if ($bits > 0) {
            $output .= $alphabet[($buffer << (5 - $bits)) & 31];
        }

        return strtoupper($output);
    }

    private function base32Decode(string $secret): string
    {
        $secret = strtoupper(preg_replace('/[^A-Z2-7]/', '', $secret));
        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $output = '';
        $buffer = 0;
        $bits = 0;

        foreach (str_split($secret) as $char) {
            $value = strpos($alphabet, $char);
            if ($value === false) {
                continue;
            }

            $buffer = ($buffer << 5) | $value;
            $bits += 5;

            if ($bits >= 8) {
                $bits -= 8;
                $output .= chr(($buffer >> $bits) & 255);
            }
        }

        return $output;
    }
}
