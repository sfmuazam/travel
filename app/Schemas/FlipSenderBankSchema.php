<?php

namespace App\Schemas;


class FlipSenderBankSchema
{
    const VIRTUAL_ACCOUNT = 'virtual_account';
    const BANK_ACCOUNT = 'bank_account';
    const WALLET_ACCOUNT = 'wallet_account';

    public static $list = [self::VIRTUAL_ACCOUNT, self::BANK_ACCOUNT, self::WALLET_ACCOUNT];
}