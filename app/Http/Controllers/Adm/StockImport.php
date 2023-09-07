<?php

namespace App\Http\Controllers\Adm;

use App\Http\Controllers\Controller;
use App\Http\Libraries\Response;
use App\Models\Addressings;
use App\Models\Offices;
use App\Models\Partners;
use App\Models\Products;
use App\Models\Stocks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\SimpleExcel\SimpleExcelReader;
use stdClass;

class StockImport extends Controller {

    private static Request $request;
    private static array $data;

    public function sendCsv() {
        return view('adm.stockImportLoadCsv');
    }

    public function loadCsv(Request $request) {

        $partner = Partners::find($request->partner_id);
        $offices = Offices::get(['id','name'])->toArray();
        $typeStock = Stocks::TYPES;
        $delimiter = $request->delimiter ?? ",";
        $csvHeaders = null;
        $csvFile = null;

        if($partner){
            $csvFileName = "{$partner->document01}_".date('Y-m-d_H-i-s').'.csv';
            $csvFile = $request->file()['file']->storeAs('imports/stocks',$csvFileName);
            $csvHeaders = SimpleExcelReader::create($request->file()['file'],'csv')->useDelimiter($delimiter)->getHeaders();
            $csvHeaders = array_combine($csvHeaders, $csvHeaders);
        }

        return view('adm.stockImportProcessCsv', compact('csvHeaders','csvFile','delimiter','typeStock','partner','offices'));

    }

    public function processCsv(Request $request) {

        self::$request = $request;

        /* Relembra a ordem dos campos no formulario */
        session(['importFields' => $request->toArray()]);

        /* Carrega o CSV */
        if(!$csvFile = Storage::path($request->csv_file)){
            echo (new Response())->error('Falha ao carregar o arquivo')->json();
            return;
        }

        /* Percorre o CSV */
        $csvFile = SimpleExcelReader::create($csvFile)->useDelimiter(self::$request->delimiter??',')->getRows();
        $csvFile->each(function(array $stock) {

            $fail = []; //Caso houver erro armazena em array

            $data = new stdClass();
            $data->type         = self::$request->type;
            $data->office_id    = self::$request->office_id;
            $data->partner_id   = self::$request->partner_id;
            $data->quantity     = (int) $stock[self::$request->quantity];

            if(!$data->product = $this->checkProduct($stock[self::$request->isbn])){
                $fail[] = "ISBN {$stock[self::$request->isbn]} não cadastrado.";
            } else {
                $data->product_id = $data->product->id;
            }

            if(!$data->addressing = $this->checkAddressing($stock[self::$request->addressing],(int)$data->office_id)){
                $fail[] = "Endereçamento não Cadastrado.";
            } else {
                $data->addressing_id = $data->addressing->id;
            }

            if(empty($fail) && Stocks::where('office_id',$data->office_id)
                    ->where('partner_id','!=',$data->partner_id)
                    ->where('addressing_id',$data->addressing_id)
                    ->where('product_id',$data->product_id)
                    ->first()){
                $fail[] = "Endereçamento ocupado.";
            }

            if(empty($fail) && !Stocks::updateOrCreate([
                    'office_id' => $data->office_id,
                    'partner_id' => $data->partner_id,
                    'product_id' => $data->product_id,
                    'addressing_id' => $data->addressing_id,
                ], (array) $data)->save()){
                $fail[] = "Falha ao Salvar Estoque";
            }


            /*
             * DAQUI PARA BAIXO É FORMATAÇÃO PARA UTILIZAR NO DATATABLE.
             * DESCONSIDERAR EM OUTRAS CIRCUNSTANCIAS
             */

            $data = [
                'product'       => ($data->product->title??"ND"),
                'isbn'          => ($data->product->isbn??"ND"),
                'addressing'    => ($data->addressing->name??"ND"),
                'quantity'      => $data->quantity,
                'status'        => (empty($fail)?"Sucesso":implode(" ",$fail))
            ];

            /* Adiciona uma linha */
            self::$data['data'][] = $data;
            /* Verifica se as colunas do datatable estão preenchidas */
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
     * VERIFICA SE O ISBN EXISTE NA BASE.
     * SE SIM, RETORNA ID. SE NÃO, RETORNA NULL
     * @param string $isbn
     * @return Products|null
     */
    private function checkProduct(string $isbn):? Products {
       return Products::where('isbn',$isbn)->first();
    }

    /**
     * VERIFICA SE O ENDEREÇAMENTO EXISTE
     * SE NÃO, VERIFICA SE PODE CRIAR.
     * @param string $addressingName
     * @param int $office_id
     * @return Addressings|null
     */
    private function checkAddressing(string $addressingName, int $office_id):? Addressings {

        /* To Upper */
        $addressingName = strtoupper($addressingName);

        /* Verifica se o endereço existe */
        if($addressing = Addressings::where('office_id',$office_id)->where('name',$addressingName)->first()){
            return $addressing;
        }

        /* Caso não exista, pode ser cadastrado? (Cuidado aqui é negativa) */
        if(empty(self::$request->save_addressing)){
            return null;
        }

        /* Se puder, conseguimos cadastrar com sucesso? */
        if($addressing = Addressings::create(['office_id'=>$office_id,"name"=>$addressingName,"distance"=>1])){
            return $addressing;
        }

        return null;

    }

}
