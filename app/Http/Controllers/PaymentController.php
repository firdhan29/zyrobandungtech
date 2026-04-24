<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Project;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'payment_type' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $payment = Payment::create([
            'project_id' => $request->project_id,
            'payment_type' => $request->payment_type,
            'amount' => $request->amount,
            'payment_date' => $request->payment_date,
            'notes' => $request->notes,
            'status' => 'confirmed', // Auto confirm for now
        ]);

        // Update project remaining amount
        $project = $payment->project;
        $project->remaining_amount -= $payment->amount;
        if ($project->remaining_amount <= 0) {
            $project->remaining_amount = 0;
            // Optionally update status to completed if progress is 100%? 
            // Let's just update the amount for now.
        }
        $project->save();

        return redirect()->back()->with('success', 'Pembayaran berhasil ditambahkan!');
    }

    public function destroy(Payment $payment)
    {
        $project = $payment->project;
        $project->remaining_amount += $payment->amount;
        $project->save();

        $payment->delete();
        return redirect()->back()->with('success', 'Pembayaran dihapus!');
    }
}
