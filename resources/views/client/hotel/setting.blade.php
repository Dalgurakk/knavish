@extends('layouts.master2')
@section('title','Hotel Contract Settings')
@section('page-css')
    <link href="{{ asset('assets/global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/bootstrap-modal/css/bootstrap-modal-bs3patch.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css') }}" rel="stylesheet" type="text/css" />
@stop

@section('page-title','Hotel Contract Settings')
@section('')

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
                                                <label>Client</label>
                                                <input type="text" class="form-control" name="client" readonly style="background-color: #fff;">
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3">
                                            <div class="form-group">
                                                <label>Board Type</label>
                                                <select class="form-control" name="board-type" id="board-type"></select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top: 10px;">
                                        <div class="col-lg-3 col-md-3 col-sm-3">
                                            <div class="form-group">
                                                <label>Period</label>
                                                <input type="text" class="form-control" name="period" readonly style="background-color: #fff;">
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3">
                                            <div class="form-group">
                                                <label>Hotel Chain</label>
                                                <input type="text" class="form-control" name="hotel-chain" readonly style="background-color: #fff;">
                                            </div>
                                        </div>
                                        <!--div class="col-lg-3 col-md-3 col-sm-3">
                                            <a class="btn green btn-complements" style="margin-top:25px;" href="javascript:;"> <i class="fa fa-briefcase"></i> Complements</a>
                                        </div-->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <div class="portlet box green porlet-setting">
                                <div class="portlet-title porlet-title-setting">
                                    <div class="caption caption-setting">
                                        <i class="fa fa-calendar"></i>Range</div>
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
                                    <div class="tools tools-setting">
                                        <a href="javascript:;" class="tool-item check-all-rooms" data="check"> <i class="fa fa-check-square"></i></a></div>
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

<div id="modal-offers" class="modal fade custom-container" tabindex="-1" data-width="760" data-backdrop="static" data-keyboard="false">
    <div class="modal-header">
        <button type="button" class="close cancel-form-offer" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title"><i class="fa fa-file-text-o"></i> Offers </h4>
    </div>
    <div class="modal-body">
        <div class="row offers-container"></div>
    </div>
    <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn btn-outline dark cancel-form-offer"><i class="fa fa-close"></i> Cancel</button>
    </div>
</div>
@stop

@section('page-plugins')
<script src="{{ asset('assets/global/plugins/morris/morris.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/my-moment.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-modal/js/bootstrap-modalmanager.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-modal/js/bootstrap-modal.js') }}" type="text/javascript"></script>
<script>
    var routeSearch = "{{ route('client.hotel.search') }}";
    var routeContract = "{{ route('client.hotel') }}";
    var routeData = "{{ route('client.hotel.settings.data') }}";
    var contractId = '{{ $contract_id }}';
</script>
@stop

@section('custom-scripts')
<script src="{{ asset('assets/pages/scripts/client/setting.min.js') }}" type="text/javascript"></script>
@stop