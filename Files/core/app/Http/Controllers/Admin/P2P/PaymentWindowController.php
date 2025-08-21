<?php

namespace App\Http\Controllers\Admin\P2P;

use App\Http\Controllers\Controller;
use App\Models\P2P\PaymentWindow;
use Illuminate\Http\Request;

class PaymentWindowController extends Controller
{
    public function index()
    {
        $pageTitle = 'P2P Payment Window';
        $windows   = PaymentWindow::orderBy('minute')->paginate(getPaginate());
        return view('admin.p2p.payment_window.index', compact('pageTitle', 'windows'));
    }

    public function save(Request $request, $id = 0)
    {
        $request->validate([
            'minute'                    => "required|integer|unique:p2p_payment_windows,minute,$id",
            'multiple_payment_window.'  => "nullable|array",
            'multiple_payment_window.*' => "required|integer|unique:p2p_payment_windows,minute,$id",
        ]);

        if ($id) {
            $paymentWindow = PaymentWindow::findOrFail($id);
            $message       = "P2P Payment Window updated successfully";
        } else {
            $paymentWindow = new PaymentWindow();
            $message       = "P2P Payment Window added successfully";
        }

        $paymentWindow->minute = $request->minute;
        $paymentWindow->save();
        
        if ($request->multiple_payment_window && !$id) {
            $multipleWindow = [];
            foreach ($request->multiple_payment_window as $window) {
                $multipleWindow[] = [
                    'minute' => $window
                ];
            }
            PaymentWindow::insert($multipleWindow);
        }
        
        return returnBack($message, "success");
    }

    public function status($id)
    {
        return PaymentWindow::changeStatus($id);
    }
}
