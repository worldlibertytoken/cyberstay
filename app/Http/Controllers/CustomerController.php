<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q'));

        if ($request->user()?->isReceptionist()) {
            $customers = Customer::query()
                ->with(['bookings' => function ($query) {
                    $query->with('room')
                        ->latest('check_in');
                }])
                ->when($q !== '', function ($query) use ($q) {
                    $query->where(function ($inner) use ($q) {
                        $inner->where('name', 'like', "%{$q}%")
                            ->orWhere('phone', 'like', "%{$q}%")
                            ->orWhere('cnic', 'like', "%{$q}%");
                    });
                })
                ->when($q === '', function ($query) {
                    $query->whereRaw('1 = 0');
                })
                ->orderBy('name')
                ->paginate(10)
                ->withQueryString();

            if ($request->ajax()) {
                return response()->json([
                    'html' => view('customers.partials.receptionist-results', [
                        'customers' => $customers,
                        'q' => $q,
                    ])->render(),
                ]);
            }

            return view('customers.receptionist-index', compact('customers', 'q'));
        }

        $customers = Customer::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($inner) use ($q) {
                    $inner->where('name', 'like', "%{$q}%")
                        ->orWhere('phone', 'like', "%{$q}%")
                        ->orWhere('cnic', 'like', "%{$q}%");
                });
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('customers.index', compact('customers', 'q'));
    }

    public function create()
    {
        $this->ensureNotReceptionist();

        return view('customers.create');
    }

    public function store(Request $request)
    {
        $this->ensureNotReceptionist();

        Customer::create($this->validated($request));

        return redirect()->route('customers.index')->with('success', 'Customer saved.');
    }

    public function edit(Customer $customer)
    {
        $this->ensureNotReceptionist();

        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $this->ensureNotReceptionist();

        $customer->update($this->validated($request));

        return redirect()->route('customers.index')->with('success', 'Customer updated.');
    }

    public function destroy(Customer $customer)
    {
        $this->ensureNotReceptionist();

        if ($customer->bookings()->exists()) {
            return back()->with('error', 'This customer has bookings and cannot be deleted.');
        }

        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'Customer removed.');
    }

    public function search(Request $request)
    {
        $q = trim((string) $request->get('q'));

        if (mb_strlen($q) < 1) {
            return response()->json([]);
        }

        $customers = Customer::query()
            ->where(function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%")
                    ->orWhere('cnic', 'like', "%{$q}%");
            })
            ->orderBy('name')
            ->limit(8)
            ->get(['id', 'name', 'father_name', 'phone', 'cnic', 'address']);

        return response()->json($customers);
    }

    public function quickStore(Request $request)
    {
        $customer = Customer::create($this->validated($request));

        return response()->json($customer);
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'father_name' => ['nullable', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:30'],
            'cnic' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);
    }

    private function ensureNotReceptionist(): void
    {
        if (request()->user()?->isReceptionist()) {
            abort(403, 'Receptionists can only search customers and view stay history.');
        }
    }
}
