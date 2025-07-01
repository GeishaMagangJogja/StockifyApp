@if(session('success'))
    <div class="p-4 mb-6 text-green-700 bg-green-100 rounded-lg dark:bg-green-900/50 dark:text-green-300">
        <div class="flex items-center">
            <i class="mr-2 fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    </div>
@endif

@if(session('error'))
    <div class="p-4 mb-6 text-red-700 bg-red-100 rounded-lg dark:bg-red-900/50 dark:text-red-300">
        <div class="flex items-center">
            <i class="mr-2 fas fa-exclamation-circle"></i>
            {{ session('error') }}
        </div>
    </div>
@endif

@if($errors->any())
    <div class="p-4 mb-6 text-red-700 bg-red-100 rounded-lg dark:bg-red-900/50 dark:text-red-300">
        <div class="flex items-center">
            <i class="mr-2 fas fa-exclamation-circle"></i>
            <ul class="ml-2 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif
