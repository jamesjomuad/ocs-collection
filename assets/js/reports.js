'use strict';

//ChartJS Util
(function(global) {
    global.chartColors = {
        red: '#e23131',
        orange: '#ff9f40',
        yellow: '#ffe056',
        green: '#2cdd80',
        blue: '#36a2eb',
        purple: '#9966ff',
        grey: '#9a9b9c'
    };

	var MONTHS = [
		'January',
		'February',
		'March',
		'April',
		'May',
		'June',
		'July',
		'August',
		'September',
		'October',
		'November',
		'December'
	];

	var COLORS = [
		'#4dc9f6',
		'#f67019',
		'#f53794',
		'#537bc4',
		'#acc236',
		'#166a8f',
		'#00a950',
		'#58595b',
		'#8549ba'
	];

	var Samples = global.Samples || (global.Samples = {});
	var Color = global.Color;

	Samples.utils = {
		// Adapted from http://indiegamr.com/generate-repeatable-random-numbers-in-js/
		srand: function(seed) {
			this._seed = seed;
		},

		rand: function(min, max) {
			var seed = this._seed;
			min = min === undefined ? 0 : min;
			max = max === undefined ? 1 : max;
			this._seed = (seed * 9301 + 49297) % 233280;
			return min + (this._seed / 233280) * (max - min);
		},

		numbers: function(config) {
			var cfg = config || {};
			var min = cfg.min || 0;
			var max = cfg.max || 1;
			var from = cfg.from || [];
			var count = cfg.count || 8;
			var decimals = cfg.decimals || 8;
			var continuity = cfg.continuity || 1;
			var dfactor = Math.pow(10, decimals) || 0;
			var data = [];
			var i, value;

			for (i = 0; i < count; ++i) {
				value = (from[i] || 0) + this.rand(min, max);
				if (this.rand() <= continuity) {
					data.push(Math.round(dfactor * value) / dfactor);
				} else {
					data.push(null);
				}
			}

			return data;
		},

		labels: function(config) {
			var cfg = config || {};
			var min = cfg.min || 0;
			var max = cfg.max || 100;
			var count = cfg.count || 8;
			var step = (max - min) / count;
			var decimals = cfg.decimals || 8;
			var dfactor = Math.pow(10, decimals) || 0;
			var prefix = cfg.prefix || '';
			var values = [];
			var i;

			for (i = min; i < max; i += step) {
				values.push(prefix + Math.round(dfactor * i) / dfactor);
			}

			return values;
		},

		months: function(config) {
			var cfg = config || {};
			var count = cfg.count || 12;
			var section = cfg.section;
			var values = [];
			var i, value;

			for (i = 0; i < count; ++i) {
				value = MONTHS[Math.ceil(i) % 12];
				values.push(value.substring(0, section));
			}

			return values;
		},

		color: function(index) {
			return COLORS[index % COLORS.length];
		},

		transparentize: function(color, opacity) {
			var alpha = opacity === undefined ? 0.5 : 1 - opacity;
			return Color(color).alpha(alpha).rgbString();
		}
	};

	// DEPRECATED
	window.randomScalingFactor = function() {
		return Math.round(Samples.utils.rand(-100, 100));
	};

	// INITIALIZATION
	Samples.utils.srand(Date.now());

}(this));

// Dropdown extra
$(document).on('ready',function(){
    $('.dropdown-menu a').on('click',function(e){
		e.preventDefault();
		var self = $(this);
		var $dropdown = $(this).parents('.dropdown');
		var $anchors = $dropdown.find('a');
        var $label = $(this).text();
        var $btn = $dropdown.find('[data-toggle="dropdown"]');
		$btn.text($label);
		setTimeout(function(){ 
			$anchors.removeClass('active');
			self.addClass('active');
		}, 500);
    });
});

// Chart: Payment Counts
$(document).on('ready',function(){
	var config = {
		type: 'line',
		data: {
			labels: [],
			datasets: [
				{
					label: 'Daily',
					backgroundColor: window.chartColors.grey,
					borderColor: window.chartColors.orange,
					data: [],
					fill: false,
				}
			]
		},
		options: {
			responsive: true,
			title: {
				display: true,
				text: 'Payment Counts'
			},
			tooltips: {
				mode: 'index',
				intersect: false,
			},
			hover: {
				mode: 'nearest',
				intersect: true
			},
			scales: {
				xAxes: [{
					display: true,
					scaleLabel: {
						display: true,
						labelString: 'Days'
					}
				}],
				yAxes: [{
					display: true,
					scaleLabel: {
						display: true,
						labelString: 'Counts'
					}
				}]
			}
		}
	};
    var payCount = document.getElementById('payment-count').getContext('2d');
	window.ctxPayCount = new Chart(payCount, config);
	var request = function(mode){
		$.request('onGetPaymentCount',{ data: { mode: mode } })
		.success(function(res){
			config.data.labels = res.label
			config.data.datasets[0].data = res.value
			config.data.datasets[0].label = mode
			if(mode=='daily'){
				config.options.scales.xAxes[0].scaleLabel.labelString = 'Days'
			}else if(mode=='weekly'){
				config.options.scales.xAxes[0].scaleLabel.labelString = 'Weeks'
			}else if(mode=='monthly'){
				config.options.scales.xAxes[0].scaleLabel.labelString = 'Months'
			}
			ctxPayCount.update()
		});
	}
	
	request('daily');

	$('#chart-payment-count .mode li a:not(".active")').on('click',function(e){
		e.preventDefault()
		if($(this).is('.active')){return;}
		var mode = $(this).data('mode')
		request(mode);
	});
})

// Chart: Payment Amounts
$(document).on('ready',function(){
	var config = {
		type: 'line',
		data: {
			labels: [],
			datasets: [
				{
					label: 'Daily',
					backgroundColor: window.chartColors.grey,
					borderColor: window.chartColors.orange,
					data: [],
					fill: false,
				}
			]
		},
		options: {
			responsive: true,
			title: {
				display: true,
				text: 'Payment Amounts'
			},
			tooltips: {
				mode: 'index',
				intersect: false,
			},
			hover: {
				mode: 'nearest',
				intersect: true
			},
			scales: {
				xAxes: [{
					display: true,
					scaleLabel: {
						display: true,
						labelString: 'Days'
					}
				}],
				yAxes: [{
					display: true,
					scaleLabel: {
						display: true,
						labelString: 'Amounts'
					}
				}]
			}
		}
	};
    var payAmount = document.getElementById('payment-amount').getContext('2d');
	window.ctxPayAmount = new Chart(payAmount, config);
	var request = function(mode){
		$.request('onGetPaymentAmount',{ data: { mode: mode } })
		.success(function(res){
			config.data.labels = res.label
			config.data.datasets[0].data = res.value
			config.data.datasets[0].label = mode
			if(mode=='daily'){
				config.options.scales.xAxes[0].scaleLabel.labelString = 'Days'
			}else if(mode=='weekly'){
				config.options.scales.xAxes[0].scaleLabel.labelString = 'Weeks'
			}else if(mode=='monthly'){
				config.options.scales.xAxes[0].scaleLabel.labelString = 'Months'
			}
			ctxPayAmount.update()
		});
	}
	
	request('daily');

	$('#chart-payment-amount .mode li a:not(".active")').on('click',function(e){
		e.preventDefault()
		if($(this).is('.active')){return;}
		var mode = $(this).data('mode')
		request(mode);
	});
})

// Chart: Debt Counts
$(document).on('ready',function(){
	var config = {
		type: 'line',
		data: {
			labels: [],
			datasets: [
				{
					label: 'Daily',
					backgroundColor: window.chartColors.grey,
					borderColor: window.chartColors.orange,
					data: [],
					fill: false,
				}
			]
		},
		options: {
			responsive: true,
			title: {
				display: true,
				text: 'Debt Counts'
			},
			tooltips: {
				mode: 'index',
				intersect: false,
			},
			hover: {
				mode: 'nearest',
				intersect: true
			},
			scales: {
				xAxes: [{
					display: true,
					scaleLabel: {
						display: true,
						labelString: 'Days'
					}
				}],
				yAxes: [{
					display: true,
					scaleLabel: {
						display: true,
						labelString: 'Counts'
					}
				}]
			}
		}
	};
    var payCount = document.getElementById('debt-count').getContext('2d');
	window.ctxDebtCount = new Chart(payCount, config);
	var request = function(mode){
		$.request('onGetDebtCount',{ data: { mode: mode } })
		.success(function(res){
			config.data.labels = res.label
			config.data.datasets[0].data = res.value
			config.data.datasets[0].label = mode
			if(mode=='daily'){
				config.options.scales.xAxes[0].scaleLabel.labelString = 'Days'
			}else if(mode=='weekly'){
				config.options.scales.xAxes[0].scaleLabel.labelString = 'Weeks'
			}else if(mode=='monthly'){
				config.options.scales.xAxes[0].scaleLabel.labelString = 'Months'
			}
			ctxDebtCount.update()
		});
	}
	
	request('daily');

	$('#chart-debt-count .mode li a:not(".active")').on('click',function(e){
		e.preventDefault()
		if($(this).is('.active')){return;}
		var mode = $(this).data('mode')
		request(mode);
	});
})

// Chart: Debt Amounts
$(document).on('ready',function(){
	var config = {
		type: 'line',
		data: {
			labels: [],
			datasets: [
				{
					label: 'Daily',
					backgroundColor: window.chartColors.grey,
					borderColor: window.chartColors.orange,
					data: [],
					fill: false,
				}
			]
		},
		options: {
			responsive: true,
			title: {
				display: true,
				text: 'Debt Amounts'
			},
			tooltips: {
				mode: 'index',
				intersect: false,
			},
			hover: {
				mode: 'nearest',
				intersect: true
			},
			scales: {
				xAxes: [{
					display: true,
					scaleLabel: {
						display: true,
						labelString: 'Days'
					}
				}],
				yAxes: [{
					display: true,
					scaleLabel: {
						display: true,
						labelString: 'Amounts'
					}
				}]
			}
		}
	};
    var payCount = document.getElementById('debt-amount').getContext('2d');
	window.ctxDebtamount = new Chart(payCount, config);
	var request = function(mode){
		$.request('onGetDebtAmount',{ data: { mode: mode } })
		.success(function(res){
			config.data.labels = res.label
			config.data.datasets[0].data = res.value
			config.data.datasets[0].label = mode
			if(mode=='daily'){
				config.options.scales.xAxes[0].scaleLabel.labelString = 'Days'
			}else if(mode=='weekly'){
				config.options.scales.xAxes[0].scaleLabel.labelString = 'Weeks'
			}else if(mode=='monthly'){
				config.options.scales.xAxes[0].scaleLabel.labelString = 'Months'
			}
			ctxDebtamount.update()
		});
	}
	
	request('daily');

	$('#chart-debt-amount .mode li a:not(".active")').on('click',function(e){
		e.preventDefault()
		if($(this).is('.active')){return;}

		var mode = $(this).data('mode')
		request(mode);
	});
})

// Pie Chart: Debt vs Payment
$(document).on('click','#debt-payment .mode li a',function(e){
	e.preventDefault()
	if($(this).is('.active')){return;}

	var mode = $(this).data('mode')
	$.request('onDebtvsPayment',{ data: { mode: mode } })
	// .success(function(res){
		
	// });
	console.log($(this))
});