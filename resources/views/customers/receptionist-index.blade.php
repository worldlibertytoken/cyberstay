@extends('layouts.app')

@section('title', 'Customer Search')
@section('subtitle', 'Find by phone, name, or CNIC and review stay history')

@section('content')
    <div class="card p-5">
        <form id="receptionist-customer-search-form" method="GET" class="flex flex-col gap-3 sm:flex-row sm:items-end">
            <div class="flex-1">
                <label class="field-label"><x-icon name="search" size="sm" /> Search customer</label>
                <input id="receptionist-customer-search-input" class="input" name="q" value="{{ $q }}" placeholder="Type phone, name, or CNIC" autocomplete="off">
            </div>
            <button class="btn btn-primary" type="submit">
                <x-icon name="search" size="sm" /> Search
            </button>
        </form>
    </div>

    <div id="receptionist-customer-results">
        @include('customers.partials.receptionist-results', ['customers' => $customers, 'q' => $q])
    </div>

    <script>
        (() => {
            const form = document.getElementById('receptionist-customer-search-form');
            const input = document.getElementById('receptionist-customer-search-input');
            const results = document.getElementById('receptionist-customer-results');

            if (!form || !input || !results) {
                return;
            }

            let debounceTimer = null;

            const fetchResults = () => {
                const query = input.value.trim();
                const url = new URL(form.action || window.location.href);

                if (query !== '') {
                    url.searchParams.set('q', query);
                } else {
                    url.searchParams.delete('q');
                }

                fetch(url.toString(), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                })
                    .then((response) => response.json())
                    .then((data) => {
                        results.innerHTML = data.html ?? '';
                        window.history.replaceState({}, '', url.toString());
                    })
                    .catch(() => {
                        // Keep current results if request fails.
                    });
            };

            input.addEventListener('input', () => {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(fetchResults, 250);
            });

            form.addEventListener('submit', (event) => {
                event.preventDefault();
                clearTimeout(debounceTimer);
                fetchResults();
            });
        })();
    </script>
@endsection
