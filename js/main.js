$('[data-toggle-switch]')
	.next()
	.append(
		'<i data-bs-tooltip="tooltip" data-bs-placement="top" title="Wybór tego elementu wpłynie na zawartość formularza" class=" px-1 fas fa-align-center"></i>',
	);
var ans = [];
var formId = $('body').attr('data-site-href');

if (location.protocol !== 'https:') {
	location.replace(`https:${location.href.substring(location.protocol.length)}`);
}

$('[data-show-id]').each(function () {
	var els = $(this).attr('data-show-id');
	if ($(this).data('show-id') != '') {
		if (!$(".toggle-show[data-toggle-id='" + els + "']").is(':checked')) {
			$(this).addClass('d-none');
		} else {
			$(this).closest('[data-actual]').attr('data-actual', els);
		}
	}
	if (els) {
		if (els.includes(',')) {
			var arra = els.split(',');
			arra.forEach(function (x) {
				if (!ans.includes(x)) {
					ans.push(x);
				}
			});
		} else {
			if (!ans.includes(els)) {
				ans.push(els);
			}
		}
	}
});

$(document).ready(function () {
	$.fn.select2.defaults.set('theme', 'bootstrap-5');
	$('.select').select2({
		matcher: function (params, data) {
			var original_matcher = $.fn.select2.defaults.defaults.matcher;
			var result = original_matcher(params, data);
			if (
				result &&
				data.children &&
				result.children &&
				data.children.length != result.children.length &&
				data.text.toLowerCase().includes(params.term.toLowerCase())
			) {
				result.children = data.children;
			}
			return result;
		},
		width: 'resolve',
	});
});

$('.string-title').each(function () {
	if ($(this).text().includes(' do')) {
		$(this).text($(this).text().replace(' do', ''));
		let value = $(this).closest('.string-parent').find('.string-value').text();
		$(this)
			.closest('.string-parent')
			.prev()
			.find('.string-value')
			.append(' do ' + value);
		$(this).closest('.string-parent').prev().find('.string-value').prepend('od ');
		$(this).closest('.string-parent').remove();
	}
	if ($(this).text().includes(' od')) {
		if ($(this).closest('.string-parent').next().find('.string-title').text().includes(' do')) {
			$(this).text($(this).text().replace(' od', ''));
		}
	}
});
$(document).on('click', "[data-hide-alert='true']", function () {
	$.cookie.json = true;
	$(this).closest('.custom-alert').removeClass('d-flex').hide();
	let id = $(this).closest('.custom-alert').attr('data-alert');
	var i = 0;
	arra = [];
	if ($.cookie('alert') == undefined) {
		arra.push(id);
		$.cookie('alert', arra);
	} else {
		$.cookie('alert').forEach(function (e) {
			if (e == id) {
				i = 1;
			} else {
				arra.push(e);
			}
		});

		if (i == 0) {
			arra.push(id);
			$.cookie('alert', arra);
		} else {
		}
	}
});

if ($.cookie('alert') != undefined) {
	let obj = JSON.parse($.cookie('alert'));
	obj.forEach(function (e) {
		if ($("div[data-alert='" + e + "']").length > 0) {
			$("div[data-alert='" + e + "']").remove();
		}
	});
}

$(document).ready(function () {
	if ($('input[type=tel]').length > 0) {
		var input = document.querySelector("input[type='tel']");
		var errorMsg = document.querySelector('#invalid-' + $(input).attr('id'));
		var errorMap = [
			'Nieprawidłowy numer',
			'Nieprawidłowy kod kraju',
			'Numer jest za krótki',
			'Numer jest za długi',
			'Numer jest nieprawidłowy',
		];
		iti = [];
		$("input[type='tel']").each(function () {
			var index = $(this).attr('data-index');
			iti[index] = window.intlTelInput(this, {
				preferredCountries: ['pl'],
				utilsScript: 'https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js',
				customContainer: 'input-group',
			});
		});
		$('.iti').removeClass('iti');
		$('.iti__flag-container').addClass('btn btn-outline-secondary');

		var reset = function () {
			input.classList.remove('error');
			errorMsg.innerHTML = '';
			$(errorMsg).hide();
		};

		// on blur: validate
		input.addEventListener('blur', function () {
			reset();
			if (input.value.trim()) {
				if (iti.isValidNumber()) {
				} else {
					input.classList.add('error');
					var errorCode = iti.getValidationError();
					errorMsg.innerHTML = errorMap[errorCode];
					$(errorMsg).show();
				}
			}
		});

		// on keyup / change flag: reset
		input.addEventListener('change', reset);
		input.addEventListener('keyup', reset);
	}
});

ans.forEach(function (x) {
	$("input[data-toggle-id='" + x + "']")
		.next()
		.append(
			"<i id='form-editor' class='ps-2 fas fa-stream' data-bs-tooltip='tooltip' data-bs-placement='top' data-moderate='TRUE' data-contentid='add-build' title='' data-bs-original-title='Zaznaczenie tej opcji wpłynie na dalszą zawartość formularza'></i>",
		);
});
var a = document.URL;

$("input[type=decimal][name$='from']").blur(function () {
	var find = $(this).attr('name').replace('from', '');

	var fvalue = $(this).val();
	var svalue = $('input[name=' + find + 'to]').val();

	if (fvalue > svalue && svalue && fvalue) {
		$(this).focusout();
		alert('Wprowadzone dane wydają się być nielogiczne, sprawdź ich poprawność  ');
	}
});

$("input[type=decimal][name$='to']").blur(function () {
	var find = $(this).attr('name').replace('to', '');
	var fvalue = $(this).val();

	var svalue = $('input[name=' + find + 'from]').val();

	if (svalue > fvalue && svalue && fvalue) {
		$(this).focusout();
		alert('Wprowadzone dane wydają się być nielogiczne, sprawdź ich poprawność ');
	}
});

$(document).ready(function () {
	var mask = Maska.create('.form-control');
});

$(document).on('change', '.toggle-show', function () {
	var last = $(this).closest('[data-actual]');

	var actual = $(this).data('toggle-id');
	if (!$(this).is(':checked')) {
		$('[data-show-id*=' + last.attr('data-actual') + ']:not(.d-none)').each(function () {
			$(this).addClass('d-none');
		});
	}

	if (last.attr('data-actual') == '') {
		$('[data-show-id*=' + actual + ']').removeClass('d-none');
	} else if (last.attr('data-actual') != actual) {
		$('[data-show-id*=' + last.attr('data-actual') + ']:not(.d-none)').each(function () {
			$(this).addClass('d-none');
		});

		$('.d-none[data-show-id*=' + actual + ']').each(function () {
			$(this)
				.find('.toggle-show')
				.each(function () {
					$(this).prop('checked', false);
				});
			$(this)
				.find('[data-actual]')
				.each(function () {
					$(this).attr('data-actual', '');
				});
			$(this)
				.find('.hide-temp')
				.each(function () {
					$(this).show();
				});
			$(this).removeClass('d-none');
		});
	}
	last.attr('data-actual', actual);
	if (!$(this).is(':checked')) {
	}
});

var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-tooltip="tooltip"]'));
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
	return new bootstrap.Tooltip(tooltipTriggerEl);
});

//
$('.data-table').DataTable({
	'paging': false,
});
function customAlert(color, title, value) {
	var alert = '';
	alert +=
		'<div class="alert alert-' + color + ' alert-dismissible fade show" role="alert" id="myAlert">';
	alert += '<strong> ' + title + ' </strong> ' + value;
	alert +=
		'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
	$('.alerts').append(alert);
}

count = 9;

$(window).on('scroll', function () {
	if ($('table:not(.data-table) tbody tr:eq(' + count + ')').length) {
		if ($(window).scrollTop() > $('tbody tr:eq(' + count + ')').offset().top) {
			//
			count = count + 1;
			$.ajax({
				type: 'POST',
				url: 'ajax/session-set.php',
				dataType: 'json',
				data: {
					href: $('body').attr('data-site-href'),
					title: $('#pageTitle').text(),
					count: count,
					id: $('tr:eq(' + (count * 1 - 1) + ')').attr('data-id'),
				},
				success: function (zawartosc) {
					if ($('.back-to').hasClass('d-none')) {
						$('.back-to').removeClass('d-none');
						customAlert(
							'success',
							'Możliwy powrót',
							'W panelu po lewej stronie pojawił się przycisk cofania, możesz nim wrócić do ostatnio przeszukiwanych pozycji',
						);
					} else {
						if (zawartosc['ans'] == 0) {
							customAlert(
								'success',
								'Możliwy kolejny powrót',
								'W panelu po lewej stronie pojawiła się kolejna pozycja do której możesz wrócić',
							);
						}
					}
				},
			});
		}
	}
});
var host = window.location.pathname.split('/');
$(function () {
	$('#sortable').sortable({
		placeholder: 'ui-state-highlight',
	});
	$('#sortable').disableSelection();
});

console.log('działa???');
$(document).on('click', '.print', function () {
	console.log('drukowanko');
	$(this)
		.closest('form')
		.find('.modal-body')
		.printThis({
			debug: true, // show the iframe for debugging
			importCSS: true, // import parent page css
			importStyle: false, // import style tags
			printContainer: false, // grab outer container as well as the contents of the selector
			loadCSS: ['cm/style/print.css', 'cm/style/lib/bootstrap-dark.css'], // path to additional css file - use an array [] for multiple
			pageTitle: 'UAS', // add title to print page
			removeInline: false, // remove all inline styles from print elements
			removeInlineSelector: 'body *', // custom selectors to filter inline styles. removeInline must be true
			printDelay: 333, // variable print delay
			header: null, // prefix to html
			footer: null, // postfix to html
			base: false, // preserve the BASE tag, or accept a string for the URL
			formValues: true, // preserve input/form values
			canvas: false, // copy canvas elements
			//doctypeString: '...', // enter a different doctype for older markup
			removeScripts: false, // remove script tags from print content
			copyTagClasses: false, // copy classes from the html & body tag
			beforePrintEvent: null, // callback function for printEvent in iframe
			beforePrint: null, // function called before iframe is filled
			afterPrint: null, // function called before iframe is removed
		});
});

// * * EVENT HANDLERS  * //
$(document).on('submit', 'form:not(.formReady)', function (e) {
	e.preventDefault();
	var form = $(this).closest('form');
	var i = 0;
	form.find('input.required').each(function () {
		if ($(this).val() == '') {
			if (i == 0) {
				$(this).focus();
				i++;
			}
			$(this)
				.closest('.input-content')
				.find('.invalid-feedback')
				.text('To pole nie może pozostać puste !');
			$(this).closest('.input-content').find('.invalid-feedback').show();
		} else {
			$(this).closest('.input-content').find('.invalid-feedback').hide();
			$(this).closest('.input-content').find('.valid-feedback').text('Pole poprawne !');
			$(this).closest('.input-content').find('.valid-feedback').show();
		}
	});
	let a = 0;
	form
		.find('[data-show-one=1]')
		.not('.d-none')
		.each(function () {
			a++;
		});
	if (a > 1) {
		$('.invalid-only-one').text('Chociaż jedna wartość musi pochodzić z twojego konta');
		$('.invalid-only-one').show();
	}
	if ($('.invalid-feedback').is(':visible')) {
	} else {
		if (form.find('input[type=tel]:visible').length > 0) {
			$('input[type=tel]:visible').each(function () {
				var index = $(this).attr('data-index');
				console.log('------------------------', index);
				console.log(iti);
				$(this).val(iti[index].getNumber());
			});
		}
		form.find('.d-none').each(function () {
			$(this).remove();
		});
		form.find('.d-none').each(function () {
			$(this).remove();
		});
		form.addClass('formReady');
		form.submit();
	}
});

$(document).on('change', '.input-content input', function () {
	$(this).closest('.input-content').find('.feedback').hide();
});

$('#add-new-row').click(function () {
	let html = $(this).closest('form').find('.copy-me').html();
	$(this)
		.closest('form')
		.find('.input-group:last')
		.after('<div class="input-group input-content py-1 px-2">' + html + '</div>');
});
$(document).on('click', '.remove-row', function () {
	$(this).closest('div').remove();
});

//////// - - -- - - --WM

if ('serviceWorker' in navigator) {
	navigator.serviceWorker.register('/sw.js').then(() => {
		console.log('Service Worker Registered');
	});
}

// Code to handle install prompt on desktop
let deferredPrompt;

window.addEventListener('beforeinstallprompt', function (e) {
	// Prevent Chrome 67 and earlier from automatically showing the prompt
	e.preventDefault();
	// Stash the event so it can be triggered later.
	deferredPrompt = e;
});
document.getElementById('get-app').addEventListener('click', (e) => {
	// hide our user interface that shows our A2HS button
	// btnAdd.style.display = 'none';
	alert('Wysłaliśmy do twojego urządzenia prośbę o zainstalowaniu aplikacji.');
	// Show the prompt
	deferredPrompt.prompt();
	// Wait for the user to respond to the prompt
	deferredPrompt.userChoice.then((choiceResult) => {
		if (choiceResult.outcome === 'accepted') {
			$('[data-alert=1022]').hide();
			console.log('User accepted the A2HS prompt');
		} else {
			console.log('User dismissed the A2HS prompt');
		}
		deferredPrompt = null;
	});
});

console.log('test 2022');
if (window.matchMedia('(display-mode: standalone)').matches) {
}

function isWebAppStandalone() {
	const STANDALONE = ':standalone:';
	const hash = window.location.hash;

	let standalone = false;

	if (hash === '#' + STANDALONE) {
		standalone = true;
		history.replaceState(history.state, '', '/');
	}

	if (window.matchMedia('(display-mode)').matches) {
		return window.matchMedia('(display-mode: standalone)').matches;
	}

	if (standalone) {
		sessionStorage.setItem(STANDALONE, '1');
	} else if (sessionStorage.getItem(STANDALONE)) {
		standalone = true;
	}

	return standalone;
}
if (isWebAppStandalone()) {
	console.log('display-mode is standalone');
	$('div[data-alert=1022]').remove();
}

window.addEventListener('appinstalled', (evt) => {
	console.log('a2hs installed');
	$('div[data-alert=1022]').remove();
	addDiv.style.display = 'none';
});
