<?php

namespace App\Services\Invoice;

use App\Models\Offer;
use App\Models\Invoice;
use App\Repositories\Tax\Tax;
use App\Repositories\Money\Money;
use App\Models\SettingsConfig;

class InvoiceCalculator
{
    /**
     * @var Invoice
     */
    private $invoice;
    /**
     * @var Tax
     */
    private $tax;

    public function __construct($invoice)
    {
        if (!$invoice instanceof Invoice && !$invoice instanceof Offer) {
            throw new \Exception("Not correct type for Invoice Calculator");
        }
        $this->tax = new Tax();
        $this->invoice = $invoice;
    }

    public function getVatTotal()
    {
        $price = $this->getSubTotal()->getAmount();
        return new Money($price * $this->tax->vatRate());
    }

    public function getRemise(): float
    {
        return SettingsConfig::where('key', 'global_discount_rate')->value('value') ?? 0;
    }


    public function getTotalPrice(): Money
    {
        $price = 0;
        $invoiceLines = $this->invoice->invoiceLines;

        foreach ($invoiceLines as $invoiceLine) {
            $price += $invoiceLine->quantity * $invoiceLine->price;
        }

        $remise = $this->getRemise();
        $price -= $price * $remise;
        return new Money($price);
    }

    public function getSubTotal(): Money
    {
        $price = 0;
        $invoiceLines = $this->invoice->invoiceLines;

        foreach ($invoiceLines as $invoiceLine) {
            $price += $invoiceLine->quantity * $invoiceLine->price;
        }
        return new Money($price / $this->tax->multipleVatRate());
    }

    public function getAmountDue()
    {
        return new Money($this->getTotalPrice()->getAmount() - $this->invoice->payments()->sum('amount'));
    }

    public function getInvoice()
    {
        return $this->invoice;
    }

    public function getTax()
    {
        return $this->tax;
    }
}
