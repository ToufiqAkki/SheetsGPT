<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\IOFactory;

class DocumentParserService
{
    /**
     * Parses an uploaded spreadsheet file into a text representation for LLMs.
     * Extracts data into a CSV-like text format.
     */
    public function parse(string $absolutePath): string
    {
        try {
            $spreadsheet = IOFactory::load($absolutePath);
            $worksheet = $spreadsheet->getActiveSheet();
            
            $data = [];
            foreach ($worksheet->getRowIterator() as $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false); 
                
                $rowData = [];
                foreach ($cellIterator as $cell) {
                    $val = $cell->getCalculatedValue() ?? $cell->getValue();
                    $rowData[] = str_replace(["\n", "\r"], " ", (string)$val);
                }
                
                // only add row if it has some non-empty value
                if (count(array_filter($rowData, fn($v) => trim($v) !== '')) > 0) {
                    $data[] = implode(" | ", $rowData);
                }
            }
            
            return implode("\n", $data);
            
        } catch (\Exception $e) {
            \Log::error("Failed to parse document at {$absolutePath}: " . $e->getMessage());
            throw new \Exception("Could not parse file: " . $e->getMessage());
        }
    }
}
