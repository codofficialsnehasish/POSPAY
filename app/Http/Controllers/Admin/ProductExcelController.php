<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Product;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use App\Imports\ProductsImport;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ProductExcelController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Product Bulk Upload', only: ['showUploadForm','downloadTemplate','importProducts'])
        ];
    }

    // Show upload form
    public function showUploadForm()
    {
        return view('admin.bulk-upload.bulk-upload');
    }

    /*public function downloadTemplate()
    {
        $fileName = 'product_template.xlsx';
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ];

        return response()->streamDownload(function () {

          	// Clean any existing buffers
        	if (ob_get_length()) ob_end_clean();
          
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Products');

            // Header row
            $sheet->setCellValue('A1', 'Name');
            $sheet->setCellValue('B1', 'HSN Code');
            $sheet->setCellValue('C1', 'Category');
            $sheet->setCellValue('D1', 'Brand');
            $sheet->setCellValue('E1', 'Brand Owner');
            $sheet->setCellValue('F1', 'Sort Description');
            $sheet->setCellValue('G1', 'Product Availability'); // 1/0
            $sheet->setCellValue('H1', 'GST Included'); // 1/0
            $sheet->setCellValue('I1', 'Visibility'); // 1/0

            // Variation columns
            $sheet->setCellValue('K1', 'Measure'); // dropdown from units
            $sheet->setCellValue('L1', 'Unit');
            $sheet->setCellValue('M1', 'Barcode');
            $sheet->setCellValue('N1', 'Quantity');
            $sheet->setCellValue('O1', 'MRP');
            $sheet->setCellValue('P1', 'No Discount'); // 1/0
            $sheet->setCellValue('Q1', 'Discount Rate');
            $sheet->setCellValue('R1', 'Discount Amount');
            $sheet->setCellValue('S1', 'Price');

            // Hidden sheet for dropdowns
            $dropdownSheet = $spreadsheet->createSheet();
            $dropdownSheet->setTitle('Dropdowns');

            $hsnCodes = \App\Models\HsnCode::pluck('hsncode')->where('vendor_id',auth()->user()->id)->toArray();
            $categories = \App\Models\Category::pluck('name')->where('vendor_id',auth()->user()->id)->toArray();
            $brands = \App\Models\Brand::pluck('name')->where('vendor_id',auth()->user()->id)->toArray();
            $units = \App\Models\Unit::pluck('short_name')->toArray();

            // Fill hidden sheet
            foreach ($hsnCodes as $i => $val) {
                $dropdownSheet->setCellValue('A' . ($i + 1), $val);
            }
            foreach ($categories as $i => $val) {
                $dropdownSheet->setCellValue('B' . ($i + 1), $val);
            }
            foreach ($brands as $i => $val) {
                $dropdownSheet->setCellValue('C' . ($i + 1), $val);
            }
            foreach ($units as $i => $val) {
                $dropdownSheet->setCellValue('D' . ($i + 1), $val);
            }

            // Apply dropdowns for rows 2–1000
            for ($row = 2; $row <= 1000; $row++) {

                // HSN Code
                $validation = $sheet->getCell('B' . $row)->getDataValidation();
                $validation->setType(DataValidation::TYPE_LIST);
                $validation->setErrorStyle(DataValidation::STYLE_STOP);
                $validation->setAllowBlank(true);
                $validation->setShowInputMessage(true);
                $validation->setShowErrorMessage(true);
                $validation->setShowDropDown(true);
                $validation->setFormula1('Dropdowns!$A$1:$A$' . count($hsnCodes));

                // Category
                $validation = $sheet->getCell('C' . $row)->getDataValidation();
                $validation->setType(DataValidation::TYPE_LIST);
                $validation->setErrorStyle(DataValidation::STYLE_STOP);
                $validation->setAllowBlank(true);
                $validation->setShowInputMessage(true);
                $validation->setShowErrorMessage(true);
                $validation->setShowDropDown(true);
                $validation->setFormula1('Dropdowns!$B$1:$B$' . count($categories));

                // Brand
                $validation = $sheet->getCell('D' . $row)->getDataValidation();
                $validation->setType(DataValidation::TYPE_LIST);
                $validation->setErrorStyle(DataValidation::STYLE_STOP);
                $validation->setAllowBlank(true);
                $validation->setShowInputMessage(true);
                $validation->setShowErrorMessage(true);
                $validation->setShowDropDown(true);
                $validation->setFormula1('Dropdowns!$C$1:$C$' . count($brands));

                // Product Availability
                $validation = $sheet->getCell('G' . $row)->getDataValidation();
                $validation->setType(DataValidation::TYPE_LIST);
                $validation->setErrorStyle(DataValidation::STYLE_STOP);
                $validation->setAllowBlank(true);
                $validation->setShowInputMessage(true);
                $validation->setShowErrorMessage(true);
                $validation->setShowDropDown(true);
                $validation->setFormula1('"Available,Not Available"'); // descriptive dropdown

                // GST Included
                $validation = $sheet->getCell('H' . $row)->getDataValidation();
                $validation->setType(DataValidation::TYPE_LIST);
                $validation->setErrorStyle(DataValidation::STYLE_STOP);
                $validation->setAllowBlank(true);
                $validation->setShowInputMessage(true);
                $validation->setShowErrorMessage(true);
                $validation->setShowDropDown(true);
                $validation->setFormula1('"Included,Excluded"'); // descriptive dropdown

                // Visibility
                $validation = $sheet->getCell('I' . $row)->getDataValidation();
                $validation->setType(DataValidation::TYPE_LIST);
                $validation->setErrorStyle(DataValidation::STYLE_STOP);
                $validation->setAllowBlank(true);
                $validation->setShowInputMessage(true);
                $validation->setShowErrorMessage(true);
                $validation->setShowDropDown(true);
                $validation->setFormula1('"Show,Hide"'); // descriptive dropdown

                // Measure
                $validation = $sheet->getCell('K' . $row)->getDataValidation();
                $validation->setType(DataValidation::TYPE_LIST);
                $validation->setFormula1('Dropdowns!$D$1:$D$' . count($units));
                $validation->setShowDropDown(true);

                // No Discount
                $validation = $sheet->getCell('P' . $row)->getDataValidation();
                $validation->setType(DataValidation::TYPE_LIST);
                $validation->setFormula1('"Yes,No"'); // map to 1/0 on import
                $validation->setShowDropDown(true);
            }

            // Hide dropdown sheet
            $dropdownSheet->setSheetState(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::SHEETSTATE_HIDDEN);

            // Auto-size columns
            foreach (range('A', 'S') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save('php://output');
          exit;

        }, $fileName, $headers);
    }*/
  
    public function downloadTemplate()
    {
        // Create spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Products');

        // Header row
        $sheet->setCellValue('A1', 'Name');
        $sheet->setCellValue('B1', 'HSN Code');
        $sheet->setCellValue('C1', 'Category');
        $sheet->setCellValue('D1', 'Brand');
        $sheet->setCellValue('E1', 'Brand Owner');
        $sheet->setCellValue('F1', 'Sort Description');
        $sheet->setCellValue('G1', 'Product Availability'); // 1/0
        $sheet->setCellValue('H1', 'GST Included'); // 1/0
        $sheet->setCellValue('I1', 'Visibility'); // 1/0

        // Variation columns
        $sheet->setCellValue('K1', 'Measure'); // dropdown from units
        $sheet->setCellValue('L1', 'Unit');
        $sheet->setCellValue('M1', 'Barcode');
        $sheet->setCellValue('N1', 'Quantity');
        $sheet->setCellValue('O1', 'MRP');
        $sheet->setCellValue('P1', 'No Discount'); // 1/0
        $sheet->setCellValue('Q1', 'Discount Rate');
        $sheet->setCellValue('R1', 'Discount Amount');
        $sheet->setCellValue('S1', 'Price');

        // Hidden sheet for dropdowns
        $dropdownSheet = $spreadsheet->createSheet();
        $dropdownSheet->setTitle('Dropdowns');

        // Fetch dropdown data (make sure you filter BEFORE pluck)
        $hsnCodes = \App\Models\Hsncode::where('vendor_id', auth()->user()->id)->pluck('hsncode')->toArray();
        $categories = \App\Models\Category::where('vendor_id', auth()->user()->id)->pluck('name')->toArray();
        $brands = \App\Models\Brand::where('vendor_id', auth()->user()->id)->pluck('name')->toArray();
        $units = \App\Models\Unit::pluck('short_name')->toArray();

        // Fill hidden sheet
        foreach ($hsnCodes as $i => $val) {
            $dropdownSheet->setCellValue('A' . ($i + 1), $val);
        }
        foreach ($categories as $i => $val) {
            $dropdownSheet->setCellValue('B' . ($i + 1), $val);
        }
        foreach ($brands as $i => $val) {
            $dropdownSheet->setCellValue('C' . ($i + 1), $val);
        }
        foreach ($units as $i => $val) {
            $dropdownSheet->setCellValue('D' . ($i + 1), $val);
        }

        // Apply dropdowns for rows 2–1000
        for ($row = 2; $row <= 1000; $row++) {
            // HSN Code
            $validation = $sheet->getCell('B' . $row)->getDataValidation();
            $validation->setType(DataValidation::TYPE_LIST);
            $validation->setErrorStyle(DataValidation::STYLE_STOP);
            $validation->setAllowBlank(true);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setShowDropDown(true);
            $validation->setFormula1('Dropdowns!$A$1:$A$' . count($hsnCodes));

            // Category
            $validation = $sheet->getCell('C' . $row)->getDataValidation();
            $validation->setType(DataValidation::TYPE_LIST);
            $validation->setErrorStyle(DataValidation::STYLE_STOP);
            $validation->setAllowBlank(true);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setShowDropDown(true);
            $validation->setFormula1('Dropdowns!$B$1:$B$' . count($categories));

            // Brand
            $validation = $sheet->getCell('D' . $row)->getDataValidation();
            $validation->setType(DataValidation::TYPE_LIST);
            $validation->setErrorStyle(DataValidation::STYLE_STOP);
            $validation->setAllowBlank(true);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setShowDropDown(true);
            $validation->setFormula1('Dropdowns!$C$1:$C$' . count($brands));

            // Product Availability
            $validation = $sheet->getCell('G' . $row)->getDataValidation();
            $validation->setType(DataValidation::TYPE_LIST);
            $validation->setErrorStyle(DataValidation::STYLE_STOP);
            $validation->setAllowBlank(true);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setShowDropDown(true);
            $validation->setFormula1('"Available,Not Available"');

            // GST Included
            $validation = $sheet->getCell('H' . $row)->getDataValidation();
            $validation->setType(DataValidation::TYPE_LIST);
            $validation->setErrorStyle(DataValidation::STYLE_STOP);
            $validation->setAllowBlank(true);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setShowDropDown(true);
            $validation->setFormula1('"Included,Excluded"');

            // Visibility
            $validation = $sheet->getCell('I' . $row)->getDataValidation();
            $validation->setType(DataValidation::TYPE_LIST);
            $validation->setErrorStyle(DataValidation::STYLE_STOP);
            $validation->setAllowBlank(true);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setShowDropDown(true);
            $validation->setFormula1('"Show,Hide"');

            // Measure
            $validation = $sheet->getCell('K' . $row)->getDataValidation();
            $validation->setType(DataValidation::TYPE_LIST);
            $validation->setFormula1('Dropdowns!$D$1:$D$' . count($units));
            $validation->setShowDropDown(true);

            // No Discount
            $validation = $sheet->getCell('P' . $row)->getDataValidation();
            $validation->setType(DataValidation::TYPE_LIST);
            $validation->setFormula1('"Yes,No"');
            $validation->setShowDropDown(true);
        }

        // Hide dropdown sheet
        $dropdownSheet->setSheetState(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::SHEETSTATE_HIDDEN);

        // Auto-size columns
        foreach (range('A', 'S') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Save to temp file
        $fileName = 'product_template.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);
        $writer = new Xlsx($spreadsheet);
        $writer->save($temp_file);

        // Return as download and delete temp file after sending
        return response()->download($temp_file, $fileName)->deleteFileAfterSend(true);
    }



    // Handle Excel upload
    public function importProducts(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        $import = new ProductsImport;
        Excel::import($import, $request->file('file'));

        if ($import->getImportedCount() === 0) {
            return back()->with('error', 'No valid products found to import.');
        }

        return back()->with('success', 'Products imported successfully.');
    }

}
