(function ( $ ) {
	"use strict";

	$(function () {


		$('#truelysell-fafe-fields-editor').sortable({
			items: '.form_item',
			handle: '.handle',
			cursor: 'move',
			containment: 'parent',
			placeholder: 'my-placeholder',
			
		});	

		$('.field-options-custom tbody').sortable();


		
		$(".truelysell-forms-builder").on('click', '.truelysell-fafe-section-move-down', function(event){
			event.preventDefault();
			var section = $(this).parents('.truelysell-fafe-row-section');
			var next = $(this).parents('.truelysell-fafe-row-section').next();
			section.insertAfter(next);			
		});

		$(".truelysell-forms-builder").on('click', '.truelysell-fafe-section-move-up', function(event){
			event.preventDefault();
			var section = $(this).parents('.truelysell-fafe-row-section');
			var prev = $(this).parents('.truelysell-fafe-row-section').prev();
			section.insertBefore(prev);			
		});

		$('#truelysell-fafe-forms-editor,#truelysell-fafe-forms-editor-adv').sortable({
			items: '.form_item',
			handle: '.handle',
			cursor: 'move',
			containment: 'parent',
			placeholder: 'my-placeholder',
			connectWith: '#truelysell-fafe-forms-editor,#truelysell-fafe-forms-editor-adv',
			stop: function(event, ui) {
		        $(".form_item").each(function(i, el){
		            $(this).find('.priority_field').val ($(el).index() );
		        });
		    },
		    receive: function (e, ui) {
		        ui.sender.data('copied', true);
		        console.log(ui);
		    }
		});

		function randomIntFromInterval(min,max){
		    return Math.floor(Math.random()*(max-min+1)+min);
		}

		$( ".form-editor-available-elements-container" ).sortable({
			items: '.form_item',
			handle: '.handle',
			connectWith: '.form-editor-container',
			helper: function (e, li) {
				
				if(li.hasClass('form_item_header')) {
					var copy = li.clone();
					var formRowCount = $('#truelysell-fafe-forms-editor .form_item').length+25;
					$('.name-container input',copy).val('header'+randomIntFromInterval(20,990));
					$('input',copy).attr('name').replace(/^(\[)\d+(\].+)$/, '$1' + formRowCount + '$2');
					copy.find('input,select').each(function() {
				        var $this = $(this);
				        
				        $this.attr('name', $this.attr('name').replace(/\[(\d+)\]/, '[' + formRowCount + ']'));
				        
				    });
				    formRowCount++;
					this.copyHelper = copy.insertAfter(li);
					 $(this).data('copied', false);
			        return li.clone();
				} else {
					return li.data('copied', true);
				}
		    },
			stop: function(event, ui) {
	
				var copied = $(this).data('copied');
				
		        if (!copied) {
		            this.copyHelper.remove();
		        }

		        this.copyHelper = null;
				$(".form_item").each(function(i, el){
					var i = $(el).index();
					if($(el).parent().hasClass('adv')){
						if($(el).parent().hasClass('panel')){
							$(this).find('.place_hidden').val('panel')
						} else {
							$(this).find('.place_hidden').val('adv')	
						}
						
					} else {
						$(this).find('.place_hidden').val('main')
					}
					if( $(this).find('.priority_field').lenght > 0 ) {
						$(this).find('.priority_field').attr('name').replace(/(\[\d\])/, '[' + $(el).index() + ']'); 	
					}
		        
		    	});
		    }
		});

		$(".truelysell-forms-builder,.truelysell-forms-builder-right").on('click', '#truelysell-show-names', function(){
			$('.name-container').show();
		});

		$('.form-editor-container').on( 'click', '.element_title', function() {

			$(this).next().slideToggle();
		});

	 
	    $(".remove_item").click(function(event) {
 			event.preventDefault();
 			if (window.confirm("Are you sure?")) {
	 			$(this).parent().fadeOut(300, function() { $(this).remove(); });
	 		}
 		});
 		
 		$(".field-options-custom").on('click', '.remove_row', function(event){
 			event.preventDefault();
 			if (window.confirm("Are you sure?")) {
	 			$(this).parent().fadeOut(300, function() { $(this).remove(); });
	 		}
 		});

	    
		/*fields editor*/
		$('#truelysell-fafe-fields-editor, #truelysell-fafe-forms-editor,#truelysell-fafe-forms-editor-adv')
		.on( 'init', function() {
			$('.step-error-too-many').hide();
			$('.step-error-exceed').hide();
			$(this).find( '.field-type-selector' ).change();
			$(this).find( '.field-type select' ).change();
			$(this).find( '.field-edit-class-select' ).change();
			$(this).find( '.field-options-data-source-choose' ).change();
		})
		.on( 'change', '.field-type select', function() {
			$(this).parent().parent().find('.field-options').hide();

			if ( 'select' === $(this).val() || 'select_multiple' === $(this).val() || 'checkbox' === $(this).val() || 'multicheck_split' === $(this).val() || 'radio' === $(this).val()) {
				$(this).parent().parent().find('.field-options').show();
			} 

		})
		.on( 'change', '.field-options-data-source-choose', function() {
			if ( 'predefined' === $(this).val() ) {
				$(this).parent().find('.field-options-predefined').show();
				$(this).parent().find('.field-options-custom').hide();
			}
			if ( 'custom' === $(this).val() ) {
				$(this).parent().find('.field-options-predefined').hide().val("");
				$(this).parent().find('.field-options-custom').show();
			}
			if ( '' === $(this).val() ) {
				$(this).parent().find('.field-options-predefined').hide().val("");
				$(this).parent().find('.field-options-custom').hide();
			}
		})
		.on( 'change', '.field-edit-class-select', function() {
			if ( 'col-md-12' === $(this).val() ) {
				$(this).parent().parent().find('.open_row-container').hide().find('input').prop('checked', true);
				$(this).parent().parent().find('.close_row-container').hide().find('input').prop('checked', true);
				
			} else {
				$(this).parent().parent().find('.open_row-container').show();
				$(this).parent().parent().find('.close_row-container').show();
				
			}
			if ( '' === $(this).val() ) {
				$(this).parent().parent().find('.open_row-container').hide().find('input').prop('checked', false);
				$(this).parent().parent().find('.close_row-container').hide().find('input').prop('checked', false);
			}
			if ( 'custom' === $(this).val() ) {
				$(this).parent().find('.field-options-predefined').hide().val("");
				$(this).parent().find('.field-options-custom').show();
			}
		})
		.on( 'click', '.remove-row', function(e){
			e.preventDefault();
			
 			if (window.confirm("Are you sure?")) {
	 			$(this).parent().parent().fadeOut(300, function() { $(this).remove(); });
	 		}
		})
		.on( 'click', '.add-new-option-table', function(e){
			e.preventDefault();
			var $tbody = $(this).closest('table').find('tbody');
			var row    = $tbody.data( 'field' );
			row = row.replace( /\[-1\]/g, "[" + $tbody.find('tr').size() + "]");
			$tbody.append( row );
		})
		.on('change', '.step-container', function() {
			var form = $(this).parent().parent();
			var max = form.find('.max-container input').val();
			var min = form.find('.min-container input').val();
			var step = $(this).find('input').val();
			$('.step-error-too-many').hide();
			$('.step-error-exceed').hide();
			if(step > (max-min)){
				form.find('.step-error-exceed').show();
			}
			var offset = 0;
			var len = (Math.abs(max - min)  + ((offset || 0) * 2)) / (step || 1) + 1;
			
			if(len > 30){
				form.find('.step-error-too-many').show();
			}
		})
		.on('change', '.min-container', function() {
			var form = $(this).parent().parent();
			var max = form.find('.max-container input').val();
			var min = form.find('.min-container input').val();
			var step = $(this).find('input').val();
			$('.step-error-too-many').hide();
			$('.step-error-exceed').hide();
			if(step > (max-min)){
				form.find('.step-error-exceed').show();
			}
			var offset = 0;
			var len = (Math.abs(max - min)  + ((offset || 0) * 2)) / (step || 1) + 1;
			console.log(len);
			if(len > 30){
				form.find('.step-error-too-many').show();
			}
		})
		.on('change', '.max-container', function() {
			var form = $(this).parent().parent();
			var max = form.find('.max-container input').val();
			var min = form.find('.min-container input').val();
			var step = $(this).find('input').val();
			$('.step-error-too-many').hide();
			$('.step-error-exceed').hide();
			if(step > (max-min)){
				form.find('.step-error-exceed').show();
			}
			var offset = 0;
			var len = (Math.abs(max - min)  + ((offset || 0) * 2)) / (step || 1) + 1;
			console.log(len);
			if(len > 30){
				form.find('.step-error-too-many').show();
			}
		})
		.on('change', '.field-type-selector', function() {
		  var form = $(this).parent().parent();
		  var type = $(this).val();
		
		  switch (type) { 
				case 'select': 
				case 'radio': 
				case 'multicheck_split': 
				case 'multi-select': 
					form.find('.options-container').show();
					form.find('.multi-container').show();
					form.find('.max-container').hide();
					form.find('.min-container').hide();
					form.find('.step-container').hide();
					form.find('.unit-container').hide();
					form.find('.taxonomy-container').hide();
					break;
				case 'select-taxonomy': 
				case 'term-select': 
					form.find('.multi-container').show();
					form.find('.taxonomy-container').show();
					form.find('.options-container').hide();
					form.find('.max-container').hide();
					form.find('.min-container').hide();
					form.find('.step-container').hide();
					form.find('.unit-container').hide();
					break;
				case 'input-select': 
				case 'slider': 
				case 'double-input': 
					form.find('.options-container').hide();
					form.find('.multi-container').hide();
					form.find('.max-container').show();
					form.find('.min-container').show();
					form.find('.step-container').show();
					form.find('.unit-container').show();
					break;		
				case 'multi-checkbox': 
				case 'multi-checkbox-row': 
					form.find('.options-container').show();
					form.find('.taxonomy-container').show();
					form.find('.multi-container').hide();
					form.find('.max-container').hide();
					form.find('.min-container').hide();
					form.find('.step-container').hide();
					form.find('.unit-container').hide();
					break;
				case 'header': 
					form.find('.max-container').hide();
					form.find('.min-container').hide();
					form.find('.multi-container').hide();
					form.find('.step-container').hide();
					form.find('.unit-container').hide();
					form.find('.options-container').hide();
					form.find('.taxonomy-container').hide();
					break;
				case 'radius': 
					form.find('.max-container').show();
					form.find('.min-container').show();
					
					form.find('.multi-container').hide();
					form.find('.step-container').hide();
					form.find('.unit-container').hide();
					form.find('.options-container').hide();
					form.find('.taxonomy-container').hide();
					break;
				default:
					form.find('.max-container').hide();
					form.find('.min-container').hide();
					form.find('.multi-container').hide();
					form.find('.step-container').hide();
					form.find('.unit-container').show();
					form.find('.options-container').hide();
					form.find('.taxonomy-container').hide();
			}

		  // Does some stuff and logs the event to the console
		});
		
		$('#truelysell-fafe-fields-editor').trigger( 'init' );
		$('#truelysell-fafe-forms-editor').trigger( 'init' );
		$('#truelysell-fafe-forms-editor-adv').trigger( 'init' );


		$('.truelysell-forms-builder-top').on('click', '.add_new_item', function(e) {
			e.preventDefault();
			var name;
		    do {
		        name=prompt("Please enter field name");
		    }
			while(name.length < 2);
			var clone    = $('#truelysell-fafe-fields-editor').data( 'clone' );
			var id = string_to_slug(name);
			var index = $('.form_item').size()+1; 
			clone = clone.replace( /\[-2\]/g, "[" + index + "]").replace( /clone/g, name);
			$('#truelysell-fafe-fields-editor').append(clone);
			$('#truelysell-fafe-fields-editor .form_item:last-child .edit-form-field').toggle().find('.field-id input').val('_'+id);
		});


		$('.truelysell-form-editor table')
		.on( 'click', '.add-new-main-option', function(e){
			e.preventDefault();
			var $tbody = $(this).closest('table').find('tbody');
			var row    = $tbody.data( 'field' );
			
			row = row.replace( /\[-1\]/g, "[" + $tbody.find('tr').size() + "]");
			
			$tbody.append( row );
		})
		.on( 'click', '.remove-row', function(e){
			e.preventDefault();
			
 			if (window.confirm("Are you sure?")) {
	 			$(this).parent().parent().fadeOut(300, function() { $(this).remove(); });
	 		}
		})
		
		function string_to_slug (str) {
		    str = str.replace(/^\s+|\s+$/g, ''); // trim
		    str = str.toLowerCase();
		  
		    // remove accents, swap ñ for n, etc
		    var from = "àáäâèéëêìíïîòóöôùúüûñç·/_,:;";
		    var to   = "aaaaeeeeiiiioooouuuunc------";
		    for (var i=0, l=from.length ; i<l ; i++) {
		        str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
		    }

		    str = str.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
		        .replace(/\s+/g, '_') // collapse whitespace and replace by -
		        .replace(/-+/g, '_'); // collapse dashes

		    return str;
		}


		// Submit Form Editor

		
		$('.row-container').sortable({
			items: '.editor-block',
			// handle: '.handle',
			cursor: 'move',
			
			connectWith: '.row-container',
			placeholder: 'my-placeholder',
		    update: function(event, ui){
                if(ui.sender){
                    var section_old = ui.sender.data('section');
                    var section_new = $(this).data("section")
                    
                    $(ui.item).find('input,select').each(function(){
    				
    					var newname = this.name.replace(section_old,section_new);
    					this.name = newname;
    					//$(this).attr('name',newname);
  					});      
                }
            }
		    // stop: function(event, ui) {
		       

		    //     // $(".form_item").each(function(i, el){
		    //     //     $(this).find('.priority_field').val( $(el).index() );
		    //     // });
		    // },
		    // receive: function (e, ui) {
		    //     ui.sender.data('copied', true);
		    // }
		}).disableSelection();	

		var widths = ["block-width-3", "block-width-4", "block-width-6","block-width-12"];
		var widths_nr = ["3", "4", "6","12"];
		$('.form-editor-container').on( 'click', '.block-wider a', function(e){
			e.preventDefault();
			var className = $(this).parents().eq(3).attr('class').match(/block-width-\d+/);
			if (className) {
				var cur_width_index = widths.indexOf(className[0]);
				console.log(cur_width_index);
				if(cur_width_index < 3) {
					console.log($(this).parents('.editor-block'));
					$(this).parents('.editor-block').removeClass(widths[cur_width_index]).addClass(widths[cur_width_index+1]);	
					$(this).parents('.editor-block').find('.block-width-input').val(widths_nr[cur_width_index+1]);
				}
			}
		})
		$('.form-editor-container').on( 'click', '.block-narrower a', function(e){
			e.preventDefault();
			var className = $(this).parents().eq(3).attr('class').match(/block-width-\d+/);
			if (className) {
				var cur_width_index = widths.indexOf(className[0]);
				console.log(cur_width_index);
				if(cur_width_index > 0) {
					console.log($(this).parents('.editor-block'));
					$(this).parents('.editor-block').removeClass(widths[cur_width_index]).addClass(widths[cur_width_index-1]);	
					$(this).parents('.editor-block').find('.block-width-input').val(widths_nr[cur_width_index-1]);
				}
			}
		})

		$('.form-editor-container').on( 'click', '.block-edit a', function(e){
			var form_fields;
			e.preventDefault();
				$('.truelysell-editor-modal-title').html('Edit Field');
			$('.truelysell-editor-modal-footer .button-primary').html('Save Field');
			form_fields = $(this).parents('.editor-block').find('.editor-block-form-fields').addClass('edited-now').html();
			$('.truelysell-modal-form').html(form_fields);

			$('.edited-now').find('select').each(function(i) {
				var value = $(this).val();
				console.log(value);
     			$('.truelysell-modal-form').find('select').eq(i).val(value);
			});
			$('.edited-now').find('input[type="checkbox"]').each(function(i) {
				
				if($(this).is(":checked")) {
					$('.truelysell-modal-form').find('input[type="checkbox"]').eq(i).prop('checked',true);	
				} else {
					$('.truelysell-modal-form').find('input[type="checkbox"]').eq(i).prop('checked',false);	
				}
     			
			});
			
			$('.truelysell-editor-modal').show();
		
		});

		$('.form-editor-container').on( 'click', '.block-delete a', function(e){
			$(this).parents('.editor-block').remove();
			e.preventDefault();
		});

		$('.form-editor-container').on( 'click', '.block-add-new a', function(e){
			e.preventDefault();
			$('.truelysell-editor-modal-title').html('Add New Field');
			$('.truelysell-editor-modal-footer .button-primary').html('Add Field');
			var section = $(this).data('section');
			var ajax_data = {
				'action'	: 'truelysell_editor_get_items', 
				'section'	: section
				//'nonce': nonce		
			};

			$.ajax({
	            type: 'POST', dataType: 'json',
				url: ajaxurl,
				data: ajax_data,
				
	            success: function(data){
	            	console.log(data);
					$('.truelysell-modal-form').html(data.data.items);
					$('.truelysell-editor-modal').show();
	            }
	        });
		});
		
		$('.truelysell-modal-close, .truelysell-cancel').on( 'click', function(e){
			e.preventDefault();
			$('.truelysell-editor-modal').hide();
			$('.truelysell-modal-form').html('');
			$('.editor-block-form-fields').removeClass('edited-now')
		});

		
		$('#truelysell-save-field').on( 'click', function(e){
			
			e.preventDefault();           

			$('.truelysell-modal-form input').each(function(){
			    $(this).attr('value',$(this).val());
			
			});

			var new_fields = $('.truelysell-modal-form').html();
			$('.edited-now').html(new_fields);
			
			$('.truelysell-modal-form').find('input[type="checkbox"]').each(function(i) {
				
				if($(this).is(":checked")) {
					$('.edited-now').find('input[type="checkbox"]').eq(i).prop('checked',true);	
				} else {
					$('.edited-now').find('input[type="checkbox"]').eq(i).prop('checked',false);	
				}
     			
			});

			$('.truelysell-modal-form').find('select').each(function(i) {
				var value = $(this).val();
     			$('.edited-now').find('select').eq(i).val(value);
			});


			$('.truelysell-editor-modal').hide();
			//$('.truelysell-modal-form').html('');
			$('.editor-block-form-fields').removeClass('edited-now');
			$('.section_options').removeClass('edited-now');
		
		});

		$('.truelysell-modal-form').on( 'click', '.insert-field', function(e){
			e.preventDefault();
			var section = $(this).data('section');
			var field = $(this).parent().find('.editor-block').clone();
			
			field.show();
			
			// console.log(section);
			// console.log($("div").find("[data-section='" + section + "']"));
			//$(".row-"+section).append(field).show();
			// $(field).appendTo($(".row-"+section)).show();
			// $("TEEST").appendTo($(".row-"+section));s
			
			if($(".row-"+section+" .editor-block").length > 0){
				$(".row-"+section+" .editor-block:last").after(field);		
			} else {
				$(".row-"+section+"").append(field);	
			}
			
		
			$('.row-container').sortable('refresh');
			$('.truelysell-editor-modal').hide();
		});

		$('.form-editor-container').on('click','.truelysell-fafe-section-edit', function(e){
			e.preventDefault();
			var form_fields;
				$('.truelysell-editor-modal-title').html('Edit Section');
			$('.truelysell-editor-modal-footer .button-primary').html('Save Changes');
			form_fields = $(this).parent().parent().find('.section_options').addClass('edited-now').html();
		
			$('.truelysell-modal-form').html(form_fields);

			$('.edited-now').find('select').each(function(i) {
				var value = $(this).val();
     			$('.truelysell-modal-form').find('select').eq(i).val(value);
			});

			$('.edited-now').find('input[type="checkbox"]').each(function(i) {
				
				if($(this).is(":checked")) {
					$('.truelysell-modal-form').find('input[type="checkbox"]').eq(i).prop('checked',true);	
				} else {
					$('.truelysell-modal-form').find('input[type="checkbox"]').eq(i).prop('checked',false);	
				}
     			
			});
			
			$('.truelysell-editor-modal').show();
		});

		$('.truelysell-fafe-new-section').on('click',function(e){
			e.preventDefault();
			var name;
		    do {
		        name=prompt("Please enter section name");
		    }
			while(name.length < 2);
			var clone    = $('.form-editor-container').data('section');
			var id = string_to_slug(name);
			
			clone = clone.replace( '{section_org}', name).replace(/{section}/g, id);
			
			$('.form-editor-container').append(clone);
			$('.row-container').sortable();
			//$('#truelysell-fafe-fields-editor .form_item:last-child .edit-form-field').toggle().find('.field-id input').val('_'+id);
		});

		$('.form-editor-container').on('click','.truelysell-fafe-section-remove-section', function(e){
			e.preventDefault();
			$(this).parents('.truelysell-fafe-section').next().remove();
			$(this).parents('.truelysell-fafe-section').remove();
		});

		$('.editor-block').each(function(i, el){
        	
            var css_class = $(this).find('select#field_for_type').val();
            $(this).addClass('type-'+css_class);
            
        });
		

		$('.show-fields-type-service').on('click',function(e){
			e.preventDefault();
			$('.truelysell-editor-listing-types a').removeClass('active');
			$(this).addClass('active');
			$('.type-event').hide();
			$('.type-rental').hide();
			$('.type-service').show();
			$('.type-classifieds').hide();
		})

		$('.show-fields-type-rentals').on('click',function(e){
			e.preventDefault();
			$('.truelysell-editor-listing-types a').removeClass('active');
			$(this).addClass('active');
			$('.type-event').hide();
			$('.type-rental').show();
			$('.type-service').hide();
			$('.type-classifieds').hide();
		})

		$('.show-fields-type-events').on('click',function(e){
			e.preventDefault();
			$('.truelysell-editor-listing-types a').removeClass('active');
			$(this).addClass('active');
			$('.type-event').show();
			$('.type-rental').hide();
			$('.type-service').hide();
			$('.type-classifieds').hide();
		})
		
		$('.show-fields-type-classifieds').on('click',function(e){
			e.preventDefault();
			$('.truelysell-editor-listing-types a').removeClass('active');
			$(this).addClass('active');
			$('.type-classifieds').show();
			$('.type-event').hide();
			$('.type-rental').hide();
			$('.type-service').hide();
		})

		$('.show-fields-type-all').on('click',function(e){
			e.preventDefault();
			$('.truelysell-editor-listing-types a').removeClass('active');
			$(this).addClass('active');
			$('.type-event').show();
			$('.type-rental').show();
			$('.type-service').show();
			$('.type-classifieds').show();
		})
		
	/*eof*/

	});

}(jQuery));
