<!DOCTYPE html>
<html>

<head>

    <title>Social Media Intelligence</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: Inter, system-ui, sans-serif;
        }
    </style>

</head>


<body class="bg-slate-100">

<div class="max-w-7xl mx-auto px-8 py-10">

    <!-- ================= HEADER ================= -->

    <div class="mb-10">

        <h1 class="text-3xl font-bold text-slate-800">
            Social Media Intelligence
        </h1>

        <p class="text-slate-500 mt-1">
            Analisis komentar, pola narasi, dan koordinasi aktivitas
        </p>

    </div>



    <!-- ================= STATISTICS ================= -->

    <div class="grid lg:grid-cols-4 md:grid-cols-2 gap-6 mb-10">

        <!-- TOTAL COMMENTS -->

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">

            <div class="text-sm text-slate-500 mb-2">
                Total Comments
            </div>

            <div class="text-4xl font-bold text-slate-800">
                {{ count($comments) }}
            </div>

        </div>



        <!-- SIMILARITY -->

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">

            <div class="text-sm text-slate-500 mb-2">
                Similarity Detected
            </div>

            <div class="text-4xl font-bold text-red-500">
                {{ count($similar ?? []) }}
            </div>

        </div>



        <!-- CLUSTERS -->

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">

            <div class="text-sm text-slate-500 mb-2">
                Cluster Network
            </div>

            <div class="text-4xl font-bold text-purple-600">
                {{ count($clusters ?? []) }}
            </div>

        </div>



        <!-- BURST -->

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">

            <div class="text-sm text-slate-500 mb-2">
                Burst Attack
            </div>

            <div class="text-2xl font-semibold text-green-600">
                {{ $burst ?? "Normal" }}
            </div>

        </div>

    </div>



    <!-- ================= MAIN ANALYSIS GRID ================= -->

    <div class="grid lg:grid-cols-3 gap-8">


        <!-- ===== NARRATIVE TOPICS ===== -->

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">

            <h2 class="text-lg font-semibold text-slate-800 mb-5">
                Narrative Topics
            </h2>

            <div class="space-y-4">

                @foreach(($topics ?? []) as $topic)

                    <div class="border-b pb-3">

                        <div class="text-xs text-slate-400 mb-1">
                            Topic {{ $topic["topic_id"] }}
                        </div>

                        <div class="text-sm text-slate-700">
                            {{ implode(", ", $topic["keywords"]) }}
                        </div>

                    </div>

                @endforeach

            </div>

        </div>



        <!-- ===== TEMPLATE COMMENTS ===== -->

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">

            <h2 class="text-lg font-semibold text-slate-800 mb-5">
                Template Comments
            </h2>

            <div class="space-y-4 max-h-[420px] overflow-y-auto pr-2">

                @foreach(($similar ?? []) as $item)

                    <div class="border-b pb-3">

                        <div class="text-sm text-slate-700">
                            {{ $item["text"] }}
                        </div>

                        <div class="text-xs text-red-500 mt-1">
                            Similarity: {{ $item["similarity"] }}
                        </div>

                    </div>

                @endforeach

            </div>

        </div>



        <!-- ===== COORDINATED CLUSTERS ===== -->

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">

            <h2 class="text-lg font-semibold text-slate-800 mb-5">
                Coordinated Clusters
            </h2>

            <div class="space-y-4">

                @foreach(($clusters ?? []) as $cluster)

                    <div class="border-b pb-3">

                        <div class="text-xs text-slate-400 mb-1">
                            Cluster {{ $cluster["cluster"] }}
                        </div>

                        <div class="text-sm text-slate-700">
                            Members: {{ $cluster["size"] }}
                        </div>

                    </div>

                @endforeach

            </div>

        </div>

    </div>



    <!-- ================= COMMENT STREAM ================= -->

    <div class="mt-10 bg-white rounded-2xl shadow-sm border border-slate-200 p-6">

        <h2 class="text-lg font-semibold text-slate-800 mb-5">
            Comment Stream
        </h2>

        <div class="space-y-3 max-h-[420px] overflow-y-auto pr-2">

            @foreach(($comments ?? []) as $c)

                <div class="border-b pb-3 text-sm text-slate-700">

                    <span class="font-semibold text-slate-800">
                        {{ $c["user"] ?? "user" }}
                    </span>

                    <span class="text-slate-400">:</span>

                    {{ $c["text"] ?? "" }}

                </div>

            @endforeach

        </div>

    </div>


</div>

</body>
</html>