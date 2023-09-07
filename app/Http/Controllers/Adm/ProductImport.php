<?php

namespace App\Http\Controllers\Adm;

use finfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\SimpleExcel\SimpleExcelReader;

use App\Http\Libraries\Response;
use App\Http\Controllers\Controller;
use App\Models\Products;
use stdClass;

class ProductImport extends Controller {

    private static Request $request;
    private static array $data;

    public function sendCsv() {
        return view('adm.productImportLoadCsv');
    }

    public function loadCsv(Request $request) {

        $csvFileName = 'product_'.date('Y-m-d_H-i-s').'.csv';
        $csvFile = $request->file()['file']->storeAs('imports/products',$csvFileName);
        $csvHeaders = SimpleExcelReader::create($request->file()['file'],'csv')->useDelimiter($request->delimiter)->getHeaders();
        $csvHeaders = array_combine($csvHeaders, $csvHeaders);
        $delimiter = $request->delimiter ?? ",";

        return view('adm.productImportProcessCsv', compact('csvHeaders','csvFile','delimiter'));

    }

    public function processCsv(Request $request) {

        self::$request = $request;

        session(['importFields' => $request->toArray()]);

        if(!$csvFile = Storage::path($request->csv_file)){
            echo (new Response())->error('Falha ao carregar o arquivo')->json();
            return;
        }

        $csvFile = SimpleExcelReader::create($csvFile)->useDelimiter($request->delimiter)->getRows();
        $csvFile->each(function(array $product) {

            $data = new stdClass();
            $data->isbn         = $product[self::$request->isbn];
            $data->title        = $product[self::$request->title];
            $data->publisher    = $product[self::$request->publisher];
            $data->category     = $product[self::$request->category];
            $data->height       = $product[self::$request->height];
            $data->width        = $product[self::$request->width];
            $data->length       = $product[self::$request->length];
            $data->weight       = $product[self::$request->weight];
            $data->synopsis     = $product[self::$request->synopsis];
            $data               = $this->normalize($data);
            $data->cover        = (
                empty(self::$request['download_cover']) ?
                    $product[self::$request->cover] :
                    $this->getImage($data->isbn,$product[self::$request->cover])
            );

            if(empty($data->isbn) || !Products::updateOrCreate(['isbn'=>$data->isbn],(array)$data)->save()){
                $data->status = "Falhou";
            } else {
                $data->status = "Sucesso";
            }

            $data = [
                'isbn'      => $data->isbn,
                'title'     => $data->title,
                'publisher' => $data->publisher,
                'height'    => $data->height,
                'width'     => $data->width,
                'length'    => $data->length,
                'weight'    => $data->weight,
                'status'    => $data->status
            ];

            self::$data['data'][] = $data;
            if(empty(self::$data['columns'])){
                foreach ($data as $field => $value){
                    self::$data['columns'][] = array('data' => $field);
                }
            }

        });

        echo (new Response())->success('Importação Terminada')
            ->action('drawDataTable','myTable')
            ->data(self::$data)
            ->json();

    }

    /**
     * FAZ DOWNLOAD DA CAPA (PRIVATE)
     * @param string $isbn
     * @param string|null $url
     * @return string|null
     */
    private function getImage(string $isbn, string $url = null):? string {
        if(empty($url) || !filter_var($url, FILTER_VALIDATE_URL)){
            return null;
        }

        $contents = file_get_contents($url);
        $finfo = new finfo(FILEINFO_EXTENSION);
        $extensions = explode('/',$finfo->buffer($contents));
        $name = "products/{$isbn}.{$extensions[0]}";

        if(Storage::disk('public')->put($name,$contents)){
            return $name;
        }

        return null;

    }

    /**
     * RETORNA DADOS NORMALIZADOS (PRIVATE)
     * @param object $data
     * @return object
     */
    private function normalize(object $data) {

        /* Normalize */
        $data->isbn         = preg_replace("/[^0-9]/", "", $data->isbn);
        $data->title        = filter_var($data->title, FILTER_SANITIZE_STRIPPED);
        $data->publisher    = filter_var($data->publisher, FILTER_SANITIZE_STRIPPED);
        $data->category     = filter_var($data->category, FILTER_SANITIZE_STRIPPED);
        $data->height       = toIntOrFloat($data->height);
        $data->width        = toIntOrFloat($data->width);
        $data->length       = toIntOrFloat($data->length);
        $data->weight       = toIntOrFloat($data->weight);
        $data->synopsis     = filter_var($data->synopsis, FILTER_SANITIZE_STRIPPED);

        /* Max Length */
        $data->category     = mb_strimwidth($data->category, 0, 250, "...");

        /* Convert Dimensions */
        $data->height       = (int)(is_int($data->height)?$data->height:$data->height * 10);
        $data->width        = (int)(is_int($data->width)?$data->width:$data->width * 10);
        $data->length       = (int)(is_int($data->length)?$data->length:$data->length * 10);
        $data->weight       = (int)$data->weight;

        return $data;
    }

}
