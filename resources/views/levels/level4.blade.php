@if($userLevel == $level)

<head>
    <meta charset="utf-8">
    <title>Error 4</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="antialiased">
    <div class="relative flex items-center justify-center min-h-screen bg-gray-100 dark:bg-gray-900">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col items-center pt-8 sm:pt-0">

                <!-- Big Error Code with vertical separator and text side by side -->
                <div class="flex items-center gap-6">
                    <div class="px-6 text-7xl text-gray-500 border-r border-gray-400 tracking-wider">
                        Error 002
                    </div>
                    
                    <!-- Two lines stacked vertically -->
                    <div class="text-gray-500 tracking-wider uppercase text-lg whitespace-nowrap">
                        <div class="mb-2 font-semibold">Type: user not recognized</div>
                        <div class="font-semibold">Severity: not fol <span class="text-xs font-light opacity-40">(low error)</span>  </div>
                    </div>
                </div>

                <!-- Initiator info -->
                <div class="mt-8 text-gray-400 tracking-wider uppercase text-lg">
                    Initiator: <span class="font-semibold">@veravart_game</span>
                </div>

            </div>
        </div>
    </div>



</body>

@endif
 