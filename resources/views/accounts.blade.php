<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

        <div class="flex items-center">
                            <a class="buton" href="{{ route('google.store') }}">
                                Add account
                            </a>
                        </div>
        <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                @forelse ($accounts as $account)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>{{ $account->name }}</span>
                                <form action="{{ route('google.destroy', $account) }}" method="post">
                                    {{ csrf_field() }}
                                    {{ method_field('delete') }}

                                    <button type="submit" class="btn btn-outline-secondary btn-sm">
                                        delete
                                    </button>
                                </form>
                            </li>
                        @empty
                            <li class="list-group-item">
                                No google accounts.
                            </li>
                        @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
