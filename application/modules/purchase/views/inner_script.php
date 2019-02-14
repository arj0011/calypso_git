<script>
 jQuery('body').on('click', '#submit', function () {
        
        var form_name= this.form.id;
        if(form_name=='[object HTMLInputElement]')
            form_name='addForm';
        $("#"+form_name).validate({
            rules: {
                order_name: "required",
                order_email: "required",
               
            },
            messages: {
                order_name: "Full Name field is required",
                order_email: "Email field is required",
               
            },
            
           
        });

    }); 


    jQuery('body').on('change', '.input_img2', function () {

        var file_name = jQuery(this).val();
        var fileObj = this.files[0];
        var calculatedSize = fileObj.size / (1024 * 1024);
        var split_extension = file_name.split(".");
        var ext = ["jpg", "gif", "png", "jpeg"];
        if (jQuery.inArray(split_extension[1].toLowerCase(), ext) == -1)
        {
            $(this).val(fileObj.value = null);
            //showToaster('error',"You Can Upload Only .jpg, gif, png, jpeg  files !");
            $('.ceo_file_error').html('<?php echo lang('image_upload_error'); ?>');
            return false;
        }
        if (calculatedSize > 1)
        {
            $(this).val(fileObj.value = null);
            //showToaster('error',"File size should be less than 1 MB !");
            $('.ceo_file_error').html(' 1MB');
            return false;
        }
        if (jQuery.inArray(split_extension[1].toLowerCase(), ext) != -1 && calculatedSize < 10)
        {
            $('.ceo_file_error').html('');
            readURL(this);
        }
    });

    function readURL(input) {
        var cur = input;
        if (cur.files && cur.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $(cur).hide();
                $(cur).next('span:first').hide();
                $(cur).next().next('img').attr('src', e.target.result);
                $(cur).next().next('img').css("display", "block");
                $(cur).next().next().next('span').attr('style', "");
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    jQuery('body').on('click', '.remove_img', function () {
        var img = jQuery(this).prev()[0];
        var span = jQuery(this).prev().prev()[0];
        var input = jQuery(this).prev().prev().prev()[0];
        jQuery(img).attr('src', '').css("display", "none");
        jQuery(span).css("display", "block");
        jQuery(input).css("display", "inline-block");
        jQuery(this).css("display", "none");
        jQuery(".image_hide").css("display", "block");
        jQuery("#news_image").val("");
    });

       
  function show_message(msg) {
        var Base64 = {_keyStr: "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=", encode: function (e) {
                var t = "";
                var n, r, i, s, o, u, a;
                var f = 0;
                e = Base64._utf8_encode(e);
                while (f < e.length) {
                    n = e.charCodeAt(f++);
                    r = e.charCodeAt(f++);
                    i = e.charCodeAt(f++);
                    s = n >> 2;
                    o = (n & 3) << 4 | r >> 4;
                    u = (r & 15) << 2 | i >> 6;
                    a = i & 63;
                    if (isNaN(r)) {
                        u = a = 64
                    } else if (isNaN(i)) {
                        a = 64
                    }
                    t = t + this._keyStr.charAt(s) + this._keyStr.charAt(o) + this._keyStr.charAt(u) + this._keyStr.charAt(a)
                }
                return t
            }, decode: function (e) {
                var t = "";
                var n, r, i;
                var s, o, u, a;
                var f = 0;
                e = e.replace(/[^A-Za-z0-9\+\/\=]/g, "");
                while (f < e.length) {
                    s = this._keyStr.indexOf(e.charAt(f++));
                    o = this._keyStr.indexOf(e.charAt(f++));
                    u = this._keyStr.indexOf(e.charAt(f++));
                    a = this._keyStr.indexOf(e.charAt(f++));
                    n = s << 2 | o >> 4;
                    r = (o & 15) << 4 | u >> 2;
                    i = (u & 3) << 6 | a;
                    t = t + String.fromCharCode(n);
                    if (u != 64) {
                        t = t + String.fromCharCode(r)
                    }
                    if (a != 64) {
                        t = t + String.fromCharCode(i)
                    }
                }
                t = Base64._utf8_decode(t);
                return t
            }, _utf8_encode: function (e) {
                e = e.replace(/\r\n/g, "\n");
                var t = "";
                for (var n = 0; n < e.length; n++) {
                    var r = e.charCodeAt(n);
                    if (r < 128) {
                        t += String.fromCharCode(r)
                    } else if (r > 127 && r < 2048) {
                        t += String.fromCharCode(r >> 6 | 192);
                        t += String.fromCharCode(r & 63 | 128)
                    } else {
                        t += String.fromCharCode(r >> 12 | 224);
                        t += String.fromCharCode(r >> 6 & 63 | 128);
                        t += String.fromCharCode(r & 63 | 128)
                    }
                }
                return t
            }, _utf8_decode: function (e) {
                var t = "";
                var n = 0;
                var r = c1 = c2 = 0;
                while (n < e.length) {
                    r = e.charCodeAt(n);
                    if (r < 128) {
                        t += String.fromCharCode(r);
                        n++
                    } else if (r > 191 && r < 224) {
                        c2 = e.charCodeAt(n + 1);
                        t += String.fromCharCode((r & 31) << 6 | c2 & 63);
                        n += 2
                    } else {
                        c2 = e.charCodeAt(n + 1);
                        c3 = e.charCodeAt(n + 2);
                        t += String.fromCharCode((r & 15) << 12 | (c2 & 63) << 6 | c3 & 63);
                        n += 3
                    }
                }
                return t
            }}

        msg = Base64.decode(msg);
        $('#message_container').text(msg);
        $('#message_div').show();
    }
  function close_message(){
     $('#message_container').text('');
    $('#message_div').hide();
 }

 $('#common_datatable_cms').dataTable({
      order: [],
      columnDefs: [ { orderable: false, targets: [3,4] } ]
    });
 
 $(function(){
	var today = new Date();
	var dd = today.getDate();
	var mm = today.getMonth()+1; //January is 0!
	var yyyy = today.getFullYear();

	if(dd<10) {
		dd = '0'+dd
	} 

	if(mm<10) {
		mm = '0'+mm
	} 

	today = mm + '/' + dd + '/' + yyyy; 
	$('#order_date').val(today);
	
	var on = $('.order_name');
	var op = $('.order_phone');
	var oe = $('.order_email');
    var oi = $('.order_name_id');
	
	$(".order_name" ).autocomplete({
      source: function( request, response ) {
        $.ajax( {
          url: "/purchase/userSuggest",
          method:'POST',
		  dataType: "json",
          data: {
            key: request.term
          },
          success: function( data ) {
            response( data );
          }
        } );
      },
      minLength: 2,
      select: function( event, ui ) {
        op.val(ui.item.phone);
		oe.val(ui.item.email);
		oi.val(ui.item.id);
	  }
    });
	var cProContPar = '';
	
	var $bi = $('.billing_items');
	
	var autoCompleteWrapper = function(){
		
	$('body').find(".product_name" ).autocomplete({
      source: function( request, response ) {
		var cProCont = this.element; 
		cProContPar = $(cProCont).closest('.row');
        $.ajax( {
          url: "/purchase/productSuggest",
          method:'POST',
		  dataType: "json",
          data: {
            key: request.term
          },
          success: function( data ) {
            response( data );
          }
        } );
      },
      minLength: 2,
      select: function( event, ui ) {
        cProContPar.find('.product_price').val(ui.item.price);
        cProContPar.find('.product_id').val(ui.item.id);
		var price = cProContPar.find('.product_price').val();
		var qty = cProContPar.find('.product_qty').val();

		cProContPar.find('.product_total').val(parseFloat(price)*parseFloat(qty));
		cProContPar = '';
	  }
    });
	}
	
	autoCompleteWrapper();
	
	$bi.on('click','.addItem',function(){
		var $r = $bi.find('.row:last-child').clone(); 
		cleaner($r);
		$r.appendTo($bi);
		autoCompleteWrapper();
		regenNaming($bi);
	});
	
	$bi.on('click','.removeItem',function(){
		var _parent = $(this).closest('.row'); 
		_parent.remove();
		regenNaming($bi);
		setTotal();
	});
	
	$bi.on('blur','.product_qty',function(){
		var _that = $(this);
		if(_that.val()!='')
		{
		  var $p = $(this).closest('.row');
		  var $price = $p.find('.product_price').val();
		  var $qty = _that.val();
		  if($price=='' || $qty=='')
		  {
			$p.find('.product_total').val(0);
			  
		  }
		  else
		  {
			  $p.find('.product_total').val(parseInt($qty)*parseInt($price));
			
		  }
		  setTotal();
		  
		}
	});
	$bi.on('blur','input[type="text"].product_name',function(){
		var _that = $(this);
		if(_that.val()=='')
		{
		  var $p = $(this).closest('.row');
		  cleaner($p);
		}
		setTotal();
	});
	var cleaner = function($par)
	{
		$par.find('.product_price').val('');
		$par.find('.product_qty').val('1');
		$par.find('.product_total').val('');
		$par.find('.product_name').val('');
	}
	var regenNaming = function($bi){
		$pno = $bi.find('div.product_no');
		$pp = $bi.find('input[type="text"].product_price');
		$pq = $bi.find('input[type="number"].product_qty');
		$pn = $bi.find('input[type="text"].product_name');
		$pt = $bi.find('input[type="text"].product_total');
        $pi = $bi.find('input[type="hidden"].product_id');
		var i = 0;
		$pp.each(function(){
			$pno.eq(i).html(i+1);
			$pp.eq(i).attr('name','product_price['+i+']');
			$pq.eq(i).attr('name','product_qty['+i+']');
			$pn.eq(i).attr('name','product_name['+i+']');
			$pt.eq(i).attr('name','product_total['+i+']');
            $pi.eq(i).attr('name','product_id['+i+']');
			i++;
		});
	};
	var setTotal = function(){
	var total = 0;
	$bi.find('input[type="text"].product_total').each(function(){
			var _that = $(this);
			if(_that.val()!=0)
			{
				total += parseFloat(_that.val());
			}
	});
	$('.total').val(total);
	};
});

 $(document).ready(function(){
$('#user_id').select2();
});

 $('#common_datatable_purchase').dataTable({
      order: [],
      columnDefs: [ { orderable: false, targets: [9] } ]
    });
</script>

