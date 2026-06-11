<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class AiAnalyzerController extends Controller
{
    public function index()
    {
        return view('ai-analyzer.index');
    }

    public function analyze(Request $request)
    {
        $q1 = $request->input('q1'); // After washing: a(tight/dry), b(normal), c(oily)
        $q2 = $request->input('q2'); // Pores: a(invisible), b(large), c(t-zone large)
        $q3 = $request->input('q3'); // Sensitivity: a(yes red/itchy), b(no)

        $scores = ['kering' => 0, 'berminyak' => 0, 'kombinasi' => 0, 'sensitif' => 0];

        // Q1
        if ($q1 === 'a') $scores['kering'] += 2;
        if ($q1 === 'b') $scores['kombinasi'] += 1;
        if ($q1 === 'c') $scores['berminyak'] += 2;

        // Q2
        if ($q2 === 'a') $scores['kering'] += 1;
        if ($q2 === 'b') $scores['berminyak'] += 1;
        if ($q2 === 'c') $scores['kombinasi'] += 2;

        // Q3 (High weight for sensitivity)
        if ($q3 === 'a') $scores['sensitif'] += 4;

        // Determine highest score
        $skinType = 'kombinasi'; // default
        $maxScore = -1;
        foreach ($scores as $type => $score) {
            if ($score > $maxScore) {
                $maxScore = $score;
                $skinType = $type;
            }
        }

        // Fetch top recommended products
        $recommendedProducts = Product::where('skin_type', $skinType)->inRandomOrder()->take(4)->get();

        return view('ai-analyzer.result', compact('skinType', 'recommendedProducts'));
    }
}
