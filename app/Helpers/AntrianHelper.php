<?php

use App\Models\Transaction;

function generateNoAntrian()
{
    return Transaction::whereDate('created_at', now())
        ->max('no_antrian') + 1;
}