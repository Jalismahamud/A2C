<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;

class AgentWalletController extends Controller
{
    public function pendingList()
    {
        $withdraw_requests = WalletTransaction::where('status', 0)->paginate(10);
        return view('backend.withdraw.index', compact('withdraw_requests'));
    }

    public function approveList()
    {
        $withdraw_requests = WalletTransaction::where('status', 1)->paginate(10);
        return view('backend.withdraw.index', compact('withdraw_requests'));
    }

    public function approveWithdraw($id)
    {
        $withdrawRequest = WalletTransaction::find($id);

        if (!$withdrawRequest) {
            return redirect()->back()->with('error', 'Withdrawal request not found.');
        }


        $withdrawRequest->status = $withdrawRequest->status == 0 ? 1 : 0;
        $withdrawRequest->save();


        $statusText = $withdrawRequest->status == 1 ? 'approved' : 'reverted';
        return redirect()->back()->with('success', "Withdrawal has been {$statusText}.");
    }
}
