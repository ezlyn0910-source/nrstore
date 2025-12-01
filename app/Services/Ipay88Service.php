<?php

namespace App\Services;

class Ipay88Service
{
    public static function formatAmount($amount)
    {
        // MyR with 2 decimals + thousands; then remove . and , for signature
        return number_format($amount, 2, '.', '');
    }

    public static function signatureRequest(string $refNo, string $amount, string $currency): string
    {
        // amount for signature must have no '.' or ',' :contentReference[oaicite:2]{index=2}
        $merchantKey  = config('ipay88.merchant_key');
        $merchantCode = config('ipay88.merchant_code');
        $amountNoSep  = str_replace([',', '.'], '', $amount);

        $toHash = $merchantKey . $merchantCode . $refNo . $amountNoSep . $currency;

        // SHA1 then base64
        return base64_encode(sha1($toHash, true));
    }

    public static function signatureResponse(array $fields): string
    {
        // For response, spec uses MerchantKey+MerchantCode+PaymentId+RefNo+Amount+Currency+Status :contentReference[oaicite:3]{index=3}
        $merchantKey  = config('ipay88.merchant_key');
        $merchantCode = config('ipay88.merchant_code');

        $amountNoSep  = str_replace([',', '.'], '', $fields['Amount'] ?? '');

        $toHash = $merchantKey
            . $merchantCode
            . ($fields['PaymentId'] ?? '')
            . ($fields['RefNo'] ?? '')
            . $amountNoSep
            . ($fields['Currency'] ?? '')
            . ($fields['Status'] ?? '');

        return base64_encode(sha1($toHash, true));
    }
}
