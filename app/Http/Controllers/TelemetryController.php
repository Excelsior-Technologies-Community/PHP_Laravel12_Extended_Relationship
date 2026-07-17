<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Manager;
use Illuminate\Support\Facades\DB;

class TelemetryController extends Controller
{
    public function index()
    {
        // ── EAGER LOAD TEST ──────────────────────────────────────────────
        DB::flushQueryLog();
        DB::enableQueryLog();
        $memBefore = memory_get_usage();

        $eagerProducts = Product::with(['managers', 'tags'])->get();
        // Access relationships to force resolution
        foreach ($eagerProducts as $p) {
            $_ = $p->managers->creator;
            $_ = $p->tags->count();
        }

        $eagerQueries   = DB::getQueryLog();
        $eagerQueryCount = count($eagerQueries);
        $eagerMemory    = memory_get_usage() - $memBefore;

        // ── LAZY LOAD TEST (N+1 simulation) ─────────────────────────────
        DB::flushQueryLog();
        $memBefore = memory_get_usage();

        $lazyProducts = Product::all();
        foreach ($lazyProducts as $p) {
            $_ = $p->managers->creator;   // triggers N queries
            $_ = $p->tags->count();       // triggers N more queries
        }

        $lazyQueries    = DB::getQueryLog();
        $lazyQueryCount  = count($lazyQueries);
        $lazyMemory     = memory_get_usage() - $memBefore;
        DB::disableQueryLog();

        // ── MANAGER LAYER ────────────────────────────────────────────────
        DB::enableQueryLog();
        DB::flushQueryLog();
        $managers = Manager::with(['auditedProducts', 'tags'])->get();
        $managerQueries = count(DB::getQueryLog());
        DB::disableQueryLog();

        $productCount = $eagerProducts->count();
        $n1Expected   = ($productCount * 2) + 1; // 1 base + N*2 relations

        $metrics = [
            'product_count'      => $productCount,
            'eager_query_count'  => $eagerQueryCount,
            'lazy_query_count'   => $lazyQueryCount,
            'n1_expected'        => $n1Expected,
            'n1_detected'        => $lazyQueryCount >= $n1Expected,
            'query_saved'        => max(0, $lazyQueryCount - $eagerQueryCount),
            'eager_memory_kb'    => round($eagerMemory / 1024, 2),
            'lazy_memory_kb'     => round($lazyMemory / 1024, 2),
            'memory_diff_kb'     => round(abs($lazyMemory - $eagerMemory) / 1024, 2),
            'manager_count'      => $managers->count(),
            'manager_queries'    => $managerQueries,
            'eager_queries_log'  => $eagerQueries,
            'lazy_queries_log'   => $lazyQueries,
        ];

        return view('telemetry.index', compact('metrics'));
    }
}
