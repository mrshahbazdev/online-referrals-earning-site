<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = $user->transactions();

        // Filter types define karein
        $types = ['investment', 'withdrawal', 'commission', 'task_reward'];
        $selectedType = $request->query('type');

        // Agar koi valid type select hua hai, to query ko filter karein
        if (in_array($selectedType, $types)) {
            $query->where('type', $selectedType);
        }

        $transactions = $query->latest()->paginate(15);

        return view('transactions.index', compact('transactions', 'types', 'selectedType'));
    }
}
