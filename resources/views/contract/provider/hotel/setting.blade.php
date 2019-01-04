@extends('layouts.master2')
@section('title','Hotel Contract Settings')
@section('page-css')
<link href="{{ asset('assets/global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/bootstrap-modal/css/bootstrap-modal-bs3patch.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/bootstrap-sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/bootstrap-touchspin/bootstrap.touchspin.css') }}" rel="stylesheet" type="text/css" />
<style>
    .show-import, .show-import:focus, .show-import:hover { color: #fff; background-color: #32c5d2; border-color: #32c5d2; }
    .hide-import, .hide-import:focus, .hide-import:hover { color: #32c5d2; background-color: #fff; border-color: #32c5d2; }
</style>
@stop

@section('page-title','Hotel Contract Settings')
@section('page-sub-title','define costs, prices and more...')

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
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="portlet box green porlet-setting">
                            <div class="portlet-title porlet-title-setting">
                                <div class="caption caption-setting">
                                    <i class="fa fa-file-text-o"></i>Contract</div>
                            </div>
                            <div class="portlet-body" style="padding-bottom: 8px;">
                                <div class="scroller" style="height:200px">
                                    <div class="row">
                                        <div class="col-md-12" style="margin-top: 8px;">
                                            <div class="form-group">
                                                <label>Contract</label>
                                                <select class="form-control" name="contract"></select>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Period</label>
                                                <input type="text" class="form-control" name="period" readonly style="background-color: #fff;">
                                            </div>
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
                                    <i class="fa fa-building-o"></i>Hotel</div>
                            </div>
                            <div class="portlet-body" style="padding-bottom: 8px;">
                                <div class="scroller" style="height:200px">
                                    <div class="row">
                                        <div class="col-md-12" style="margin-top: 8px;">
                                            <div class="form-group">
                                                <label>Hotel</label>
                                                <input type="text" class="form-control" name="hotel" readonly style="background-color: #fff;">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Hotel Chain</label>
                                                <input type="text" class="form-control" name="hotel-chain" readonly style="background-color: #fff;">
                                            </div>
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
                                    <i class="fa fa-tag"></i>Price Rate</div>
                            </div>
                            <div class="portlet-body" style="padding-bottom: 8px;">
                                <div class="scroller" style="height:200px">
                                    <div class="row">
                                        <div class="col-md-12" style="margin-top: 8px;">
                                            <div class="form-group">
                                                <label>Price Rate</label>
                                                <select class="form-control" name="market" id="market"></select>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Import Cost From</label>
                                                <select class="form-control" name="import-from" id="import-from"></select>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <button class="btn green btn-import" style="margin-top:5px;"> <i class="fa fa-download"></i> Import</button>
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

<div id="modal-setting" class="modal fade custom-container" tabindex="-1" data-width="650" data-backdrop="static" data-keyboard="false">
    <div class="modal-header">
        <button type="button" class="close cancel-form" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title"><i class="icon-settings"></i> Settings</h4>
    </div>
    <form id="form-setting">
    <div class="modal-body" style="padding-bottom: 0;">
        <input type="hidden" name="contract-id" value="0">
        <input type="hidden" name="room-type-id" value="0">
        <input type="hidden" name="market-id" value="0">
        <div class="row">
            <div class="col-md-12" style="margin-bottom: 25px; display: inline-block; text-align: center;">
                <div class="caption">
                    <span class="caption-subject font-green-sharp bold uppercase room-name-header" style="font-size: 16px;"></span>
                </div>
            </div>
            <div class="col-md-12 share-container" style="margin-bottom: 30px; display: inline-block; text-align: center;">
                <label class="mt-checkbox mt-checkbox-outline no-margin-bottom share"> Share
                    <input type="checkbox" value="1" name="share"/>
                    <span></span>
                </label>
                <label class="mt-checkbox mt-checkbox-outline no-margin-bottom change" style="margin-left: 20px;"> Change Room
                    <input type="checkbox" value="1" name="change"/>
                    <span></span>
                </label>
            </div>
        </div>
        <div class="share-rooms shearing" style="display: none;">
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet box green porlet-setting">
                        <div class="portlet-title porlet-title-setting">
                            <div class="caption caption-setting">
                                <i class="fa fa-hotel"></i>Rooms</div>
                        </div>
                        <div class="portlet-body">
                            <div class="row">
                                <div class="rooms-container">
                                    <div class="col-md-12">
                                        <div id="search-section">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" name="search-code" placeholder="Code">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" name="search-name" placeholder="Denomination">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <table class="table table-striped table-bordered table-hover dt-responsive dt-custom-datatable table-room-type" width="100%" cellspacing="0">
                                            <thead>
                                            <tr>
                                                <th>
                                                    <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                                        <input type="checkbox" class="group-checkable" data-set=".table-room-type .checkboxes" />
                                                        <span></span>
                                                    </label>
                                                </th>
                                                <th> Id </th>
                                                <th> Code </th>
                                                <th> Denomination </th>
                                            </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="portlet box green porlet-setting">
                    <div class="portlet-title porlet-title-setting">
                        <div class="caption caption-setting">
                            <i class="fa fa-calendar"></i>Range Date</div>
                    </div>
                    <div class="portlet-body">
                        <div class="row">
                            <div class="range-container">
                                <div class="range">
                                    <div class="col-md-5 col-sm-5 col-xs-5">
                                        <div class="form-group">
                                            <label>From</label>
                                            <div class="input-icon left">
                                                <i class="fa fa-calendar"></i>
                                                <input type="text" class="form-control date-picker" name="setting-from">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5 col-sm-5 col-xs-5">
                                        <div class="form-group">
                                            <label>To</label>
                                            <div class="input-icon left">
                                                <i class="fa fa-calendar"></i>
                                                <input type="text" class="form-control date-picker" name="setting-to">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xs-2">
                                        <div class="form-group">
                                            <a class="btn blue btn-outline add-row add-row-setting" href="javascript:;">
                                                <i class="fa fa-plus"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="portlet box green porlet-setting">
                    <div class="portlet-title porlet-title-setting">
                        <div class="caption caption-setting">
                            <i class="icon-settings"></i>Sets</div>
                    </div>
                    <div class="portlet-body">
                        <div class="row">
                            <div class="measures-container"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn green" data="apply"><i class="fa fa-repeat"></i> Apply</button>
        <button type="submit" class="btn green" data="accept"><i class="fa fa-check"></i> Accept</button>
        <button type="button" data-dismiss="modal" class="btn btn-outline dark cancel-form"><i class="fa fa-close"></i> Cancel</button>
    </div>
    </form>
</div>

<div id="modal-change" class="modal fade custom-container" tabindex="-1" data-width="650" data-backdrop="static" data-keyboard="false">
    <div class="modal-header">
        <button type="button" class="close cancel-change" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title"><i class="fa fa-hotel"></i> Select Room Type</h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12" style="padding-top: 20px; padding-bottom: 20px;">
                <select class="form-control" name="change-room"></select>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn green accept-change" data="accept"><i class="fa fa-check"></i> Accept</button>
        <button type="button" data-dismiss="modal" class="btn btn-outline dark cancel-change"><i class="fa fa-close"></i> Cancel</button>
    </div>
</div>

<div id="modal-import" class="modal fade custom-container" tabindex="-1" data-width="650" data-backdrop="static" data-keyboard="false">
    <div class="modal-header">
        <button type="button" class="close cancel-import" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title"><i class="fa fa-download"></i> Import</h4>
    </div>
    <form id="form-import">
    <div class="modal-body">
        <div class="row">
            <div class="row">
                <div class="col-md-12" style="margin-bottom: 25px; display: inline-block; text-align: center;">
                    <div class="caption">
                        <span class="caption-subject font-green-sharp bold uppercase room-name-header" style="font-size: 16px;"></span>
                    </div>
                </div>
            </div>
            <div class="col-md-12 share-container" style="margin-bottom: 25px; display: inline-block; text-align: center;">
                <label class="mt-checkbox mt-checkbox-outline no-margin-bottom set-all-dates"> All Dates
                    <input type="checkbox" value="1" name="all-dates"/>
                    <span></span>
                </label>
            </div>
            <div class="col-md-12 all-dates-container" style="display: none;">
                <div class="portlet box green porlet-setting">
                    <div class="portlet-title porlet-title-setting">
                        <div class="caption caption-setting">
                            <i class="fa fa-calendar"></i>Ranges</div>
                    </div>
                    <div class="portlet-body">
                        <div class="row">
                            <div class="import-range-container">
                                <div class="range">
                                    <div class="col-md-5 col-sm-5 col-xs-5">
                                        <div class="form-group">
                                            <label>From</label>
                                            <div class="input-icon left">
                                                <i class="fa fa-calendar"></i>
                                                <input type="text" class="form-control date-picker" name="import-from">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5 col-sm-5 col-xs-5">
                                        <div class="form-group">
                                            <label>To</label>
                                            <div class="input-icon left">
                                                <i class="fa fa-calendar"></i>
                                                <input type="text" class="form-control date-picker" name="import-to">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xs-2">
                                        <div class="form-group">
                                            <a class="btn blue btn-outline add-row add-row-import" href="javascript:;">
                                                <i class="fa fa-plus"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="portlet box green porlet-setting">
                    <div class="portlet-title porlet-title-setting">
                        <div class="caption caption-setting">
                            <i class="fa fa-download"></i>Import</div>
                    </div>
                    <div class="portlet-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="note note-info">
                                    <p>This will overwrite the current configuration of the room.</p>
                                </div>
                            </div>
                            <div class="col-md-12" style="padding-bottom: 10px;">
                                <div class="form-group">
                                    <label>From</label>
                                    <select class="form-control" name="select-room"></select>
                                </div>
                            </div>
                            <div class="col-md-12 share-container" style="margin-bottom: 15px; display: inline-block; text-align: center;">
                                <label class="mt-checkbox mt-checkbox-outline no-margin-bottom set-add-value" style="margin-left: 10px;"> Add Value
                                    <input type="checkbox" value="1" name="add-value"/>
                                    <span></span>
                                </label>
                            </div>
                            <div class="col-md-12 add-value-container" style="text-align: center; margin-top: 15px; display: none;">
                                <div class="mt-radio-list" style="padding: 0;">
                                    <label class="mt-radio mt-radio-outline custom-radio" style="max-width: 300px; display: inline-block; margin-bottom: 0;">
                                        <input type="radio" name="rate_type" value="2" data-target="rate_fee_value" checked>
                                        <div class="form-group">
                                            <input class="form-control fee" placeholder="Fee" type="text" value="" name="rate_fee_value" id="rate_fee_value" disabled/>
                                        </div>
                                        <span style="margin-top: 8px;"></span>
                                    </label>
                                </div>
                                <div class="mt-radio-list" style="padding: 0;">
                                    <label class="mt-radio mt-radio-outline custom-radio" style="max-width: 300px; display: inline-block; margin-bottom: 0;">
                                        <input type="radio" name="rate_type" value="1" data-target="rate_percent_value">
                                        <div class="form-group">
                                            <input class="form-control percent" placeholder="Percent" type="text" value="" name="rate_percent_value" id="rate_percent_value" disabled/>
                                        </div>
                                        <span style="margin-top: 8px;"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn green accept-import" data="accept"><i class="fa fa-download"></i> Import</button>
        <button type="button" data-dismiss="modal" class="btn btn-outline dark cancel-import"><i class="fa fa-close"></i> Cancel</button>
    </div>
    </form>
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
<script src="{{ asset('assets/global/plugins/bootstrap-sweetalert/sweetalert.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/scripts/datatable.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/datatables/datatables.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/fuelux/js/spinner.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-touchspin/bootstrap.touchspin.js') }}" type="text/javascript"></script>
<script>
    var routeSearch = "{{ route('contract.provider.hotel.search') }}";
    var routeContract = "{{ route('contract.provider.hotel') }}";
    var routeData = "{{ route('contract.provider.hotel.settings.data') }}";
    var routeSave = "{{ route('contract.provider.hotel.settings.save') }}";
    var routeImportCostFromPriceRate = "{{ route('contract.provider.hotel.settings.import.costFromPriceRate') }}";
    var routeImportCostFromRoomtype = "{{ route('contract.provider.hotel.settings.import.costFromRoomType') }}";
    var contractId = '{{ $contract_id }}';
</script>
@stop

@section('custom-scripts')
<script src="{{ asset('assets/pages/scripts/contract/provider/hotel/setting.min.js') }}" type="text/javascript"></script>
@stop