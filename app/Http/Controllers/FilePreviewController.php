<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser;

class FilePreviewController extends Controller
{
    public function preview($exerciseId)
    {
        $exercise = Exercise::findOrFail($exerciseId);
        
        if (!$exercise->file_path || !Storage::disk('public')->exists($exercise->file_path)) {
            abort(404, 'PDF tidak ditemukan');
        }

        $path = Storage::disk('public')->path($exercise->file_path);
        
        try {
            // Parse PDF
            $parser = new Parser();
            $pdf = $parser->parseFile($path);
            
            // Extract text from all pages
            $text = $pdf->getText();
            
            // Basic text formatting
            $paragraphs = explode("\n\n", $text);
            $formattedText = array_map(function($paragraph) {
                return trim($paragraph);
            }, $paragraphs);
            
            // Remove empty paragraphs
            $formattedText = array_filter($formattedText);
            
            return view('pdf.preview', [
                'title' => $exercise->title,
                'content' => $formattedText,
                'exerciseId' => $exerciseId
            ]);
            
        } catch (\Exception $e) {
            return response()->view('errors.pdf-error', [
                'message' => 'Gagal mengekstrak teks dari PDF: ' . $e->getMessage()
            ], 500);
        }
    }
} 