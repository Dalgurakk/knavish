/* =========================================================
 * bootstrap-datepaginator.js v1.1.0
 * =========================================================
 * Copyright 2013 Jonathan Miles 
 * Project URL : http://www.jondmiles.com/bootstrap-datepaginator
 *	
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ========================================================= */

;(function($, window, document, undefined) {

    /*global jQuery, moment*/

    'use strict';

    var pluginName = 'royalviewer';

    var RoyalViewer = function(element, options) {
        this._element = element;
        this.$element = $(element);
        this._init(options);
    };

    RoyalViewer.defaults = {
        fillWidth: true,
        highlightSelectedDate: true,
        highlightToday: true,
        hint: 'dddd, Do MMMM YYYY',
        injectStyle: true,
        itemWidth: 35,
        navItemWidth: 20,
        offDays: 'Sat,Sun',
        offDaysFormat: 'ddd',
        onSelectedDateChanged: null,
        selectedDate: moment().clone().startOf('day'),
        selectedDateFormat: 'YYYY-MM-DD',
        selectedItemWidth: 140,
        showOffDays: true,
        showStartOfWeek: true,
        size: undefined,
        startOfWeek: 'Mon',
        startOfWeekFormat: 'ddd',
        squareEdges: false,
        text: 'ddd<br/>Do',
        textSelected: 'dddd<br/>Do, MMMM YYYY',
        width: 0,
        startDate: moment(new Date(-8640000000000000)),
        startDateFormat: 'YYYY-MM-DD',
        endDate: moment(new Date(8640000000000000)),
        endDateFormat: 'YYYY-MM-DD'
    };

    RoyalViewer.prototype = {

        setSelectedDate: function(date, format) {
            this._setSelectedDate(moment(date, format ? format : this.options.selectedDateFormat));
            this._render();
        },

        remove: function() {

            // Cleanup dom and remove events
            this._destroy();

            // Only remove data if user initiated
            $.removeData(this, 'plugin_' + pluginName);
        },

        _init: function(options) {

            this.options = $.extend({}, RoyalViewer.defaults, options);

            // If no width provided, default to fill full width
            // this.options.width = this.options.width ? this.options.width : this.$element.width();
            if (this.options.width) {
                this.options.fillWidth = false;
            }
            else {
                this.options.width = this.$element.width();
                this.options.fillWidth = true;
            }

            // Parse and set start and end dates
            if (typeof this.options.startDate === 'string') {
                this.options.startDate = moment(this.options.startDate, this.options.startDateFormat).clone().startOf('day');
            }
            if (typeof this.options.endDate === 'string') {
                this.options.endDate = moment(this.options.endDate, this.options.endDateFormat).clone().startOf('day');
            }

            // Parse, set and validate the initially selected date
            // 1. overridding the default value of today
            if (typeof this.options.selectedDate === 'string') {
                this.options.selectedDate = moment(this.options.selectedDate, this.options.selectedDateFormat).clone().startOf('day');
            }
            // 2. enforce selectedDate with in startDate and endDate range
            if (this.options.selectedDate.isBefore(this.options.startDate)) {
                this.options.selectedDate = this.options.startDate.clone();
            }
            if (this.options.selectedDate.isAfter(this.options.endDate)) {
                this.options.selectedDate = this.options.endDate.clone();
            }

            // Parse and nomalize size options
            if (this.options.size === 'small') {
                this.options.size = 'sm';
            }
            else if (this.options.size === 'large') {
                this.options.size = 'lg';
            }

            this._destroy();
            this._subscribeEvents();
            this._render();
        },

        _unsubscribeEvents: function() {

            // $(window).off(); // TODO Turns off all resize events not just the one being destroyed
            this.$element.off('click');
            this.$element.off('selectedDateChanged');
        },

        _subscribeEvents: function() {

            this._unsubscribeEvents();

            this.$element.on('click', $.proxy(this._clickedHandler, this));

            if (typeof (this.options.onSelectedDateChanged) === 'function') {
                this.$element.on('selectedDateChanged', this.options.onSelectedDateChanged);
            }

            /*if (this.options.fillWidth) {
                $(window).on('resize', $.proxy(this._resize, this));
            }*/
        },

        _destroy: function() {

            if (this.initialized) {

                // Cleanup dom
                this.$wrapper.remove();
                this.$wrapper = null;
                // this.$element.remove();

                // Switch off events
                this._unsubscribeEvents();
            }

            // Reset flag
            this.initialized = false;
        },

        _clickedHandler: function(event) {
            event.preventDefault();
            var target = $(event.target);
            var classList = target.attr('class');
            /*if (classList.indexOf('dp-nav-left') != -1) {
                this._back();
            }
            else if (classList.indexOf('dp-nav-right') != -1) {
                this._forward();
            }
            else*/ if (classList.indexOf('dp-item') != -1) {
                this._select(target.attr('data-moment'));
            }
        },

        _setSelectedDate: function(selectedDate) {

            if ((!selectedDate.isSame(this.options.selectedDate)) &&
                (!selectedDate.isBefore(this.options.startDate)) &&
                (!selectedDate.isAfter(this.options.endDate))) {
                this.options.selectedDate = selectedDate.startOf('day');
                this.$element.trigger('selectedDateChanged', [selectedDate.clone()]);
            }
        },

        /*_back: function() {
            this._setSelectedDate(this.options.selectedDate.clone().subtract('day', 1));
            this._render();
        },

        _forward: function() {
            this._setSelectedDate(this.options.selectedDate.clone().add('day', 1));
            this._render();
        },*/

        _select: function(date) {
            //alert(date);
            this._setSelectedDate(moment(date, this.options.selectedDateFormat));
            //this._render();
        },

        /*_resize: function() {
            this.options.width = this.$element.width();
            this._render();
        },*/

        _render: function() {

            var self = this;
            this.$element
                .removeClass('datepaginator datepaginator-sm datepaginator-lg')
                .addClass(this.options.size === 'sm' ? 'datepaginator-sm' : this.options.size === 'lg' ? 'datepaginator-lg' : 'datepaginator');

            ///////////////
            var today = moment().startOf('day'),
                start = this.options.startDate,
                end = this.options.endDate,
                realStart = moment(start, 'YYYY-MM-DD').startOf('month'),
                ralEnd = moment(end, 'YYYY-MM-DD').endOf('month');

            /*var data = {
                isSelectedStartDate: this.options.selectedDate.isSame(this.options.startDate) ? true : false,
                isSelectedEndDate: this.options.selectedDate.isSame(this.options.endDate) ? true : false,
                items: []
            };*/

            var html = '';
            var htmlMonth = '';
            //moment(item.m, 'YYYY-MM-DD').date()
            //alert(start);
            //alert(moment(start, 'YYYY-MM-DD').startOf('month'));
            //alert(moment(end, 'YYYY-MM-DD').endOf('month'));
            //alert(moment(end, 'YYYY-MM-DD').endOf('month').format('YYYY-MM-DD hh:mm'));

            for (var m = realStart; m.isSameOrBefore(ralEnd); m.add(1, 'days')) {
                if (m.date() == '1') {
                    //alert('start: ' + m.format(this.options.hint));
                    html =
                        '<p style="margin: 0 !important;">' + moment(m).format('MMMM YYYY') + '</p>' +
                        '<ul class="pagination" style="margin: 0 !important;">';
                }

                var valid = (m.isSameOrAfter(this.options.startDate)) && (m.isSameOrBefore(this.options.endDate)) ? true : false;

                html +=
                '<li>' +
                    '<a ' +
                        'class="dp-item ' +
                            (m.isSame(today) ? ' dp-today ' : '') +
                            //(this.options.startOfWeek.split(',').indexOf(m.format(this.options.startOfWeekFormat)) !== -1 ? ' dp-divider ' : '') +
                            (this.options.offDays.split(',').indexOf(m.format(this.options.offDaysFormat)) !== -1 ? ' dp-off ' : '') +
                            (valid ? '' : ' dp-no-select ') +
                        '" ' +
                        'title="' + m.format(this.options.hint) + '" ' +
                        'data-moment="' + m.clone().format(this.options.selectedDateFormat) + '" ' +
                //'data-container="body" data-trigger="hover" data-placement="bottom" data-content="Popover body goes here! Popover body goes here!" data-original-title="Popover in bottom" ' +
                        //'width="' + Math.floor(this.options.selectedItemWidth) + '" ' +
                    '>' + m.format(this.options.text) + '</a>' +
                '</li>';

                /*data.items[data.items.length] = {
                    m: m.clone().format(this.options.selectedDateFormat),
                    isValid: valid,
                    isSelected: (m.isSame(this.options.selectedDate)) ? true : false,
                    isToday: (m.isSame(today)) ? true : false,
                    isOffDay: (this.options.offDays.split(',').indexOf(m.format(this.options.offDaysFormat)) !== -1) ? true : false,
                    isStartOfWeek: (this.options.startOfWeek.split(',').indexOf(m.format(this.options.startOfWeekFormat)) !== -1) ? true : false,
                    text: (m.isSame(this.options.selectedDate)) ? m.format(this.options.textSelected) : m.format(this.options.text),
                    hint: valid ? m.format(this.options.hint) : 'Invalid date',
                    itemWidth: (m.isSame(this.options.selectedDate)) ? adjustedSelectedItemWidth : adjustedItemWidth
                };*/
                if (m.date() == moment(m, "YYYY-MM").daysInMonth()) {
                    //alert('end: ' + m.format(this.options.hint));
                    html += '</ul><br>';
                    this.$element.append(html);
                }

                /*$('[data-moment="' + m.clone().format(this.options.selectedDateFormat) + '"]').popover({
                    trigger: 'click | hover | focus',
                    placement: 'top',
                    delay: { show: 500, hide: 100 }
                });*/

                $('.dp-item').popover({
                    trigger: 'click | hover | focus',
                    placement: 'top',
                    delay: { show: 500, hide: 100 },
                    html: true,
                    content: function() {
                        var unique = Date.now();
                        var html =
                            '<ul class="nav nav-tabs">'+
                                '<li class="active">'+
                                    '<a href="#tab_1_' + unique + '" data-toggle="tab"> Price </a>'+
                                '</li>'+
                                '<li>'+
                                    '<a href="#tab_2_' + unique + '" data-toggle="tab"> England </a>'+
                                '</li>'+
                            '</ul>'+
                            '<div class="tab-content">'+
                                '<div class="tab-pane fade active in" id="tab_1_' + unique + '">'+
                                    '<div class="table-responsive">'+
                                        '<table class="table table-striped table-hover" style="margin-bottom: 0;">'+
                                            '<tbody>'+
                                                '<tr>'+
                                                    '<td> 1 </td>'+
                                                    '<td> Mark </td>'+
                                                    '<td> Otto </td>'+
                                                    '<td> makr124 </td>'+
                                                    '<td>'+
                                                        '<span class="label label-sm label-success"> Approved </span>'+
                                                    '</td>'+
                                                '</tr>'+
                                                '<tr>'+
                                                    '<td> 2 </td>'+
                                                    '<td> Jacob </td>'+
                                                    '<td> Nilson </td>'+
                                                    '<td> jac123 </td>'+
                                                    '<td>'+
                                                        '<span class="label label-sm label-info"> Pending </span>'+
                                                    '</td>'+
                                                '</tr>'+
                                                '<tr>'+
                                                    '<td> 3 </td>'+
                                                    '<td> Larry </td>'+
                                                    '<td> Cooper </td>'+
                                                    '<td> lar </td>'+
                                                    '<td>'+
                                                        '<span class="label label-sm label-warning"> Suspended </span>'+
                                                    '</td>'+
                                                '</tr>'+
                                                '<tr>'+
                                                    '<td> 4 </td>'+
                                                    '<td> Sandy </td>'+
                                                    '<td> Lim </td>'+
                                                    '<td> sanlim </td>'+
                                                    '<td>'+
                                                        '<span class="label label-sm label-danger"> Blocked </span>'+
                                                    '</td>'+
                                                '</tr>'+
                                            '</tbody>'+
                                        '</table>'+
                                    '</div>'+
                            '</div>'+
                            '<div class="tab-pane fade" id="tab_2_' + unique + '">'+
                                '<div class="table-responsive">'+
                                    '<table class="table table-striped table-hover" style="margin-bottom: 0;">'+
                                        '<tbody>'+
                                            '<tr>'+
                                                '<td> 1 </td>'+
                                                '<td> Mark2 </td>'+
                                                '<td> Otto2 </td>'+
                                                '<td> makr1242 </td>'+
                                                '<td>'+
                                                    '<span class="label label-sm label-info"> Pending </span>'+
                                                '</td>'+
                                            '</tr>'+
                                            '<tr>'+
                                                '<td> 2 </td>'+
                                                '<td> Jacob2 </td>'+
                                                '<td> Nilson2 </td>'+
                                                '<td> jac1232 </td>'+
                                                '<td>'+
                                                    '<span class="label label-sm label-success"> Approved </span>'+
                                                '</td>'+
                                            '</tr>'+
                                            '<tr>'+
                                                '<td> 3 </td>'+
                                                '<td> Larry2 </td>'+
                                                '<td> Cooper2 </td>'+
                                                '<td> lar2 </td>'+
                                                '<td>'+
                                                    '<span class="label label-sm label-danger"> Blocked </span>'+
                                                '</td>'+
                                            '</tr>'+
                                            '<tr>'+
                                                '<td> 4 </td>'+
                                                '<td> Sandy2 </td>'+
                                                '<td> Lim2 </td>'+
                                                '<td> sanlim2 </td>'+
                                                '<td>'+
                                                    '<span class="label label-sm label-warning"> Suspended </span>'+
                                                '</td>'+
                                            '</tr>'+
                                        '</tbody>'+
                                    '</table>'+
                                '</div>'+
                            '</div>';
                        return html;
                    }
                });

                //if
                //this.$element.append(html);
            }
            ///////////////////

            //var html = '<ul class="pagination"><li><a href="#" class="dp-item dp-divider" data-moment="2018-01-01" title="Monday, 1st January 2018" style="width: 29px;">Mon<br>1st</a></li><li><a href="#" class="dp-item" data-moment="2018-01-02" title="Tuesday, 2nd January 2018" style="width: 29px;">Tue<br>2nd</a></li><li><a href="#" class="dp-item" data-moment="2018-01-03" title="Wednesday, 3rd January 2018" style="width: 29px;">Wed<br>3rd</a></li><li><a href="#" class="dp-item" data-moment="2018-01-04" title="Thursday, 4th January 2018" style="width: 29px;">Thu<br>4th</a></li><li><a href="#" class="dp-item" data-moment="2018-01-05" title="Friday, 5th January 2018" style="width: 29px;">Fri<br>5th</a></li><li><a href="#" class="dp-item dp-off" data-moment="2018-01-06" title="Saturday, 6th January 2018" style="width: 29px;">Sat<br>6th</a></li><li><a href="#" class="dp-item dp-off" data-moment="2018-01-07" title="Sunday, 7th January 2018" style="width: 29px;">Sun<br>7th</a></li><li><a href="#" class="dp-item dp-divider" data-moment="2018-01-08" title="Monday, 8th January 2018" style="width: 29px;">Mon<br>8th</a></li><li><a href="#" class="dp-item" data-moment="2018-01-09" title="Tuesday, 9th January 2018" style="width: 29px;">Tue<br>9th</a></li><li><a href="#" class="dp-item" data-moment="2018-01-10" title="Wednesday, 10th January 2018" style="width: 29px;">Wed<br>10th</a></li><li><a href="#" class="dp-item" data-moment="2018-01-11" title="Thursday, 11th January 2018" style="width: 29px;">Thu<br>11th</a></li><li><a href="#" class="dp-item" data-moment="2018-01-12" title="Friday, 12th January 2018" style="width: 29px;">Fri<br>12th</a></li><li><a href="#" class="dp-item dp-off" data-moment="2018-01-13" title="Saturday, 13th January 2018" style="width: 29px;">Sat<br>13th</a></li><li><a href="#" class="dp-item dp-off" data-moment="2018-01-14" title="Sunday, 14th January 2018" style="width: 29px;">Sun<br>14th</a></li><li><a href="#" class="dp-item dp-divider" data-moment="2018-01-15" title="Monday, 15th January 2018" style="width: 29px;">Mon<br>15th</a></li><li><a href="#" class="dp-item" data-moment="2018-01-16" title="Tuesday, 16th January 2018" style="width: 29px;">Tue<br>16th</a></li><li><a href="#" class="dp-item" data-moment="2018-01-17" title="Wednesday, 17th January 2018" style="width: 29px;">Wed<br>17th</a></li><li><a href="#" class="dp-item" data-moment="2018-01-18" title="Thursday, 18th January 2018" style="width: 29px;">Thu<br>18th</a></li><li><a href="#" class="dp-item" data-moment="2018-01-19" title="Friday, 19th January 2018" style="width: 29px;">Fri<br>19th</a></li><li><a href="#" class="dp-item dp-off" data-moment="2018-01-20" title="Saturday, 20th January 2018" style="width: 29px;">Sat<br>20th</a></li><li><a href="#" class="dp-item dp-off" data-moment="2018-01-21" title="Sunday, 21st January 2018" style="width: 29px;">Sun<br>21st</a></li><li><a href="#" class="dp-item dp-divider" data-moment="2018-01-22" title="Monday, 22nd January 2018" style="width: 29px;">Mon<br>22nd</a></li><li><a href="#" class="dp-item" data-moment="2018-01-23" title="Tuesday, 23rd January 2018" style="width: 29px;">Tue<br>23rd</a></li><li><a href="#" class="dp-item" data-moment="2018-01-24" title="Wednesday, 24th January 2018" style="width: 29px;">Wed<br>24th</a></li><li><a href="#" class="dp-item" data-moment="2018-01-25" title="Thursday, 25th January 2018" style="width: 29px;">Thu<br>25th</a></li><li><a href="#" class="dp-item" data-moment="2018-01-26" title="Friday, 26th January 2018" style="width: 29px;">Fri<br>26th</a></li><li><a href="#" class="dp-item dp-off" data-moment="2018-01-27" title="Saturday, 27th January 2018" style="width: 29px;">Sat<br>27th</a></li><li><a href="#" class="dp-item dp-off" data-moment="2018-01-28" title="Sunday, 28th January 2018" style="width: 29px;">Sun<br>28th</a></li><li><a href="#" class="dp-item dp-divider" data-moment="2018-01-29" title="Monday, 29th January 2018" style="width: 29px;">Mon<br>29th</a></li><li><a href="#" class="dp-item" data-moment="2018-01-30" title="Tuesday, 30th January 2018" style="width: 29px;">Tue<br>30th</a></li><li><a href="#" class="dp-item" data-moment="2018-01-31" title="Wednesday, 31st January 2018" style="width: 29px;">Wed<br>31st</a></li></ul>';
            //var html1 = '<ul class="pagination"><li><a href="#" class="dp-item dp-divider" data-moment="2018-01-01" title="Monday, 1st January 2018" style="width: 29px;">Mon<br>1st</a></li></ul>'

            //this.$element.append(html);
            this.$element.append('<br>');
            //this.$element.append(html1);

            /*if (!this.initialized) {

                // Setup first time only components, reused on later _renders
                this.$element
                    .removeClass('datepaginator datepaginator-sm datepaginator-lg')
                    .addClass(this.options.size === 'sm' ? 'datepaginator-sm' : this.options.size === 'lg' ? 'datepaginator-lg' : 'datepaginator');
                this.$wrapper = $(this._template.list);
                this.$leftNav = $(this._template.navItem)
                    .addClass('dp-nav-left')
                    .addClass(this.options.size === 'sm' ? 'dp-nav-sm' : this.options.size === 'lg' ? 'dp-nav-lg' : '')
                    .addClass(this.options.squareEdges ? 'dp-nav-square-edges' : '')
                    .append($(this._template.icon)
                        .addClass('glyphicon-chevron-left')
                        .addClass('dp-nav-left'))
                    .width(this.options.navItemWidth);
                this.$rightNav = $(this._template.navItem)
                    .addClass('dp-nav-right')
                    .addClass(this.options.size === 'sm' ? 'dp-nav-sm' : this.options.size === 'lg' ? 'dp-nav-lg' : '')
                    .addClass(this.options.squareEdges ? 'dp-nav-square-edges' : '')
                    .append($(this._template.icon)
                        .addClass('glyphicon-chevron-right')
                        .addClass('dp-nav-right'))
                    .width(this.options.navItemWidth);
                this._injectStyle();
                this.initialized = true;
            }

            // Get data then string together DOM elements
            var data = this._buildData();
            this.$element.empty().append(this.$wrapper.empty());

            // Left nav
            this.$leftNav
                .removeClass('dp-no-select')
                .attr('title', '');
            if (data.isSelectedStartDate) {
                this.$leftNav
                    .addClass('dp-no-select')
                    .attr('title', 'Start of valid date range');
            }
            this.$wrapper.append($(self._template.listItem).append(this.$leftNav));

            // Items
            $.each(data.items, function(id, item) {
                var $a = $(self._template.dateItem)
                    .attr('data-moment', item.m)
                    .attr('title', item.hint)
                    .width(item.itemWidth);

                if (item.isSelected && self.options.highlightSelectedDate) {
                    $a.addClass('dp-selected');
                }
                if (item.isToday && self.options.highlightToday) {
                    $a.addClass('dp-today');
                }
                if (item.isStartOfWeek && self.options.showStartOfWeek) {
                    $a.addClass('dp-divider');
                }
                if (item.isOffDay && self.options.showOffDays) {
                    $a.addClass('dp-off');
                }
                if (self.options.size === 'sm') {
                    $a.addClass('dp-item-sm');
                }
                else if (self.options.size === 'lg') {
                    $a.addClass('dp-item-lg');
                }
                if (!item.isValid) {
                    $a.addClass('dp-no-select');
                }
                $a.append(item.text);


                self.$wrapper.append($(self._template.listItem).append($a));

                //if(moment(item.m, 'YYYY-MM-DD').date() == moment(item.m, "YYYY-MM").daysInMonth()){
                //    self.$wrapper.css('color', 'green');
                    //this.parent.append('<br>123');
                //}
            });

            // Right nav
            this.$rightNav
                .removeClass('dp-no-select')
                .attr('title', '');
            if (data.isSelectedEndDate) {
                this.$rightNav
                    .addClass('dp-no-select')
                    .attr('title', 'End of valid date range');
            }
            this.$wrapper.append($(self._template.listItem).append(this.$rightNav));*/
        },

        _injectStyle: function() {
            // Make sure we only add it once
            if (this.options.injectStyle && !document.getElementById('bootstrap-datepaginator-style')) {
                $('<style type="text/css" id="bootstrap-datepaginator-style">' + this._css + '</style>').appendTo('head');
            }
        },

        _buildData: function() {

            /*var asd = moment(this.options.startDate, "YYYY-MM").daysInMonth();
            var ttt = moment(this.options.startDate).isSame(this.options.endDate, 'month'); //true if dates are in the same month
            alert(ttt);*/

//alert(moment('2014-07-28', 'YYYY/MM/DD').date());


            var viewWidth = (this.options.width - ((this.options.selectedItemWidth - this.options.itemWidth) + (this.options.navItemWidth * 2))),
                //units = Math.floor(viewWidth / this.options.itemWidth),
                units = Math.floor(moment(this.options.startDate, "YYYY-MM").daysInMonth()),
                unitsPerSide = parseInt(units / 2),
                adjustedItemWidth = Math.floor(viewWidth / units),
                adjustedSelectedItemWidth = Math.floor(this.options.selectedItemWidth + (viewWidth - (units * adjustedItemWidth))),
                today = moment().startOf('day'),
                //start = this.options.selectedDate.clone().subtract('days', unitsPerSide),
                //end = this.options.selectedDate.clone().add('days', (units - unitsPerSide));

                start = this.options.startDate,
                end = this.options.endDate;
            //alert (start);
            var data = {
                isSelectedStartDate: this.options.selectedDate.isSame(this.options.startDate) ? true : false,
                isSelectedEndDate: this.options.selectedDate.isSame(this.options.endDate) ? true : false,
                items: []
            };

            for (var m = start; m.isSameOrBefore(end); m.add('days', 1)) {

                var valid = ((m.isSame(this.options.startDate) || m.isAfter(this.options.startDate)) &&
                (m.isSame(this.options.endDate) || m.isBefore(this.options.endDate))) ? true : false;

                data.items[data.items.length] = {
                    m: m.clone().format(this.options.selectedDateFormat),
                    isValid: valid,
                    isSelected: (m.isSame(this.options.selectedDate)) ? true : false,
                    isToday: (m.isSame(today)) ? true : false,
                    isOffDay: (this.options.offDays.split(',').indexOf(m.format(this.options.offDaysFormat)) !== -1) ? true : false,
                    isStartOfWeek: (this.options.startOfWeek.split(',').indexOf(m.format(this.options.startOfWeekFormat)) !== -1) ? true : false,
                    text: (m.isSame(this.options.selectedDate)) ? m.format(this.options.textSelected) : m.format(this.options.text),
                    hint: valid ? m.format(this.options.hint) : 'Invalid date',
                    itemWidth: (m.isSame(this.options.selectedDate)) ? adjustedSelectedItemWidth : adjustedItemWidth
                };
            }

            return data;
        },

        _template: {
            list: '<ul class="pagination"></ul>',
            listItem: '<li></li>',
            //navItem: '<a href="#" class="dp-nav"></a>',
            dateItem: '<a href="#" class="dp-item"></a>',
            icon: '<i class="glyphicon"></i>'
        },

        _css: '.datepaginator{font-size:12px;/*height:60px*/}.datepaginator-sm{font-size:10px;height:40px}.datepaginator-lg{font-size:14px;height:80px}.pagination{margin:0;padding:0;white-space:nowrap}.dp-nav{height:60px;padding:22px 0!important;width:20px;margin:0!important;text-align:center}.dp-nav-square-edges{border-radius:0!important}.dp-item{height:60px;padding:13px 0!important;width:35px;margin:0!important;border-left-style:hidden!important;text-align:center}.dp-item-sm{height:40px!important;padding:5px!important}.dp-item-lg{height:80px!important;padding:22px 0!important}.dp-nav-sm{height:40px!important;padding:11px 0!important}.dp-nav-lg{height:80px!important;padding:33px 0!important}a.dp-nav-right{border-left-style:hidden!important}.dp-divider{border-left:2px solid #ddd!important}.dp-off{background-color:#F0F0F0!important}.dp-no-select{color:#ccc!important;background-color:#F0F0F0!important}.dp-no-select:hover{background-color:#F0F0F0!important}.dp-today{background-color:#88B5DB!important;color:#fff!important}.dp-selected{background-color:#428bca!important;color:#fff!important;width:140px}#dp-calendar{padding:3px 5px 0 0!important;margin-right:3px;position:absolute;right:0;top:10}'
    };

    var logError = function(message) {
        if(window.console) {
            window.console.error(message);
        }
    };

    // Prevent against multiple instantiations,
    // handle updates and method calls
    $.fn[pluginName] = function(options, args) {
        return this.each(function() {
            var self = $.data(this, 'plugin_' + pluginName);
            if (typeof options === 'string') {
                if (!self) {
                    logError('Not initialized, can not call method : ' + options);
                }
                else if (!$.isFunction(self[options]) || options.charAt(0) === '_') {
                    logError('No such method : ' + options);
                }
                else {
                    if (typeof args === 'string') {
                        args = [args];
                    }
                    self[options].apply(self, args);
                }
            }
            else {
                if (!self) {
                    $.data(this, 'plugin_' + pluginName, new RoyalViewer(this, options));
                }
                else {
                    self._init(options);
                }
            }
        });
    };

})(jQuery, window, document);