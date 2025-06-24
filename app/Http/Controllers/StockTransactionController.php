<?php



namespace App\Http\Controllers;


use App\Services\StockTransactionService;
use Illuminate\Http\Request;

class StockTransactionController extends Controller
{
    protected $service;

    public function __construct(StockTransactionService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return response()->json($this->service->getAllTransactions());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'type'       => 'required|in:in,out',
            'quantity'   => 'required|integer|min:1',
            'date'       => 'required|date',
            'status'     => 'nullable|string',
            'notes'      => 'nullable|string',
        ]);

        return response()->json($this->service->createTransaction($validated, auth()->id()), 201);
    }

    public function show($id)
    {
        return response()->json($this->service->getTransactionById($id));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'type'       => 'required|in:in,out',
            'quantity'   => 'required|integer|min:1',
            'date'       => 'required|date',
            'status'     => 'nullable|string',
            'notes'      => 'nullable|string',
        ]);

        return response()->json($this->service->updateTransaction($id, $validated));
    }

    public function destroy($id)
    {
        $this->service->deleteTransaction($id);
        return response()->json(['message' => 'Transaction deleted']);
    }

    public function confirm($id)
    {
        return response()->json($this->service->confirmTransaction($id));
    }

    public function report(Request $request)
    {
        $validated = $request->validate([
            'from' => 'required|date',
            'to'   => 'required|date|after_or_equal:from',
        ]);

        return response()->json($this->service->getReport($validated['from'], $validated['to']));
    }

    public function dashboardSummary()
    {
        return response()->json($this->service->getDashboardSummary());
    }
}
