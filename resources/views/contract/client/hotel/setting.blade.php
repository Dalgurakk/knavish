@extends('......layouts.master2')
@section('title','Hotel Contract Settings')
@section('page-css')
<link href="{{ asset('assets/global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/bootstrap-modal/css/bootstrap-modal-bs3patch.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css') }}" rel="stylesheet" type="text/css" />
<style>
.table-setting td { font-size: 11px !important; padding: 5px 1px !important; word-wrap:break-word;white-space: normal !important; text-align: center; }
.table-setting th { font-size: 11px !important; padding: 5px 2px !important; word-wrap:break-word;white-space: normal !important; text-align: center; }
.table-setting { margin-bottom: 0; table-layout: fixed !important; min-width: 900px; border-bottom: 0;}
.porlet-title-setting { min-height: 0 !important; height: 30px; }
.caption-setting { font-size: 13px !important; padding: 6px 0 5px !important; font-weight: 600; }
.tools-setting { font-size: 13px !important; padding: 6px 0 0 !important; }
.table-setting .item-setting:hover { background-color: #f2f2f2; cursor: pointer; }
.column-setting { width: 2.9%; }
.head-setting { vertical-align: top !important; background-color: #e8f0fc; border:1px solid #fff !important; }
.head-setting-invalid { background-color: #fff !important; border: 1px solid #fff !important;}
.room-name { word-wrap:break-word;width: 10.1%; color: #fff; background-color: #6d90c4;white-space: normal !important; vertical-align: middle !important;}
.item-variable { /*font-weight: 600;background-color: #e8f0fc; border:1px solid #fff !important;*/}
/*.room-name { word-wrap:break-word;width: 10.1%;}*/
/*.select2-selection__rendered { margin-left: 20px; }*/
.mt-checkbox-row { margin-bottom: 10px !important; }
/*.mt-checkbox-list-row { padding: 0 !important; }*/
.portlet-body-row { padding-top: 5px !important; padding-bottom: 5px !important }
/*.btn-search-submit { margin-top: 10px; }*/
/*.porlet-setting { margin-bottom: 5px !important;}*/
/*.medium-porlet { min-height: 0 !important; height: 30px; }*/
.mt-radio { margin-bottom: 10px !important; }
.note-custom { padding: 4px 10px !important; margin-bottom: 5px !important; }
</style>
@stop

@section('page-title','Hotel Contract Settings')
@section('page-sub-title','define prices, allotments and more...')

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="portlet light custom-container" style="padding-bottom: 0;">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-settings"></i>Search Accommodation </div>
            </div>
            <form id="search-accomodation">
            <div class="portlet-body">
                <div class="row filter-content" style="display: none;">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="portlet box green">
                            <div class="portlet-title porlet-title-setting">
                                <div class="caption caption-setting">
                                    <i class="fa fa-building-o"></i>Contract</div>
                            </div>
                            <div class="portlet-body">
                                <div class="row" style="margin-top: 10px;">
                                    <div class="col-lg-3 col-md-3 col-sm-3">
                                        <label>Contract</label>
                                        <div class="form-group">
                                            <select class="form-control" name="contract"></select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3">
                                        <div class="form-group">
                                            <label>Hotel</label>
                                            <input type="text" class="form-control" name="hotel" readonly style="background-color: #fff;">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3">
                                        <div class="form-group">
                                            <label>Period</label>
                                            <input type="text" class="form-control" name="period" readonly style="background-color: #fff;">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3">
                                        <div class="form-group">
                                            <label>Client</label>
                                            <input type="text" class="form-control" name="client" readonly style="background-color: #fff;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="portlet box green porlet-setting">
                            <div class="portlet-title porlet-title-setting">
                                <div class="caption caption-setting">
                                    <i class="fa fa-calendar"></i>Range Date</div>
                            </div>
                            <div class="portlet-body" style="padding-bottom: 8px;">
                                <div class="scroller" style="height:200px">
                                    <div class="row">
                                        <div class="col-md-12 datepicker-from-container" style="margin-top: 8px;"></div>
                                        <div class="col-md-12 datepicker-to-container"></div>
                                        <div class="col-md-12">
                                            <button type="submit" class="btn green btn-search-submit" style="margin-top:5px;"> <i class="fa fa-search"></i> Search</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="portlet box green porlet-setting">
                            <div class="portlet-title porlet-title-setting">
                                <div class="caption caption-setting">
                                    <i class="fa fa-hotel"></i>Rooms</div>
                            </div>
                            <div class="portlet-body" style="padding-bottom: 8px;">
                                <div class="scroller" style="height:200px">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mt-checkbox-list mt-checkbox-list-row room-types-list"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="portlet box green porlet-setting">
                            <div class="portlet-title porlet-title-setting">
                                <div class="caption caption-setting">
                                    <i class="fa fa-table"></i>Rows</div>
                            </div>
                            <div class="portlet-body" style="padding-bottom: 8px;">
                                <div class="scroller" style="height:200px">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mt-checkbox-list mt-checkbox-list-row measures-list"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 result-container"></div>
</div>

@stop

@section('page-plugins')
<script src="{{ asset('assets/global/plugins/morris/morris.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/my-moment.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-modal/js/bootstrap-modalmanager.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-modal/js/bootstrap-modal.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/jquery-validation/js/jquery.validate.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/jquery-validation/js/additional-methods.min.js') }}" type="text/javascript"></script>
<script>
    var routeSearch = "{{ route('contract.client.hotel.search') }}";
    var routeClient = "{{ route('contract.client.hotel') }}";
    var routeData = "{{ route('contract.client.hotel.settings.data') }}";
    var contractId = '{{ $contract_id }}';
</script>
@stop

@section('custom-scripts')
<script src="{{ asset('assets/pages/scripts/contract/client/hotel/setting.js') }}" type="text/javascript"></script>
@stop