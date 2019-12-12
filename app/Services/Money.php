<?php

namespace App\Services;

use Money\Currency;
use NumberFormatter;
use Money\Money as BaseMoney;
use Money\Currencies\ISOCurrencies;
use Money\Formatter\IntlMoneyFormatter;

class Money
{
    protected $money;

    /**
     * Constructor for the Money class.
     *
     * @param $money
     */
    public function __construct($money)
    {
        $this->money = new BaseMoney($money, new Currency('GBP'));
    }

    /**
     * Returns amount from the money instance.
     *
     * @return [type] [description]
     */
    public function amount()
    {
        return $this->money->getAmount();
    }

    /**
     * Return formatted amount from Money instance.
     *
     * @return [type] [description]
     */
    public function formatted()
    {
        $formatter = new IntlMoneyFormatter(
            new NumberFormatter('en_GB', NumberFormatter::CURRENCY),
            new ISOCurrencies
        );

        return $formatter->format($this->money);
    }
}
