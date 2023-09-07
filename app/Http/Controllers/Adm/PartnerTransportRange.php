<?php

namespace App\Http\Controllers\Adm;

use App\Http\Controllers\Controller;
use App\Http\Libraries\Response;
use App\Http\Requests\AdmTransportRangeRequest;
use App\Models\Partners;
use App\Models\PartnersRanges;
use App\Models\TransportRanges;
use Illuminate\Http\Request;

class PartnerTransportRange extends Controller {

    public function index() {
        $dropPartners = Partners::where('segment','1')->get(['id','name'])->toArray();
        return view('adm.partnerTransportRange', compact('dropPartners'));
    }

    public function edit(Request $request) {

        $transportRanges = TransportRanges::get()->map(function ($transportRange) use ($request) {
            $partnerRange = PartnersRanges::where('transport_range_id',$transportRange->id)
                ->where("partner_id", (int) $request->partner_id)->first();
            $transportRange->enabled = (bool) $partnerRange;
            return $transportRange;
        });

        $dropPartners = Partners::where('segment','1')->get(['id','name'])->toArray();

        session()->flashInput($request->input());
        return view('adm.partnerTransportRange', compact('dropPartners','transportRanges'));
    }

    public function save(TransportRanges $transportRange, Partners $partner) {

        if($partnerRange = PartnersRanges::where('transport_range_id',$transportRange->id)->where('partner_id', $partner->id)->first()){
            $partnerRange->delete();
            echo (new Response())->success('Range Desabilitado')->json();
            return;
        }

        if(PartnersRanges::create([
            'partner_id'            => $partner->id,
            'transport_range_id'    => $transportRange->id
        ])){
            echo (new Response())->success('Range Habilitado')->json();
            return;
        }

        echo (new Response())->error('Nenhuma alteração realizada')->json();

    }

}
