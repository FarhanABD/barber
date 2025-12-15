<?php


namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Http\Request;

class TransactionsExport implements FromCollection
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = Transaction::query();

        if ($this->request->filled('date')) {
            $query->whereDate('created_at', $this->request->date);
        }

        if ($this->request->filled('start_date') && $this->request->filled('end_date')) {
            $query->whereBetween('created_at', [
                $this->request->start_date,
                $this->request->end_date
            ]);
        }

        return $query->get(); // ✅ JANGAN ->all()
    }
}