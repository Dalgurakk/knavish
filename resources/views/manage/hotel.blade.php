@extends('layouts.master2')
@section('title','Hotel Contract Settings')
@section('page-css')
<link href="{{ asset('assets/global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet" type="text/css" />
<style>
.table-setting td { font-size: 11px !important; padding: 5px 2px !important; word-wrap:break-word;white-space: normal !important; text-align: center; }
.table-setting th { font-size: 11px !important; padding: 5px 2px !important; word-wrap:break-word;white-space: normal !important; text-align: center; }
.table-setting { margin-bottom: 0; table-layout: fixed !important; min-width: 900px; }
.porlet-title-setting { min-height: 0 !important; height: 30px; }
.caption-setting { font-size: 13px !important; padding: 6px 0 5px !important; font-weight: 600; }
.tools-setting { font-size: 13px !important; padding: 6px 0 0 !important; }
.table-setting .item-setting:hover { background-color: #f2f2f2; cursor: pointer; }
.column-setting { width: 2.9%; }
.head-setting { vertical-align: top !important; background-color: #e8f0fc; border:1px solid #fff !important; }
.room-name { word-wrap:break-word;width: 10.1%; color: #fff; background-color: #6d90c4;white-space: normal !important; }
/*.room-name { word-wrap:break-word;width: 10.1%;}*/
.select2-selection__rendered { margin-left: 20px; }
.mt-checkbox-row { margin-bottom: 5px !important; }
.mt-checkbox-list-row { padding: 0 !important; }
.portlet-body-row { padding-top: 5px !important; padding-bottom: 5px !important }
/*.medium-porlet { min-height: 0 !important; height: 30px; }*/
</style>
@stop

@section('page-title','Hotel Contract Settings')
@section('page-sub-title','define prices, allotments and more...')

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="portlet light custom-container">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-settings"></i>Search Accommodation</div>
            </div>
            <form id="search-accomodation">
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="portlet box green">
                        <!--div class="portlet light bordered"-->
                            <div class="portlet-title porlet-title-setting">
                                <div class="caption caption-setting">
                                    <i class="fa fa-building-o"></i>Hotel</div>
                            </div>
                            <div class="portlet-body">
                                <div class="row">
                                    <div class="col-lg-4 col-md-4">
                                        <label>Hotel</label>
                                        <div class="form-group">
                                            <div class="input-icon">
                                                <i class="fa fa-building-o"></i>
                                                <select class="form-control" name="hotel"></select> </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4">
                                        <label>Contract</label>
                                        <div class="form-group">
                                            <div class="input-icon">
                                                <i class="fa fa-file-text-o"></i>
                                                <select class="form-control" name="contract"></select> </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4">
                                        <div class="form-group">
                                            <div class="mt-checkbox-list">
                                                <label class="mt-checkbox mt-checkbox-outline no-margin-bottom" style="margin-top: 15px;"> Load old contracts
                                                    <input type="checkbox" value="1" name="old"/>
                                                    <span></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="portlet box green">
                        <!--div class="portlet light bordered"-->
                            <div class="portlet-title porlet-title-setting">
                                <div class="caption caption-setting">
                                    <i class="fa fa-calendar"></i>Range Date</div>
                            </div>
                            <div class="portlet-body">
                                <div class="scroller" style="height:175px">
                                    <div class="row">
                                        <div class="col-md-12" style="margin-top: 15px;">
                                            <label>From</label>
                                            <div class="form-group">
                                                <div class="input-icon">
                                                    <i class="fa fa-calendar"></i>
                                                    <input class="form-control date-picker" name="from"> </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <label>To</label>
                                            <div class="form-group">
                                                <div class="input-icon">
                                                    <i class="fa fa-calendar"></i>
                                                    <input class="form-control date-picker" name="to"> </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="portlet box green">
                        <!--div class="portlet light bordered"-->
                            <div class="portlet-title porlet-title-setting">
                                <div class="caption caption-setting">
                                    <i class="fa fa-table"></i>Rows</div>
                            </div>
                            <div class="portlet-body">
                                <div class="scroller" style="height:175px">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mt-checkbox-list mt-checkbox-list-row">
                                                <label class="mt-checkbox mt-checkbox-outline mt-checkbox-row">
                                                    <input type="checkbox" checked> Price
                                                    <span></span>
                                                </label>
                                                <label class="mt-checkbox mt-checkbox-outline mt-checkbox-row">
                                                    <input type="checkbox" checked> Allotment
                                                    <span></span>
                                                </label>
                                                <label class="mt-checkbox mt-checkbox-outline mt-checkbox-row">
                                                    <input type="checkbox" checked> Release
                                                    <span></span>
                                                </label>
                                                <label class="mt-checkbox mt-checkbox-outline mt-checkbox-row">
                                                    <input type="checkbox" checked> Offer
                                                    <span></span>
                                                </label>
                                                <label class="mt-checkbox mt-checkbox-outline mt-checkbox-row">
                                                    <input type="checkbox" checked> Stop Sale
                                                    <span></span>
                                                </label>
                                                <label class="mt-checkbox mt-checkbox-outline mt-checkbox-row">
                                                    <input type="checkbox" checked> Restriction
                                                    <span></span>
                                                </label>
                                                <label class="mt-checkbox mt-checkbox-outline mt-checkbox-row">
                                                    <input type="checkbox" checked> Supplement
                                                    <span></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="portlet box green">
                        <!--div class="portlet light bordered"-->
                            <div class="portlet-title porlet-title-setting">
                                <div class="caption caption-setting">
                                    <i class="fa fa-hotel"></i>Rooms</div>
                            </div>
                            <div class="portlet-body">
                                <div class="scroller" style="height:175px">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mt-checkbox-list mt-checkbox-list-row">
                                                <label class="mt-checkbox mt-checkbox-outline mt-checkbox-row">
                                                    <input type="checkbox" checked> Price
                                                    <span></span>
                                                </label>
                                                <label class="mt-checkbox mt-checkbox-outline mt-checkbox-row">
                                                    <input type="checkbox" checked> Allotment
                                                    <span></span>
                                                </label>
                                                <label class="mt-checkbox mt-checkbox-outline mt-checkbox-row">
                                                    <input type="checkbox" checked> Release
                                                    <span></span>
                                                </label>
                                                <label class="mt-checkbox mt-checkbox-outline mt-checkbox-row">
                                                    <input type="checkbox" checked> Offer
                                                    <span></span>
                                                </label>
                                                <label class="mt-checkbox mt-checkbox-outline mt-checkbox-row">
                                                    <input type="checkbox" checked> Stop Sale
                                                    <span></span>
                                                </label>
                                                <label class="mt-checkbox mt-checkbox-outline mt-checkbox-row">
                                                    <input type="checkbox" checked> Restriction
                                                    <span></span>
                                                </label>
                                                <label class="mt-checkbox mt-checkbox-outline mt-checkbox-row">
                                                    <input type="checkbox" checked> Supplement
                                                    <span></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="form-group">
                            <button type="submit" class="btn green btn-search-submit"><i class="fa fa-search"></i> Search</button>
                        </div>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <div class="portlet box green">
            <div class="portlet-title porlet-title-setting">
                <div class="caption caption-setting">
                    <!--i class="fa fa-calendar"></i-->June 2018 </div>
                <div class="tools tools-setting">
                    <a href="" class="fullscreen"> </a>
                    <a href="javascript:;" class="collapse"> </a>
                </div>
            </div>
            <div class="portlet-body" style="padding: 0;">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-setting">
                        <thead>
                            <tr>
                                <th class="room-name head-setting">DOUBLE SUPERIOR</th>
                                <th class="column-setting head-setting">D88</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="column-setting">Price</td>
                                <td class="column-setting item-setting">152</td>
                                <td class="column-setting item-setting">152</td>
                                <td class="column-setting item-setting">152</td>
                                <td class="column-setting item-setting">1152</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                            </tr>
                            <tr>
                                <td class="column-setting">Allotment</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                            </tr>
                            <tr>
                                <td class="column-setting">Release</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="table table-striped table-bordered table-setting">
                        <thead>
                            <tr>
                                <th class="room-name head-setting">SINGLE</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="column-setting">Price</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>

                            </tr>
                            <tr>
                                <td class="column-setting">Allotment</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                            </tr>
                            <tr>
                                <td class="column-setting">Release</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="table table-striped table-bordered table-setting">
                        <thead>
                            <tr>
                                <th class="room-name head-setting">BUNGALOW SUPERIOR 3AD</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="column-setting">Price</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>

                            </tr>
                            <tr>
                                <td class="column-setting">Allotment</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                            </tr>
                            <tr>
                                <td class="column-setting">Release</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="portlet box green">
            <div class="portlet-title porlet-title-setting">
                <div class="caption caption-setting">
                    <!--i class="fa fa-calendar"></i-->June 2018 </div>
                <div class="tools tools-setting">
                    <a href="" class="fullscreen"> </a>
                    <a href="javascript:;" class="collapse"> </a>
                </div>
            </div>
            <div class="portlet-body" style="padding: 0;">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-setting">
                        <thead>
                            <tr>
                                <th class="room-name head-setting">DOUBLE SUPERIOR</th>
                                <th class="column-setting head-setting">D88</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="column-setting">Price</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                            </tr>
                            <tr>
                                <td class="column-setting">Allotment</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                            </tr>
                            <tr>
                                <td class="column-setting">Release</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="table table-striped table-bordered table-setting">
                        <thead>
                            <tr>
                                <th class="room-name head-setting">SINGLE</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="column-setting">Price</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>

                            </tr>
                            <tr>
                                <td class="column-setting">Allotment</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                            </tr>
                            <tr>
                                <td class="column-setting">Release</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="table table-striped table-bordered table-setting">
                        <thead>
                            <tr>
                                <th class="room-name head-setting">BUNGALOW SUPERIOR 3AD</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                                <th class="column-setting head-setting">D1</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="column-setting">Price</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>
                                <td class="column-setting item-setting">52</td>

                            </tr>
                            <tr>
                                <td class="column-setting">Allotment</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                            </tr>
                            <tr>
                                <td class="column-setting">Release</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                                <td class="column-setting item-setting">5</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


@stop

@section('page-plugins')
<script src="{{ asset('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/my-moment.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}" type="text/javascript"></script>
@stop

@section('custom-scripts')
<script>
    $(document).ready(function () {
         $('.item-setting').on('click', function() {
            alert($(this).html());
         });

         /*$('.date-picker').datepicker({
             rtl: App.isRTL(),
             orientation: "left",
             autoclose: true,
             format: 'dd-mm-yyyy'
         });*/

         $(".date-picker").datepicker({
            format: "MM yyyy",
            viewMode: "months",
            minViewMode: "months",
            autoclose: true
         });



         function formatHotel(repo) {
             if (repo.loading) return repo.text;
             var markup =
                 "<div class=''>" +
                     "<div class=''>" + repo.name + "</div>"+
                 "</div>";
             return markup;
         }

         function formatHotelSelection(repo) {
             return repo.name;
         }

         $("#search-accomodation :input[name=hotel]").select2({
             width: "off",
             placeholder: "<i class='icon-group'></i> &nbsp;&nbsp; inout your tags...",
             ajax: {
                 url: "{{ route('hotel.search.active') }}",
                 "type": "POST",
                 dataType: 'json',
                 delay: 250,
                 data: function(params) {
                     return {
                         q: params.term,
                         page: params.page
                     };
                 },
                 processResults: function(data, page) {
                     return {
                         results: data
                     };
                 },
                 cache: true
             },
             escapeMarkup: function(markup) {
                 return markup;
             },
             minimumInputLength: 3,
             templateResult: formatHotel,
             templateSelection: formatHotelSelection
         });
    });
</script>
@stop