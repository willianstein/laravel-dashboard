<?php

namespace App\Http\Controllers\Adm;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Libraries\Response;
use App\Http\Requests\AdmPartnerServiceRequest;
use App\Http\Requests\AdmPartnerRequest;
use App\Http\Requests\AdmPartnerAddressRequest;
use App\Http\Requests\AdmPartnerContactRequest;
use App\Models\PartnerServices;
use App\Models\Partners;
use App\Models\Addresses;
use App\Models\Contacts;
use App\Models\Services;

/**
 *  CADASTRO DE PARCEIROS
 */
class Partner extends Controller {

    /**
     * PAGINA INICIAL
     * @return Application|Factory|View
     */
    public function index(?Partners $partner) {
        $dropServices = Services::get(['id','description'])->toArray();
        return view('adm.partner', compact('partner','dropServices'));
    }

    /**
     * BUSCA POR NOME OU DOCUMENTO PRINCIPAL
     * @param Request $request
     * @return void
     */
    public function search(Request $request) {

        if(!empty($request->name)) {
            $field = 'name';
            $value = $request->name;
        }

        if(!empty($request->document01)) {
            $field = 'document01';
            $value = clear_number($request->document01);
        }

        if(!empty($field) && $partners = Partners::where($field, 'like', "%{$value}%")->get()){
            foreach ($partners as $partner){
                $data['data'][] = [
                    $partner->name,
                    str_convert_to_document($partner->document01),
                    "<a href=\"".route('adm.partner.index',['partner'=>$partner->id])."\" class=\"badge badge-success\"><i class=\"fas fa-pen\"></i> EDITAR</a>"
                ];
            }
        }
        echo json_encode(($data??['data'=>[]]));
    }

    /**
     * SALVA DADOS DO PARCEIRO
     * @param AdmPartnerRequest $request
     * @return RedirectResponse|void
     */
    public function save(AdmPartnerRequest $request) {

        $listPost = $request->validated();
        $listPost['id'] = $listPost['partner_id'];

        if(!$partner = Partners::updateOrCreate(['id'=>($listPost['id']??null)],$listPost)){
            echo (new Response())->error('Erro ao Salvar o Registro')->json();
            return;
        }

        echo (new Response())->success('Registro Salvo com Sucesso')->action('redirect',route('adm.user.create',['partner'=>$partner->id]))->flash();
    }



    /**
     * BUSCA TODOS ENDEREÇOS DOS PARCEIRO
     * @param Partners|null $partner
     * @return void
     */
    public function getAddresses(Partners $partner) { /* Pega todos os Endereços do Parceiro */
        if($addresses = Addresses::where('partner_id',$partner->id)->get()){
            foreach ($addresses as $address){
                /* Botão Ativo */
                $switchActive   = "<div class=\"custom-control custom-switch custom-switch-off-danger custom-switch-on-success\">\n"
                                . "    <input type=\"checkbox\" class=\"custom-control-input ajax-check\" data-url=\"".route('adm.partner.onOffAddress',['partner'=>$address->partner_id,'address'=>$address->id])."\"  id=\"active_contact_{$address->id}\" ".(empty($address->active)?"":"checked").">\n"
                                . "    <label class=\"custom-control-label\" for=\"active_contact_{$address->id}\">Ativo?</label>\n"
                                . "</div>";

                $data['data'][] = [
                    "{$address->address}, $address->number".(!empty($address->complement)?" - {$address->complement}":null),
                    $address->neighborhood,
                    $address->city,
                    $address->state,
                    str_convert_to_zip_code($address->postal_code),
                    $switchActive,
                    "<span class=\"badge badge-success ajax-link\" data-obj=\"addressForm\" data-url=\"".route('adm.partner.getAddress',['partner'=>$address->partner_id,'address'=>$address->id])."\"><i class=\"fas fa-pen\"></i> EDITAR</span>"
                ];
            }
        }
        echo json_encode(($data??['data'=>[]]));
    }

    /**
     * BUSCA O ENDEREÇO DO PARCEIRO POR ID
     * @param Partners|null $partner
     * @param Addresses $address
     * @return void
     */
    public function getAddress(Partners $partner, Addresses $address) {
        $address['address_id'] = $address['id'];
        unset($address['id']);
        echo (new Response())->action('loadForm','addressForm')->data($address->toArray())->json();
    }

    /**
     * ATIVA OU DESATIVA UM ENDEREÇO
     * @return void
     */
    public function onOffAddress() {

    }

    /**
     * CRIA OU ATUALIZA UM ENDEREÇO
     * @param Partners $partner
     * @param AdmPartnerAddressRequest $request
     * @return void
     */
    public function saveAddress(Partners $partner, AdmPartnerAddressRequest $request) {

        $listPost = $request->validated();
        $listPost['id'] = $listPost['address_id'];
        $listPost['partner_id'] = $partner->id;

        if(!$partner = Addresses::updateOrCreate(['id'=>($listPost['id']??null)],$listPost)){
            echo (new Addresses())->error('Erro ao Salvar o Registro')->json();
            return;
        }

        echo (new Response())->success('Registro Salvo com Sucesso')->action('reloadDataTable','addressesTable')->json();
    }



    /**
     * BUSCA TODOS OS CONTATOS DO PARCEIRO
     * @param Partners $partner
     * @return void
     */
    public function getContacts(Partners $partner) {
        if($contacts = Contacts::where('partner_id',$partner->id)->get()){
            foreach ($contacts as $contact){
                /* Botão Ativo */
                $switchActive   = "<div class=\"custom-control custom-switch custom-switch-off-danger custom-switch-on-success\">\n"
                                . "    <input type=\"checkbox\" class=\"custom-control-input ajax-check\" data-url=\"".route('adm.partner.onOffContact',['partner'=>$contact->partner_id,'contact'=>$contact->id])."\"  id=\"active_contact_{$contact->id}\" ".(empty($contact->active)?"":"checked").">\n"
                                . "    <label class=\"custom-control-label\" for=\"active_contact_{$contact->id}\">Ativo?</label>\n"
                                . "</div>";

                $data['data'][] = [
                    $contact->name,
                    $contact->cellphone,
                    $contact->email,
                    $contact->position,
                    $switchActive,
                    "<span class=\"badge badge-success ajax-link\" data-obj=\"contactForm\" data-url=\"".route('adm.partner.getContact',['partner'=>$contact->partner_id,'contact'=>$contact->id])."\"><i class=\"fas fa-pen\"></i> EDITAR</span>"
                ];
            }
        }
        echo json_encode(($data??['data'=>[]]));
    }

    /**
     * BUSCA O CONTATO DO PARCEIRO POR ID
     * @param Partners $partner
     * @param Contacts $contact
     * @return void
     */
    public function getContact(Partners $partner, Contacts $contact) {
        $contact['contact_id'] = $contact['id'];
        unset($contact['id']);
        echo (new Response())->action('loadForm','contactForm')->data($contact->toArray())->json();
    }

    /**
     * ATIVA OU DESATIVA UM ENDEREÇO
     * @return void
     */
    public function onOffContact() {

    }

    /**
     * SALVA O CONTATO
     * @param Partners $partner
     * @param AdmPartnerContactRequest $request
     * @return void
     */
    public function saveContact(Partners $partner, AdmPartnerContactRequest $request) {

        $listPost = $request->validated();
        $listPost['id'] = $listPost['contact_id'];
        $listPost['partner_id'] = $partner->id;

        if(!$partner = Contacts::updateOrCreate(['id'=>($listPost['id']??null)],$listPost)){
            echo (new Contacts())->error('Erro ao Salvar o Registro')->json();
            return;
        }

        echo (new Response())->success('Registro Salvo com Sucesso')->action('reloadDataTable','contactsTable')->json();
    }



    /**
     * RETORNA LISTA COM OS SERVIÇOS DO PARCEIRO EM JSON
     * @param Partners $partner
     * @return void
     */
    public function getServices(Partners $partner) {
        if($services = PartnerServices::where('partner_id',$partner->id)->get()){
            foreach ($services as $service){
                /* Botão Ativo */
                $switchActive   = "<div class=\"custom-control custom-switch custom-switch-off-danger custom-switch-on-success\">\n"
                                . "    <input type=\"checkbox\" class=\"custom-control-input ajax-check\" data-url=\"".route('adm.partner.onOffService',['partner'=>$service->partner_id,'service'=>$service->id])."\"  id=\"active_service_{$service->id}\" ".(empty($service->active)?"":"checked").">\n"
                                . "    <label class=\"custom-control-label\" for=\"active_service_{$service->id}\">Ativo?</label>\n"
                                . "</div>";

                $data['data'][] = [
                    $service->service->description,
                    "R$ ".money($service->service->price),
                    "R$ ".money($service->price),
                    $switchActive,
                    "<span class=\"badge badge-success ajax-link\" data-obj=\"serviceForm\" data-url=\"".route('adm.partner.getService',['partner'=>$service->partner_id,'service'=>$service->id])."\"><i class=\"fas fa-pen\"></i> EDITAR</span>"
                ];
            }
        }
        echo json_encode(($data??['data'=>[]]));
    }

    /**
     * RETORNA UM SERVIÇO DO PARCEIRO EM JSON
     * @param Partners $partner
     * @param PartnerServices $service
     * @return void
     */
    public function getService(Partners $partner, PartnerServices $service) {
        $service->price = money($service->price);
        $service['partner_service_id'] = $service['id'];
        unset($service['id']);
        echo (new Response())->action('loadForm','serviceForm')->data($service->toArray())->json();
    }

    /**
     * ATIVA DESATIVA UM SERVIÇO DO PARCEIRO
     * @return void
     */
    public function onOffService() {

    }

    /**
     * SALVA UM SERVIÇO DO PARCEIRO
     * @param Partners $partner
     * @param AdmPartnerServiceRequest $request
     * @return void
     */
    public function saveService(Partners $partner, AdmPartnerServiceRequest $request) {

        $listPost = $request->validated();
        $listPost['id'] = $listPost['partner_service_id'];
        $listPost['partner_id'] = $partner->id;

        if(!PartnerServices::updateOrCreate(['id'=>($listPost['id']??null)],$listPost)){
            echo (new Response())->error('Erro ao Salvar o Registro')->json();
            return;
        }

        echo (new Response())->success('Registro Salvo com Sucesso')->action('reloadDataTable','servicesTable')->json();

    }
}
