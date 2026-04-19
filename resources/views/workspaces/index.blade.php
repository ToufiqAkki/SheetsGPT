<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center whitespace-nowrap">
            <h2 class="font-semibold text-xl leading-tight">
                {{ __('Workspaces') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="bg-neutral-900 overflow-hidden shadow-sm sm:rounded-lg p-6 border border-neutral-800">
                <h3 class="text-lg font-medium mb-4">Create New Workspace</h3>
                <form action="{{ route('workspaces.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm text-neutral-400">Name</label>
                        <input type="text" name="name" required class="mt-1 block w-full bg-neutral-950 border border-neutral-800 rounded-md shadow-sm text-neutral-200 focus:ring-neutral-700 focus:border-neutral-700">
                    </div>
                    <div>
                        <label class="block text-sm text-neutral-400">Description (optional)</label>
                        <textarea name="description" class="mt-1 block w-full bg-neutral-950 border border-neutral-800 rounded-md shadow-sm text-neutral-200 focus:ring-neutral-700 focus:border-neutral-700"></textarea>
                    </div>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-neutral-200 border border-transparent rounded-md font-semibold text-xs text-neutral-900 uppercase tracking-widest hover:bg-white focus:bg-white active:bg-neutral-300 focus:outline-none focus:ring-2 focus:ring-neutral-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Create Workspace
                    </button>
                </form>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($workspaces as $workspace)
                    <div class="bg-neutral-900 p-6 rounded-lg border border-neutral-800 relative hover:border-neutral-700 transition">
                        <h4 class="text-xl font-semibold mb-2"><a href="{{ route('workspaces.show', $workspace) }}" class="absolute inset-0"></a>{{ $workspace->name }}</h4>
                        <p class="text-neutral-400 text-sm mb-4">{{ $workspace->description ?? 'No description' }}</p>
                        
                        <div class="flex items-center justify-between text-xs text-neutral-500 relative z-10">
                            <span>{{ $workspace->documents()->count() }} Documents</span>
                            
                            <form action="{{ route('workspaces.destroy', $workspace) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-400" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </div>
                    </div>
                @endforeach
                
                @if($workspaces->isEmpty())
                    <div class="col-span-1 md:col-span-2 text-center text-neutral-500 py-8">
                        No workspaces found. Create one above to get started.
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
