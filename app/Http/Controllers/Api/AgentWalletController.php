<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use App\Models\WalletTransaction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class AgentWalletController extends Controller
{
    public function walletBalace()
    {

        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // return response()->json(['message' => 'hjfsdkljfsdkljf'], 200);

        try {
            $agent = Auth::user();

            $name = $agent->name;
            $balance = $agent->balance;

            $customers = Customer::where('created_by', $agent->id)->where('approved', 1)->orderBy('created_at', 'desc')->limit(5)->get();

            // Format customer data
            $formattedCustomers = $customers->map(function ($customer) {
                return [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'phone' => $customer->phone,
                    'address' => $customer->address,
                    'type' => $customer->type,
                    'image_url' => $customer->image ? asset('backend/images/customer/' . $customer->image) : null,
                    'created_by' => $customer->created_by ? optional($customer->addBY)->name : '',
                    'created_at' => $customer->created_at->toDateTimeString(),
                ];
            });

            $transactions = WalletTransaction::where('user_id', $agent->id)->orderBy('created_at', 'desc')->limit(5)->get();

            $formatedTransaction = $transactions->map(function ($transaction) {
                return [
                    'transaction_id' => $transaction->id,
                    'user_id' => $transaction->user_id,
                    'amount' => $transaction->amount,
                    'status' => $transaction->status == 1 ? 'Approved' : 'Pending',
                    'type' => $transaction->type == 1 ? 'Deposit' : 'Withdraw',
                    'created_at' => $transaction->created_at,
                ];
            });

            return response()->json([
                'name' => $name,
                'balance' => $balance,
                'recent_customer' => $formattedCustomers,
                'recent_transaction' => $formatedTransaction,
            ], 200);
        } catch (Exception $e) {

            return response()->json([
                'message' => 'An error occurred while retrieving wallet info.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function allTransaction()
    {

        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // return response()->json(['message' => 'hjfsdkljfsdkljf'], 200);

        try {
            $agent = Auth::user();

            $transactions = WalletTransaction::where('user_id', $agent->id)->orderBy('created_at', 'desc')->get();

            $formatedTransaction = $transactions->map(function ($transaction) {
                return [
                    'transaction_id' => $transaction->id,
                    'user_id' => $transaction->user_id,
                    'amount' => $transaction->amount,
                    'status' => $transaction->status == 1 ? 'Approved' : 'Pending',
                    'type' => $transaction->type == 1 ? 'Deposit' : 'Withdraw',
                    'created_at' => $transaction->created_at,
                ];
            });

            return response()->json([
                'recent_transaction' => $formatedTransaction,
            ], 200);
        } catch (Exception $e) {

            return response()->json([
                'message' => 'An error occurred while retrieving wallet info.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function withdrawRequest(Request $request)
    {
        $minimumWithdraw = App::make('minimumWithdraw');

        // return response()->json($minimumWithdraw);

        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        try {

            $agent = Auth::user();

            $previous_request = WalletTransaction::where('user_id', $agent->id)->where('status', 0)->first();

            if ($previous_request) {
                return response()->json(['message' => 'You have a pending request'], 422);
            }

            if ($agent->balance < $minimumWithdraw) {
                return response()->json(['message' => 'Insufficient balance'], 400);
            }

            $wallet = new WalletTransaction();
            $wallet->user_id = $agent->id;
            $wallet->amount = $request->amount;
            $wallet->status = 0;
            $wallet->type = 0;
            $wallet->save();

            return response()->json([
                'message' => 'Withdraw request submitted successfully',
            ], 200);
        } catch (Exception $e) {

            return response()->json([
                'message' => 'An error occurred while requesting.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function approveWithdraw($id)
    {
        try {

            if (!Auth::check() || Auth::user()->role != 1) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
            $wallet = WalletTransaction::findOrFail($id);

            if ($wallet->status == 1) {
                return response()->json(['message' => 'This withdraw request is already approved.'], 400);
            }

            $user = User::findOrFail($wallet->user_id);

            // Update wallet status
            $wallet->status = 1;
            $wallet->save();

            // Deduct balance
            $user->balance -= $wallet->amount;
            $user->save();

            return response()->json([
                'message' => 'Withdraw request approved',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
