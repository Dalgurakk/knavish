<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UserExport;
use OwenIt\Auditing\Models\Audit;

class TraceController extends Controller
{
    private $response;

    public function __construct() {
        $this->middleware('auth');
    }

    public function index(Request $request) {
        $request->user()->authorizeRoles(['administrator']);

        $breadcrumb = array(
            0 => 'Administration',
            1 => 'Traces'
        );

        $data['breadcrumb'] = $breadcrumb;
        $data['menuAdministration'] = 'selected';
        $data['submenuTrace'] = 'selected';
        $data['currentDate'] = parent::currentDate();

        return view('administration.trace')->with($data);
    }

    public function read(Request $request) {
        $request->user()->authorizeRoles(['administrator']);

        $limit = Input::get('length');
        $offset = Input::get('start') ? Input::get('start') : 0;
        $columns = array(
            'audits.id', 'audits.user_type', 'audits.user_id', 'audits.user_username',
            'audits.user_email', 'audits.user_name', 'audits.event', 'audits.auditable_type',
            'audits.auditable_id', 'audits.url', 'audits.ip_address', 'audits.user_agent',
            'audits.created_at', 'audits');
        $orderBy = Input::get('order')['0']['column'];
        $orderDirection = Input::get('order')['0']['dir'];
        $searchUserId = Input::get('columns')['0']['search']['value'];
        $searchUsername = Input::get('columns')['3']['search']['value'];
        $searchEmail = Input::get('columns')['4']['search']['value'];
        $searchName = Input::get('columns')['5']['search']['value'];
        $searchEvent = Input::get('columns')['6']['search']['value'];
        $searchAuditableType = Input::get('columns')['7']['search']['value'];
        $searchAuditableId = Input::get('columns')['8']['search']['value'];
        $searchUrl = Input::get('columns')['9']['search']['value'];
        $searchIp = Input::get('columns')['10']['search']['value'];
        $searchAgent = Input::get('columns')['11']['search']['value'];
        $traces = array();

        $query = Audit::orderBy($columns[$orderBy], $orderDirection);

        if(isset($searchUserId) && $searchUserId != '') {
            $query->where('audits.user_id', $searchUserId);
        }
        if(isset($searchUsername) && $searchUsername != '') {
            $query->where('audits.user_username', 'like', '%' . $searchUsername . '%');
        }
        if(isset($searchEmail) && $searchEmail != '') {
            $query->where('audits.user_email', 'like', '%' . $searchEmail . '%');
        }
        if(isset($searchName) && $searchName != '') {
            $query->where('audits.user_name', 'like', '%' . $searchName . '%');
        }
        if(isset($searchEvent) && $searchEvent != '') {
            $query->where('audits.event', $searchEvent);
        }
        if(isset($searchAuditableType) && $searchAuditableType != '') {
            $query->where('audits.auditable_type', 'like', '%' . $searchAuditableType . '%');
        }
        if(isset($searchAuditableId) && $searchAuditableId != '') {
            $query->where('audits.auditable_id', $searchAuditableId);
        }
        if(isset($searchUrl) && $searchUrl != '') {
            $query->where('audits.url', 'like', '%' . $searchUrl . '%');
        }
        if(isset($searchIp) && $searchIp != '') {
            $query->where('audits.ip_address', 'like', '%' . $searchIp . '%');
        }
        if(isset($searchAgent) && $searchAgent != '') {
            $query->where('audits.user_agent', 'like', '%' . $searchAgent . '%');
        }

        $records = $query->count();

        $query
            ->offset($offset)
            ->limit($limit);
        $result = $query->get();

        foreach ($result as $r) {
            $item = array(
                'id' => $r->id,
                'user_type' => $r->user_type,
                'user_id' => $r->user_id,
                'user_username' => $r->user_username,
                'user_email' => $r->user_email,
                'user_name' => $r->user_name,
                'event' => $r->event,
                'auditable_type' => substr($r->auditable_type, 11),
                'auditable_id' => $r->auditable_id,
                'url' => $r->url,
                'ip_address' => $r->ip_address,
                'user_agent' => $r->user_agent,
                'created_at' => $r->created_at->format('d.m.Y'),
                //'created_at' => $r->created_at->format('H:i:s'),
                'object' => $r
            );
            $traces[] = $item;
        }

        $data = array(
            "draw" => Input::get('draw'),
            "length" => $limit,
            "start" => $offset,
            "recordsTotal" => $records,
            "recordsFiltered" => $records,
            "data" => $traces
        );
        echo json_encode($data);
    }
}
