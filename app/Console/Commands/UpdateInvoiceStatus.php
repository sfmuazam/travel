<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Invoice;
use App\Models\Financial;
use Carbon\Carbon;

class UpdateInvoiceStatus extends Command
{
    protected $signature = 'app:update-invoice-status';
    protected $description = 'Update invoice status based on expiration time and payment status';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('Updating invoice statuses...');

        $now = Carbon::now();

        $invoices = Invoice::where('expires_at', '<', $now)
            ->whereNotIn('status', ['paid', 'cancelled'])
            ->get();

        foreach ($invoices as $invoice) {
            $invoice->status = 'cancelled';
            $invoice->updated_at = $now;
            $invoice->save();

            Financial::where('id_transaction', $invoice->transaction_id)
                ->update([
                    'status' => 'canceled',
                    'updated_at' => $now,
                ]);
        }

        $this->info('Invoice statuses updated.');
    }
}
