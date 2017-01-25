<?php namespace App;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\DB;

class Transaction extends Eloquent
{
    protected $table = 'transactions';
    public function accountCategory()
    {
        return $this->belongsTo('App\AccountCategory');
    }
    public function accountName()
    {
        return $this->belongsTo('App\NameOfAccount');
    }
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function getTotalPaid($invoice_id)
    {
        return DB::table('transactions')
            ->selectRaw('sum(amount) as totalPaid')
            ->where('invoice_id', '=', $invoice_id)
            ->where('type', '=', 'Receive')
            ->get();
    }
    public function getTotalExpensePaid($invoice_id)
    {
        return DB::table('transactions')
            ->selectRaw('sum(amount) as totalPaid')
            ->where('invoice_id', '=', $invoice_id)
            ->where('type', '=', 'Expense')
            ->get();
    }
    public function getTotalPaidPurchase($invoice_id)
    {
        return DB::table('transactions')
            ->selectRaw('sum(amount) as totalPaid')
            ->where('invoice_id', '=', $invoice_id)
            ->where('type', '=', 'Payment')
            ->get();
    }

    public static function convertDate($date){
        $date = strtotime($date);
        return date('d/m/Y', $date);
        //return 'asd';
    }
    public function getVoucherList()
    {
        return DB::table('transactions')
            ->join('sales', 'transactions.invoice_id', '=', 'sales.invoice_id')
            ->where('transactions.type', '=', 'Receive')
            ->whereNotNull('transactions.voucher_id')
            ->where('transactions.voucher_id', '!=', 0)
            ->groupBy('transactions.voucher_id')
            ->select('transactions.created_at AS date',
                'transactions.branch_id AS branch',
                'transactions.voucher_id AS voucher',
                'sales.party_id AS party',
                'sales.cash_sale',
                DB::raw('SUM(transactions.amount) AS total')
            )
            ->get();
    }
    public function getVoucher($voucher)
    {
        return DB::table('transactions')
            ->join('sales', 'transactions.invoice_id', '=', 'sales.invoice_id')
            ->where('transactions.type', '=', 'Receive')
            ->where('transactions.voucher_id', '=', $voucher)
            ->select('transactions.created_at AS date',
                'transactions.branch_id AS branch',
                'transactions.voucher_id AS voucher',
                'transactions.account_name_id',
                'transactions.type',
                'transactions.cheque_bank',
                'transactions.cheque_no',
                'transactions.payment_method',
                'transactions.created_at',
                'transactions.invoice_id',
                'transactions.user_id',
                'transactions.remarks',
                'transactions.cheque_date',
                'sales.party_id AS party',
                'sales.cash_sale',
                DB::raw('SUM(transactions.amount) AS amount')
            )
            ->get();
    }
    public function getVoucherListByParty($date1,$date2,$party)
    {
        return DB::table('transactions')
            ->join('sales', 'transactions.invoice_id', '=', 'sales.invoice_id')
            ->where('type', '=', 'Receive')
            ->whereNotNull('transactions.voucher_id')
            ->where('transactions.voucher_id', '!=', 0)
            ->where('sales.party_id', '=', $party)
            ->whereBetween('transactions.created_at', array(new \DateTime($date1), new \DateTime($date2)))
            ->groupBy('transactions.voucher_id')
            ->select('transactions.created_at AS date',
                'transactions.branch_id AS branch',
                'transactions.voucher_id AS voucher',
                'sales.party_id AS party',
                'sales.cash_sale',
                DB::raw('SUM(transactions.amount) AS total')
            )
            ->get();
    }
}
