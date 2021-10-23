<?php
/** @noinspection PhpUnhandledExceptionInspection */

/** @noinspection PhpMissingReturnTypeInspection */

namespace App\Http\Controllers;

use App\Models\Bill;
use Illuminate\Http\Request;

class BillController extends Controller
{
    public function find(Request $request, Bill $bill): Bill
    {
        $this->authorize('view', $bill);

        return $bill;
    }
}
