<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Loan;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function index($customerId, Request $request)
    {
        $customer = Customer::findOrFail($customerId);
        $loans = Loan::where('customer_id', $customerId)->get();
        $editLoanId = $request->query('editLoanId');
        $totalLoanAmount = $loans->sum('amount');

        return view('loans.index', compact('loans', 'customer', 'editLoanId', 'totalLoanAmount'));
    }


public function store(Request $request, $customerId)
{
    // Validate the loan amount
    $request->validate([
        'amount' => 'required|numeric|min:0',
    ]);

    $loanAmount = $request->amount;
    $customer = Customer::findOrFail($customerId);

    // Update the customer's loan balance (add the new loan amount)
    $customer->loan += $loanAmount;  // Add the loan amount to the current balance
    $customer->save();

    // Create the loan record
    $loan = new Loan();
    $loan->customer_id = $customerId;
    $loan->amount = $loanAmount;
    $loan->save();

    return redirect()->route('loans.index', ['customerId' => $customerId])
        ->with('success', 'Loan added successfully.');
}

public function update(Request $request, $id)
{
    $loan = Loan::findOrFail($id);

    // Validate the new amount
    $request->validate([
        'amount' => 'required|numeric|min:0',
    ]);

    // Update the loan amount
    $loan->amount = $request->input('amount');
    $loan->save();

    // Redirect back with a success message
    return redirect()->route('loans.index', ['customerId' => $loan->customer_id])->with('success', 'Loan amount updated successfully.');
}
public function destroy($id)
    {
        // Find the loan to be deleted
        $loan = Loan::findOrFail($id);
        $customer = $loan->customer;

        // Subtract the loan amount from the customer's total loan balance
        $customer->loan -= $loan->amount;
        $customer->save();

        // Delete the loan record
        $loan->delete();

        return redirect()->route('loans.index', ['customerId' => $customer->id])
            ->with('success', 'Loan deleted successfully.');
    }

}
